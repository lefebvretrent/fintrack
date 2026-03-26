<script setup>
import { ref } from 'vue'
import { useFintrackStore } from '../stores/fintrack'

const store = useFintrackStore()

const form = ref({
  name: '',
  color: '#1a1a2e',
})

async function submit() {
  if (!form.value.name) return
  await store.addCategory({ ...form.value })
  form.value.name = ''
  form.value.color = '#1a1a2e'
}
</script>

<template>
  <div class="two-col">
    <div>
      <div class="card">
        <h2>Add Category</h2>

        <div class="form-group">
          <label>Name</label>
          <input v-model="form.name" type="text" placeholder="e.g. Groceries" />
        </div>

        <div class="form-group">
          <label>Color</label>
          <div class="color-row">
            <input v-model="form.color" type="color" class="color-picker" />
            <span class="color-value">{{ form.color }}</span>
          </div>
        </div>

        <button class="btn btn-primary" style="width:100%" @click="submit">
          Add Category
        </button>
      </div>
    </div>

    <div>
      <div class="card">
        <h2>Your Categories</h2>
        <div v-if="store.categories.length === 0" class="empty">
          No categories yet. Add one!
        </div>
        <div
          v-for="cat in store.categories"
          :key="cat.id"
          class="category-item"
        >
          <div class="category-left">
            <span
              class="category-swatch"
              :style="{ background: cat.color }"
            ></span>
            <span class="category-name">{{ cat.name }}</span>
          </div>
          <button class="btn btn-danger btn-sm" @click="store.removeCategory(cat.id)">
            ✕
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.two-col {
  display: grid;
  grid-template-columns: 1fr 2fr;
  gap: 1rem;
  align-items: start;
}

.color-row {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.color-picker {
  width: 48px;
  height: 38px;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  cursor: pointer;
  padding: 2px;
}

.color-value {
  font-size: 0.9rem;
  color: #718096;
  font-family: monospace;
}

.category-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0.75rem 0;
  border-bottom: 1px solid #f0f2f5;
}

.category-item:last-child {
  border-bottom: none;
}

.category-left {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.category-swatch {
  width: 24px;
  height: 24px;
  border-radius: 6px;
  flex-shrink: 0;
}

.category-name {
  font-weight: 500;
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