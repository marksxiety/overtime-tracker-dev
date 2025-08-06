<template>
  <div class="flex flex-col gap-6 p-4 h-full">
    <!-- Breadcrumbs -->
    <div class="breadcrumbs text-sm">
      <ul>
        <li>
          <Link :href="route('main')">Home</Link>
        </li>
        <li>
          <Link :href="route('main')">Pending Page</Link>
        </li>
        <li>
          <Link :href="route('overtime.filing')">Filing Page</Link>
        </li>
      </ul>
    </div>

    <!-- Page Title -->
    <div class="flex justify-between items-center">
      <h1 class="text-3xl font-extrabold text-base-content">For Filing Overtime Requests</h1>
    </div>

    <div class="stats stats-horizontal shadow flex-wrap">
      <Card title="Requests to File" :value="'0'" description="Approved but not yet filed" />
      <Card title="Total Overtime Hours" :value="'0'" description="Awaiting confirmation" />
    </div>

    <!-- Filing Table -->
    <div class="card bg-base-100 shadow">
      <div class="card-body">
        <div class="flex justify-between mb-4">
          <h2 class="card-title">Approved Requests Awaiting Filing</h2>
          <div class="flex flex-row flex-end gap-4 w-1/4">
            <SelectOption :options="weeks" v-model="selectedWeek" margin='' @change="handleWeekSelection()" />
            <SelectOption :options="years" v-model="selectedYear" margin='' @change="handleWeekSelection()" />
          </div>
        </div>

        <div class="overflow-x-auto min-h-[10vh] max-h-[50vh]">
          <table class="table table-zebra w-full">
            <thead class="sticky top-0 bg-base-300 z-10 rounded">
              <tr>
                <th>Employee</th>
                <th>Date</th>
                <th>Week</th>
                <th>Hours</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-if="requests.length === 0">
                <td colspan="5" class="text-center h-48 italic text-gray-400 py-4">
                  No Awaiting Filing(s)
                </td>
              </tr>
              <tr v-for="request in requests" :key="request.id">
                <td>{{ request.user.name }}</td>
                <td>{{ request.schedule.date }}</td>
                <td>{{ request.schedule.week }}</td>
                <td>{{ request.overtime.hours }}</td>
                <td class="flex gap-2 justify-center">
                  <button class="btn btn-xs text-sm btn-primary"
                    @click="openManageRequestModal(request)">Manage</button>
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
import { onMounted, ref, inject, watch } from 'vue'
import Card from '../Components/Card.vue'
import SelectOption from '../Components/SelectOption.vue'
import TextInput from '../Components/TextInput.vue'
import TextArea from '../Components/TextArea.vue'
import Stepper from '../Components/Stepper.vue'
import { weeks, years, currentWeek } from '../utils/dropdownOptions.js'
import Modal from '../Components/Modal.vue'
import { identifyColorStatus } from '../utils/colorIdentifier.js'
import { useForm, router, Link } from '@inertiajs/vue3'

const props = defineProps({
  info: Object,
  success: Boolean,
  message: String
})

const selectedWeek = ref(props?.info?.payload?.week)
const selectedYear = ref(props?.info?.payload?.year)
const requests = ref([...props?.info?.requests ?? []])

const user = ref({
  name: '',
  employee_id: '',
  role: '',
  email: ''
})

const schedule = ref({
  date: '',
  shift_code: '',
  shift_start: '',
  shift_end: '',
  week: '',
})

const overtime = ref({
  start_time: '',
  end_time: '',
  hours: '',
  status: '',
  created_at: '',
  reason: '',
  remarks: '',
})

// ===== Watchers =====

watch(() => props?.info?.requests, (updatedRequest) => {
  requests.value = [...updatedRequest]
})

watch(() => props.info.payload.week, (newWeek) => {
  selectedWeek.value = newWeek
})

watch(() => props.info.payload.year, (newYear) => {
  selectedYear.value = newYear
})

const handleWeekSelection = () => {
  router.get(route('overtime.filing'), {
    year: selectedYear.value,
    week: selectedWeek.value,
    status: 'APPROVED',
    page: 'Approver/Filing'
  }, {
    preserveState: true
  })
}
</script>
