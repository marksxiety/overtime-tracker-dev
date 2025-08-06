<template>
  <div class="flex flex-col gap-6 p-4 h-full">
    <!-- Breadcrumbs -->
    <div class="breadcrumbs text-sm">
      <ul>
        <li><Link :href="route('main')">Home</Link></li>
        <li><Link :href="route('overtime.filing')">Filing Page</Link></li>
      </ul>
    </div>

    <!-- Page Title -->
    <div class="text-2xl font-semibold text-primary">For Filing Overtime Requests</div>

    <!-- Stat Summary -->
    <div class="stats stats-horizontal shadow flex-wrap">
      <div class="stat">
        <div class="stat-title">Requests to File</div>
        <div class="stat-value text-primary">{{ forFiling.length }}</div>
        <div class="stat-desc">Approved but not yet filed</div>
      </div>

      <div class="stat">
        <div class="stat-title">Total Overtime Hours</div>
        <div class="stat-value text-accent">
          {{ totalHours }}
        </div>
        <div class="stat-desc">Awaiting confirmation</div>
      </div>

      <div class="stat">
        <div class="stat-title">Current Week</div>
        <div class="stat-value">{{ selectedWeek }}</div>
        <div class="stat-desc">{{ selectedYear }}</div>
      </div>
    </div>

    <!-- Filing Table -->
    <div class="card bg-base-100 shadow">
      <div class="card-body">
        <div class="flex justify-between mb-4">
          <h2 class="card-title">Approved Requests Awaiting Filing</h2>

          <div class="flex flex-row gap-4 w-1/3">
            <SelectOption :options="weeks" v-model="selectedWeek" @change="fetchForFiling()" />
            <SelectOption :options="years" v-model="selectedYear" @change="fetchForFiling()" />
          </div>
        </div>

        <div class="overflow-x-auto min-h-[40vh] max-h-[60vh]">
          <table class="table table-zebra w-full">
            <thead class="sticky top-0 bg-base-300 z-10">
              <tr>
                <th>Employee</th>
                <th>Date</th>
                <th>Week</th>
                <th>Hours</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="forFiling.length === 0">
                <td colspan="5" class="text-center h-48 italic text-gray-400">No requests to file.</td>
              </tr>
              <tr v-for="request in forFiling" :key="request.id">
                <td>{{ request.user.name }}</td>
                <td>{{ request.schedule.date }}</td>
                <td>{{ request.schedule.week }}</td>
                <td>{{ request.overtime.hours }}</td>
                <td class="flex gap-2 justify-center">
                  <button class="btn btn-xs btn-primary" @click="fileOvertime(request)">File</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'

// Mock week/year options
const selectedWeek = ref(32)
const selectedYear = ref(2025)

const weeks = Array.from({ length: 52 }, (_, i) => i + 1)
const years = [2024, 2025]

// Mock data (replace with props or Inertia response)
const forFiling = ref([
  {
    id: 1,
    user: { name: 'John Doe' },
    schedule: { date: '2025-08-05', week: 32 },
    overtime: { hours: 3 }
  },
  {
    id: 2,
    user: { name: 'Jane Smith' },
    schedule: { date: '2025-08-06', week: 32 },
    overtime: { hours: 2.5 }
  }
])

const totalHours = computed(() =>
  forFiling.value.reduce((sum, r) => sum + parseFloat(r.overtime.hours), 0).toFixed(2)
)

function fetchForFiling() {
  // Replace with actual API or Inertia request
  console.log('Fetch filtered by week:', selectedWeek.value, 'year:', selectedYear.value)
}

function fileOvertime(request) {
  // Replace with actual logic or modal trigger
  console.log('Filing request:', request)
}
</script>
