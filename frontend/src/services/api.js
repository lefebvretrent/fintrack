import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
  },
})

// Categories
export const getCategories = () => api.get('/categories')
export const createCategory = (data) => api.post('/categories', data)
export const deleteCategory = (id) => api.delete(`/categories/${id}`)

// Transactions
export const getTransactions = () => api.get('/transactions')
export const createTransaction = (data) => api.post('/transactions', data)
export const deleteTransaction = (id) => api.delete(`/transactions/${id}`)

// Budgets
export const getBudgets = (month) => api.get('/budgets', { params: { month } })
export const createBudget = (data) => api.post('/budgets', data)
export const deleteBudget = (id) => api.delete(`/budgets/${id}`)

// Summary
export const getSummary = (month) => api.get('/summary', { params: { month } })