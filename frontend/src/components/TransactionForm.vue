<script setup>
import { ref, onMounted } from 'vue'
import { useFintrackStore } from '../stores/fintrack'

const store = useFintrackStore()

const form = ref({
  amount: '',
  description: '',
  date: new Date().toISOString().slice(0, 10),
  type: 'expense',
  category_id: '',
})

async function submit() {
  if (!form.value.amount || !form.value.description || !form.value.category_id) return

  await store.addTransaction({
    amount: parseFloat(form.value.amount),
    description: form.value.description,
    date: form.value.date,
    type: form.value.type,
    category_id: parseInt(form.value.category_id),
  })

  // reset form
  form.value.amount = ''
  form.value.description = ''
  form.value.date = new Date().toISOString().slice(0, 10)
}
</script>

<template>
  <div>
    <div class="form-group">
      <label>Type</label>
      <div class="type-toggle">
        <button
          class="type-btn"
          :class="{ active: form.type === 'expense' }"
          @click="form.type = 'expense'"
        >
          Expense
        </button>
        <button
          class="type-btn"
          :class="{ active: form.type === 'income' }"
          @click="form.type = 'income'"
        >
          Income
        </button>
      </div>
    </div>

    <div class="form-group">
      <label>Amount</label>
      <input v-model="form.amount" type="number" step="0.01" placeholder="0.00" />
    </div>

    <div class="form-group">
      <label>Description</label>
      <input v-model="form.description" type="text" placeholder="What was this for?" />
    </div>

    <div class="form-group">
      <label>Date</label>
      <input v-model="form.date" type="date" />
    </div>

    <div class="form-group">
      <label>Category</label>
      <select v-model="form.category_id">
        <option value="">Select a category</option>
        <option
          v-for="cat in store.categories"
          :key="cat.id"
          :value="cat.id"
        >
          {{ cat.name }}
        </option>
      </select>
    </div>

    <button
      class="btn btn-primary"
      style="width: 100%"
      :disabled="store.loading"
      @click="submit"
    >
      {{ store.loading ? 'Saving...' : 'Add Transaction' }}
    </button>

    <p v-if="store.error" class="error">{{ store.error }}</p>
  </div>
</template>

<style scoped>
.type-toggle {
  display: flex;
  gap: 0.5rem;
}

.type-btn {
  flex: 1;
  padding: 0.5rem;
  border: 2px solid #e2e8f0;
  border-radius: 6px;
  background: #fff;
  cursor: pointer;
  font-weight: 500;
  transition: all 0.2s;
}

.type-btn.active {
  border-color: #1a1a2e;
  background: #1a1a2e;
  color: #fff;
}

.error {
  color: #e53e3e;
  font-size: 0.85rem;
  margin-top: 0.5rem;
}
</style>