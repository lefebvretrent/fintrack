<script setup>
import { RouterLink, RouterView } from 'vue-router'
import { useFintrackStore } from './stores/fintrack'
import { onMounted } from 'vue'

const store = useFintrackStore()

onMounted(() => {
  store.fetchAll()
})
</script>

<template>
  <div class="app">
    <nav class="navbar">
      <div class="nav-brand">
        <span class="brand-icon">💰</span>
        <span class="brand-name">FinTrack</span>
      </div>
      <div class="nav-links">
        <RouterLink to="/">Dashboard</RouterLink>
        <RouterLink to="/transactions">Transactions</RouterLink>
        <RouterLink to="/budgets">Budgets</RouterLink>
        <RouterLink to="/categories">Categories</RouterLink>
      </div>
    </nav>

    <main class="main-content">
      <RouterView />
    </main>
  </div>
</template>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  background: #f0f2f5;
  color: #1a1a2e;
}

.app {
  min-height: 100vh;
}

.navbar {
  background: #1a1a2e;
  padding: 0 2rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  height: 64px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

.nav-brand {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.brand-icon {
  font-size: 1.5rem;
}

.brand-name {
  color: #fff;
  font-size: 1.25rem;
  font-weight: 700;
  letter-spacing: 0.5px;
}

.nav-links {
  display: flex;
  gap: 0.5rem;
}

.nav-links a {
  color: #a0aec0;
  text-decoration: none;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  font-size: 0.95rem;
  transition: all 0.2s;
}

.nav-links a:hover {
  color: #fff;
  background: rgba(255,255,255,0.1);
}

.nav-links a.router-link-active {
  color: #fff;
  background: rgba(255,255,255,0.15);
}

.main-content {
  max-width: 1700px;
  margin: 0 auto;
  padding: 2rem;
}

.card {
  background: #fff;
  border-radius: 12px;
  padding: 1.5rem;
  box-shadow: 0 1px 4px rgba(0,0,0,0.08);
  margin-bottom: 1.5rem;
}

.card h2 {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 1rem;
  color: #1a1a2e;
}

.btn {
  padding: 0.5rem 1.25rem;
  border-radius: 6px;
  border: none;
  cursor: pointer;
  font-size: 0.9rem;
  font-weight: 500;
  transition: all 0.2s;
}

.btn-primary {
  background: #1a1a2e;
  color: #fff;
}

.btn-primary:hover {
  background: #2d2d4e;
}

.btn-danger {
  background: #fff0f0;
  color: #e53e3e;
}

.btn-danger:hover {
  background: #ffe0e0;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  font-size: 0.85rem;
  font-weight: 500;
  margin-bottom: 0.35rem;
  color: #4a5568;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 0.6rem 0.75rem;
  border: 1px solid #e2e8f0;
  border-radius: 6px;
  font-size: 0.95rem;
  transition: border 0.2s;
}

.form-group input:focus,
.form-group select:focus {
  outline: none;
  border-color: #1a1a2e;
}
</style>