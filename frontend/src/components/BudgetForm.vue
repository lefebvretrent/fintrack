<script setup>
import { ref } from 'vue'
import { useFintrackStore } from '../stores/fintrack'

const store = useFintrackStore()

const form = ref({
  category_id: '',
  amount_limit: '',
  month: store.currentMonth,
})

async function submit() {
  if (!form.value.category_id || !form.value.amount_limit) return

  await store.saveBudget({
    category_id: parseInt(form.value.category_id),
    amount_limit: parseFloat(form.value.amount_limit),
    month: form.value.month,
  })

  form.value.amount_limit = ''
  form.value.category_id = ''
}
</script>

<template>
  <div>
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

    <div class="form-group">
      <label>Monthly Budget Limit</label>
      <input
        v-model="form.amount_limit"
        type="number"
        step="0.01"
        placeholder="0.00"
      />
    </div>

    <div class="form-group">
      <label>Month</label>
      <input v-model="form.month" type="month" />
    </div>

    <button
      class="btn btn-primary"
      style="width: 100%"
      @click="submit"
    >
      Save Budget
    </button>
  </div>
</template>