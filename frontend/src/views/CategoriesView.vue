<script setup>
import { useFintrackStore } from '../stores/fintrack'
import CategoryManager from '../components/CategoryManager.vue'

const store = useFintrackStore()
</script>

<template>
  <div>
    <div class="page-header">
      <h1>Categories</h1>
    </div>
    <CategoryManager />
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
</style>