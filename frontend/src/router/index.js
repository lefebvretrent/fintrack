import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/DashboardView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'dashboard',
      component: Dashboard,
    },
    {
      path: '/transactions',
      name: 'transactions',
      component: () => import('../views/TransactionsView.vue'),
    },
	{
		path: '/budgets',
      name: 'budgets',
      component: () => import('../views/BudgetsView.vue'),

	},
	{
		path: '/categories',
      name: 'categories',
      component: () => import('../views/CategoriesView.vue'),
	}
  ],
})

export default router
