<script setup>
import { computed, ref } from 'vue'
import { Doughnut, Bar } from 'vue-chartjs'
import {
  Chart as ChartJS,
  ArcElement,
  Tooltip,
  Legend,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
} from 'chart.js'
import { useFintrackStore } from '../stores/fintrack'

ChartJS.register(ArcElement, Tooltip, Legend, CategoryScale, LinearScale, BarElement, Title)

const store = useFintrackStore()

const doughnutData = computed(() => {
  const categories = store.summary?.categories ?? []
  const withSpending = categories.filter(c => c.spent > 0)

  return {
    labels: withSpending.map(c => c.category.name),
    datasets: [{
      data: withSpending.map(c => c.spent),
      backgroundColor: withSpending.map(c => c.category.color),
      borderWidth: 2,
      borderColor: '#fff',
    }],
  }
})

const barData = computed(() => {
  const categories = store.summary?.categories ?? []

  return {
    labels: categories.map(c => c.category.name),
    datasets: [
      {
        label: 'Spent',
        data: categories.map(c => c.spent),
        backgroundColor: categories.map(c => c.category.color),
        borderRadius: 6,
      },
      {
        label: 'Budget',
        data: categories.map(c => c.budget_limit),
        backgroundColor: 'rgba(0,0,0,0.08)',
        borderRadius: 6,
      },
    ],
  }
})

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom',
    },
  },
}

const barOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom',
    },
    title: {
      display: true,
      text: 'Spent vs Budget by Category',
    },
  },
  scales: {
    y: {
      beginAtZero: true,
      ticks: {
        callback: (value) => '$' + value,
      },
    },
  },
}
</script>

<template>
  <div>
    <!-- Category breakdown table -->
    <div class="card">
      <h2>Category Breakdown</h2>
      <div v-if="store.summary?.categories?.length > 0">
        <table class="breakdown-table">
          <thead>
            <tr>
              <th>Category</th>
              <th>Spent</th>
              <th>Budget</th>
              <th>Remaining</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in store.summary.categories" :key="item.category.id">
              <td>
                <span class="category-dot" :style="{ background: item.category.color }"></span>
                {{ item.category.name }}
              </td>
              <td>${{ item.spent.toFixed(2) }}</td>
              <td>{{ item.budget_limit > 0 ? '$' + item.budget_limit.toFixed(2) : '—' }}</td>
              <td>{{ item.budget_limit > 0 ? '$' + item.remaining.toFixed(2) : '—' }}</td>
              <td>
                <span class="badge" :class="item.over_budget ? 'badge-danger' : 'badge-ok'">
                  {{ item.over_budget ? 'Over budget' : 'OK' }}
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <p v-else class="empty">No data for this month yet. Add some transactions!</p>
    </div>

    <!-- Charts -->
    <div class="charts-grid">
      <div class="card">
        <h2>Spending by Category</h2>
        <div class="chart-wrapper">
          <Doughnut
            v-if="doughnutData.labels.length > 0"
            :data="doughnutData"
            :options="chartOptions"
          />
          <p v-else class="empty">No expenses recorded yet</p>
        </div>
      </div>

      <div class="card">
        <h2>Spent vs Budget</h2>
        <div class="chart-wrapper">
          <Bar
            v-if="store.summary?.categories?.length > 0"
            :data="barData"
            :options="barOptions"
          />
          <p v-else class="empty">No data yet</p>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.charts-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.chart-wrapper {
  height: 280px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.breakdown-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.9rem;
}

.breakdown-table th {
  text-align: left;
  padding: 0.5rem 0.75rem;
  border-bottom: 2px solid #e2e8f0;
  color: #718096;
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.breakdown-table td {
  padding: 0.75rem;
  border-bottom: 1px solid #f0f2f5;
}

.category-dot {
  display: inline-block;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  margin-right: 0.5rem;
}

.badge {
  padding: 0.2rem 0.6rem;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 500;
}

.badge-ok {
  background: #f0fff4;
  color: #276749;
}

.badge-danger {
  background: #fff5f5;
  color: #c53030;
}

.empty {
  color: #a0aec0;
  text-align: center;
  padding: 2rem;
}
</style>