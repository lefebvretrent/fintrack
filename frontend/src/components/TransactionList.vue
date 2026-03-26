<script setup>
import { useFintrackStore } from '../stores/fintrack'

const store = useFintrackStore()

function formatDate(dateStr) {
  return new Date(dateStr).toLocaleDateString('en-CA', {
    month: 'short',
    day: 'numeric',
  })
}
</script>

<template>
  <div>
    <div v-if="store.transactions.length === 0" class="empty">
      No transactions yet. Add one!
    </div>

    <div
      v-for="transaction in store.transactions"
      :key="transaction.id"
      class="transaction-item"
    >
      <div class="transaction-left">
        <span
          class="category-dot"
          :style="{ background: transaction.category.color }"
        ></span>
        <div>
          <div class="transaction-desc">{{ transaction.description }}</div>
          <div class="transaction-meta">
            {{ transaction.category.name }} · {{ formatDate(transaction.date) }}
          </div>
        </div>
      </div>

      <div class="transaction-right">
        <span
          class="transaction-amount"
          :class="transaction.type === 'income' ? 'income' : 'expense'"
        >
          {{ transaction.type === 'income' ? '+' : '-' }}${{ parseFloat(transaction.amount).toFixed(2) }}
        </span>
        <button class="btn btn-danger btn-sm" @click="store.removeTransaction(transaction.id)">
          ✕
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.transaction-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.75rem 0;
  border-bottom: 1px solid #f0f2f5;
}

.transaction-item:last-child {
  border-bottom: none;
}

.transaction-left {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.category-dot {
  width: 10px;
  height: 10px;
  border-radius: 50%;
  flex-shrink: 0;
}

.transaction-desc {
  font-weight: 500;
  font-size: 0.95rem;
}

.transaction-meta {
  font-size: 0.8rem;
  color: #718096;
  margin-top: 0.15rem;
}

.transaction-right {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.transaction-amount {
  font-weight: 600;
  font-size: 0.95rem;
}

.transaction-amount.income {
  color: #48bb78;
}

.transaction-amount.expense {
  color: #fc8181;
}

.btn-sm {
  padding: 0.2rem 0.5rem;
  font-size: 0.75rem;
}

.empty {
  color: #a0aec0;
  text-align: center;
  padding: 2rem;
}
</style>