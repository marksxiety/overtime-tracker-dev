<template>
    <div class="flex flex-col gap-6 h-full">
        <div class="breadcrumbs text-sm">
            <ul>
                <li>
                    <Link :href="route('main')">Home</Link>
                </li>
                <li>
                    <Link :href="route('schedule.manage')">Manage Schedule</Link>
                </li>
            </ul>
        </div>
        <!-- Page Heading -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Employee Schedule</h1>
            <div class="flex space-x-4 w-2/4">
                <div class="flex-1">
                    <SelectOption :options="years" v-model="selectedYear" />
                </div>
                <div class="flex-1">
                    <SelectOption :options="weeks" v-model="selectedWeek" />
                </div>
                <div>
                    <button class="btn btn-primary flex items-center gap-2 px-4">
                        <Icon icon="material-symbols:add-rounded" width="24" height="24" /> Add Week
                    </button>
                </div>
                <div>
                    <button class="btn btn-primary flex items-center gap-2 px-4">
                        <Icon icon="mingcute:schedule-line" width="24" height="24" /> Submit Schedule
                    </button>
                </div>
            </div>

        </div>
        <div class="overflow-x-auto bg-base-100 p-6 rounded-lg shadow-sm">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold tracking-tight leading-tight text-base-content">
                    Schedule for <span class="font-bold">Week 33</span>
                </h2>
                <p class="mt-2 sm:mt-0 text-base-content opacity-70 font-medium">
                    Aug 10, 2025 — Aug 16, 2025
                </p>
            </div>

            <table class="table w-full min-h-[24rem]">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Sunday</th>
                        <th>Monday</th>
                        <th>Tuesday</th>
                        <th>Wednesday</th>
                        <th>Thursday</th>
                        <th>Friday</th>
                        <th>Saturday</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Table rows -->
                </tbody>
            </table>
        </div>
    </div>
</template>
<script setup>
import { onMounted, ref, inject } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { Icon } from "@iconify/vue"

import { years, weeks, currentWeek } from '../utils/dropdownOptions.js'
import { fetchShiftList } from '../api/shift.js'
import { fetchEmployeeSchedule, submitEmployeeSchedule } from '../api/schedule.js'

import SelectOption from '../Components/SelectOption.vue'

const toast = inject('toast')
const isSubmitting = ref(false)

const props = defineProps({
    info: Object,
    payload: Object,
    errors: Object,
    flash: Object,
    success: Boolean,
    message: String,
    auth: Object,
})

// Default selected from props or get manually for year and week
const selectedYear = ref(new Date().getFullYear())
const selectedWeek = ref(currentWeek())

onMounted(async () => {
    const scheduleResponse = await fetchEmployeeSchedule(selectedYear.value, 32)
    console.log(scheduleResponse.data)
})
</script>