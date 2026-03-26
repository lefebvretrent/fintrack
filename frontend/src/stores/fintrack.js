import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import {
  getCategories, createCategory, deleteCategory,
  getTransactions, createTransaction, deleteTransaction,
  getBudgets, createBudget,
  getSummary
} from '../services/api'

export const useFintrackStore = defineStore('fintrack', () => {
  // state
  const categories = ref([])
  const transactions = ref([])
  const budgets = ref([])
  const summary = ref(null)
  const currentMonth = ref(new Date().toISOString().slice(0, 7))
  const loading = ref(false)
  const error = ref(null)

  // getters
  const totalIncome = computed(() => summary.value?.total_income ?? 0)
  const totalExpenses = computed(() => summary.value?.total_expenses ?? 0)
  const net = computed(() => summary.value?.net ?? 0)

  // actions
  async function fetchCategories() {
    const res = await getCategories()
    categories.value = res.data
  }

  async function addCategory(data) {
    const res = await createCategory(data)
    categories.value.push(res.data)
  }

  async function removeCategory(id) {
    await deleteCategory(id)
    categories.value = categories.value.filter(c => c.id !== id)
  }

  async function fetchTransactions() {
    const res = await getTransactions()
    transactions.value = res.data
  }

  async function addTransaction(data) {
    loading.value = true
    error.value = null
    try {
      const res = await createTransaction(data)
      transactions.value.unshift(res.data)
      await fetchSummary()
    } catch (e) {
      error.value = 'Failed to add transaction'
    } finally {
      loading.value = false
    }
  }

  async function removeTransaction(id) {
    await deleteTransaction(id)
    transactions.value = transactions.value.filter(t => t.id !== id)
    await fetchSummary()
  }

  async function fetchBudgets() {
    const res = await getBudgets(currentMonth.value)
    budgets.value = res.data
  }

  async function saveBudget(data) {
    const res = await createBudget(data)
    const index = budgets.value.findIndex(
      b => b.category.id === data.category_id && b.month === data.month
    )
    if (index >= 0) {
      budgets.value[index] = res.data
    } else {
      budgets.value.push(res.data)
    }
    await fetchSummary()
  }

  async function fetchSummary() {
    const res = await getSummary(currentMonth.value)
    summary.value = res.data
  }

  async function fetchAll() {
    loading.value = true
    try {
      await Promise.all([
        fetchCategories(),
        fetchTransactions(),
        fetchBudgets(),
        fetchSummary(),
      ])
    } catch (e) {
      error.value = 'Failed to load data'
    } finally {
      loading.value = false
    }
  }

  return {
    categories, transactions, budgets, summary,
    currentMonth, loading, error,
    totalIncome, totalExpenses, net,
    fetchAll, fetchCategories, addCategory, removeCategory,
    fetchTransactions, addTransaction, removeTransaction,
    fetchBudgets, saveBudget, fetchSummary,
  }
})