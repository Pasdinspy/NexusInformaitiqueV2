import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import FicheFraisView from '../views/FicheFraisView.vue'

const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView
  },
  {
    path: '/fiche-frais',
    name: 'fiche-frais',
    component: FicheFraisView,
    meta: { requiresAuth: true },
    props: route => ({ 
      role: route.query.role,
      VIS_ID: route.query.VIS_ID 
    })
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

router.beforeEach((to, from, next) => {
  console.log('Navigation guard - destination:', to.path)
  const store = router.app.$store

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!store?.state?.auth?.utilisateur) {
      console.log('Redirection vers login - utilisateur non authentifié')
      next('/login')
    } else {
      console.log('Utilisateur authentifié - autorisation accordée')
      next()
    }
  } else {
    next()
  }
})

export default router