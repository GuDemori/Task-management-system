import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView,
    },
    {
      path: '/create-task',
      name: 'createTask',
      component: () => import('@/views/CreateTask.vue')
    },
    {
      path: '/tasks',
      name: 'taskList',
      component: () => import('@/views/TaskList.vue')
    },
    {
      path: '/tasks',
      name: 'taskList',
      component: () => import('@/views/TaskList.vue')
    },
    
  ],
})

export default router
