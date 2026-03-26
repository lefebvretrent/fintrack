<script setup>
import { computed } from 'vue'
import { useFintrackStore } from '../stores/fintrack'
import Dashboard from '../components/Dashboard.vue'

const store = useFintrackStore()
</script>

<template>
  <div>
    <div class="page-header">
      <h1>Dashboard</h1>
      <span class="month-label">{{ store.currentMonth }}</span>
    </div>

    <div class="stats-grid">
      <div class="stat-card income">
        <div class="stat-label">Total Income</div>
        <div class="stat-value">${{ store.totalIncome.toFixed(2) }}</div>
      </div>
      <div class="stat-card expenses">
        <div class="stat-label">Total Expenses</div>
        <div class="stat-value">${{ store.totalExpenses.toFixed(2) }}</div>
      </div>
      <div class="stat-card" :class="store.net >= 0 ? 'income' : 'expenses'">
        <div class="stat-label">Net</div>
        <div class="stat-value">${{ store.net.toFixed(2) }}</div>
      </div>
    </div>

    <Dashboard />
  </div>
</template>

<style scoped>
.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1.5rem;
}

.page-header h1 {
  font-size: 1.75rem;
  font-weight: 700;
}

.month-label {
  background: #1a1a2e;
  color: #fff;
  padding: 0.35rem 0.85rem;
  border-radius: 20px;
  font-size: 0.85rem;
}

.stats-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 1rem;
  margin-bottom: 1.5rem;
}

.stat-card {
  background: #fff;
  border-radius: 12px;
  padding: 1.25rem 1.5rem;
  box-shadow: 0 1px 4px rgba(0,0,0,0.08);
  border-left: 4px solid #e2e8f0;
}

.stat-card.income {
  border-left-color: #48bb78;
}

.stat-card.expenses {
  border-left-color: #fc8181;
}

.stat-label {
  font-size: 0.8rem;
  color: #718096;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 0.5rem;
}

.stat-value {
  font-size: 1.75rem;
  font-weight: 700;
  color: #1a1a2e;
}
</style>