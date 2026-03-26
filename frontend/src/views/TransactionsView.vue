<script setup>
import { useFintrackStore } from '../stores/fintrack'
import TransactionForm from '../components/TransactionForm.vue'
import TransactionList from '../components/TransactionList.vue'
const store = useFintrackStore()
</script>

<template>
  <div>
    <div class="page-header">
      <h1>Transactions</h1>
    </div>

    <div class="two-col">
      <div>
        <div class="card">
          <h2>Add Transaction</h2>
          <TransactionForm />
        </div>
      </div>
      <div>
        <div class="card">
          <h2>Recent Transactions</h2>
          <TransactionList />
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.page-header {
  margin-bottom: 1.5rem;
}

.page-header h1 {
  font-size: 1.75rem;
  font-weight: 700;
}

.two-col {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 1rem;
  align-items: start;
}
</style>