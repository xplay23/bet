import { createRouter, createWebHashHistory } from 'vue-router'
import Home from '../views/Home.vue'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/add',
    name: 'AddBet',
    component: () => import('../views/AddBet.vue')
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/Register.vue')
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue')
  },
  {
    path: '/reset',
    name: 'Reset',
    component: () => import('../views/Reset.vue')
  },
  {
    path: '/bet/:id',
    name: 'Bet',
    component: () => import('../views/Bet.vue')
  },
  {
    path: '/user',
    name: 'User',
    component: () => import('../views/User.vue')
  },
  {
    path: '/leaderboard',
    name: 'Leaderboard',
    component: () => import('../views/Leaderboard.vue')
  },
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

export default router
