<script setup>
import { useFintrackStore } from '../stores/fintrack'
import BudgetForm from '../components/BudgetForm.vue'

const store = useFintrackStore()
</script>

<template>
  <div>
    <div class="page-header">
      <h1>Budgets</h1>
      <span class="month-label">{{ store.currentMonth }}</span>
    </div>

    <div class="two-col">
      <div>
        <div class="card">
          <h2>Set Budget</h2>
          <BudgetForm />
        </div>
      </div>

      <div>
        <div class="card">
          <h2>Current Budgets</h2>
          <div v-if="store.budgets.length === 0" class="empty">
            No budgets set for this month yet.
          </div>
          <div
            v-for="budget in store.budgets"
            :key="budget.id"
            class="budget-item"
          >
            <div class="budget-left">
              <span
                class="category-dot"
                :style="{ background: budget.category.color }"
              ></span>
              <span class="budget-name">{{ budget.category.name }}</span>
            </div>
            <span class="budget-amount">${{ parseFloat(budget.amount_limit).toFixed(2) }}</span>
          </div>
        </div>
      </div>
    </div>
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

.two-col {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 1rem;
  align-items: start;
}

.budget-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.75rem 0;
  border-bottom: 1px solid #f0f2f5;
}

.budget-item:last-child {
  border-bottom: none;
}

.budget-left {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.category-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
}

.budget-name {
  font-weight: 500;
}

.budget-amount {
  font-weight: 600;
  color: #1a1a2e;
}

.empty {
  color: #a0aec0;
  text-align: center;
  padding: 2rem;
}
</style>