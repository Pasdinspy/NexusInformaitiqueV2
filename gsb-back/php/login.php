<?php
// Activation des erreurs PHP pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Headers CORS et JSON
header('Access-Control-Allow-Origin: http://51.83.74.206:8080');
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// Gérer les requêtes OPTIONS (pre-flight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Log pour déboguer
error_log('Requête reçue: ' . file_get_contents('php://input'));

// Vérifier la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'succes' => false,
        'message' => 'Méthode non autorisée'
    ]);
    exit();
}

// Récupérer et décoder les données JSON
$donnees = json_decode(file_get_contents('php://input'), true);
error_log('Données reçues: ' . print_r($donnees, true));

// Vérifier les données reçues
if (!isset($donnees['username']) || !isset($donnees['password'])) {
    http_response_code(400);
    echo json_encode([
        'succes' => false,
        'message' => 'Données de connexion manquantes'
    ]);
    exit();
}

try {
    require_once '../config/database.php';
    
    // Connexion à la base de données
    $database = new Database();
    $cnxBDD = $database->getConnection();
    
    // Requête préparée
    $sql = "SELECT VIS_ID, role FROM user WHERE username = :username AND password = :password";
    $stmt = $cnxBDD->prepare($sql);
    
    // Liaison des paramètres
    $stmt->bindParam(':username', $donnees['username']);
    $stmt->bindParam(':password', $donnees['password']);
    
    // Exécution de la requête
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
        http_response_code(200);
        echo json_encode([
            'succes' => true,
            'message' => 'Connexion réussie',
            'donnees' => [
                'role' => $utilisateur['role'],
                'VIS_ID' => $utilisateur['VIS_ID'] ?? null
            ]
        ]);
    } else {
        http_response_code(401);
        echo json_encode([
            'succes' => false,
            'message' => 'Identifiants incorrects'
        ]);
    }
} catch (PDOException $e) {
    error_log('Erreur PDO: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'succes' => false,
        'message' => 'Erreur de base de données: ' . $e->getMessage()
    ]);
}
?>