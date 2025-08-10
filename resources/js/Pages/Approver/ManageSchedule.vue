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
        <div v-for="(sched, index) in employeeSchedules" :key="index"
            class="overflow-x-auto bg-base-100 p-6 rounded-lg shadow-sm">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-xl font-semibold tracking-tight leading-tight text-base-content">
                    Schedule for <span class="font-bold">Week {{ sched.week }}</span>
                </h2>
                <p class="mt-2 sm:mt-0 text-base-content opacity-70 font-medium">
                    {{ sched.week_start }} — {{ sched.week_end }}
                </p>
            </div>

            <table class="table w-full h-auto">
                <thead>
                    <tr class="text-center">
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
                    <tr v-for="sch in sched.week_schedule">
                        <td class="text-center">{{ sch.name }}</td>
                        <td v-for="day in sch.schedule" :key="day.date">
                        <td class="w-full flex justify-center items-center">
                            <span class="loading loading-spinner" v-if="isLoading"></span>
                            <span v-else class="w-full">
                                <SelectOption :options="shifts" v-model="day.shift_id" margin="" />
                            </span>
                        </td>
                        </td>
                    </tr>
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
const isLoading = ref(false)

// Default selected from props or get manually for year and week
const selectedYear = ref(new Date().getFullYear())
const selectedWeek = ref(currentWeek())

const employeeSchedules = ref([])
const shifts = ref([])

onMounted(async () => {
    isLoading.value = true
    const scheduleResponse = await fetchEmployeeSchedule(selectedYear.value, selectedWeek.value)

    if (scheduleResponse.data.success) {
        employeeSchedules.value = [{
            week_schedule: scheduleResponse.data.info?.schedules,
            week: scheduleResponse.data.info?.week,
            week_start: scheduleResponse.data.info?.week_start,
            week_end: scheduleResponse.data.info?.week_end,
        }]

        const shiftsResponse = await fetchShiftList()

        // Format the shift data into { label, value } structure
        // so it can be used directly in <SelectOption>
        const shiftData = shiftsResponse?.data?.data ?? []
        console.log(shiftData)
        shifts.value = shiftData.map(element => ({
            label: (element.start_time && element.end_time)
                ? element.code
                : `${element.code === 'SY' ? 'NWS' : 'RD'}`,
            value: element.id
        }))

    } else {
        toast("Loading Employee(s) schedule failed. Please try again", 'error')
    }

    isLoading.value = false
})
</script>