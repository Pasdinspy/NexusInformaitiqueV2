<?php
// Inclure le fichier database.php
require_once '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $username = $_POST["username"];
    $password = $_POST["password"];
}

try {
    // Création d'une instance de la classe Database
    $database = new Database();
    $cnxBDD = $database->getConnection();

    // Requête préparée pour éviter les injections SQL
    $sql = "SELECT VIS_ID, role FROM user WHERE username = :username AND password = :password";
    $stmt = $cnxBDD->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    // Récupération des résultats
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($result as $ligne) {
        $role = $ligne['role'];
        if ($role == "VISITEUR_MEDICAL") {
            $VIS_ID = $ligne['VIS_ID'];
            // Redirection avec les paramètres appropriés
            header("Location: ../../gsb-front/src/views/FicheFraisView.vue?role=$role&VIS_ID=$VIS_ID");
        } elseif ($role == "ADMINISTRATEUR") {
            header("Location: ../../gsb-front/src/views/FicheFraisView.vue?role=$role");
        } elseif ($role == "COMPTABLE") {
            header("Location: ../../gsb-front/src/views/FicheFraisView.vue?role=$role");
        }
    }
} catch (PDOException $e) {
    // Gestion des erreurs de connexion
    echo "Connection error: " . $e->getMessage();
}

exit();
?>