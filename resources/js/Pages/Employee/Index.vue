<template>
    <div class="flex flex-col gap-4">
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 flex flex-row border-2 justify-between rounded p-4 bg-base-200">
                <span class="text-md font-semibold">Total Overtime Hours</span>
                <span class="text-2xl font-extrabold">45</span>
            </div>
            <div class="col-span-1 flex flex-row border-2 justify-between rounded p-4 bg-base-200">
                <span class="text-md font-semibold">Pending Requests</span>
                <span class="text-2xl font-extrabold">2</span>
            </div>
        </div>
        <div class="flex mt-4 justify-end gap-2">
            <Link :href="route('request')" class="btn btn-neutral">
            File New Request
            </Link>
            <Link :href="route('schedule')" class="btn btn-neutral">
            Manage Schedules
            </Link>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar: Overtime Requests -->
            <div class="col-span-1 border rounded-md p-4 bg-white shadow">
                <h2 class="text-lg font-bold mb-4">My Requests</h2>
                <!-- Sample List -->
                <ul class="space-y-2 text-sm">
                    <li v-for="(request, i) in recentRequests" :key="i" class="border p-2 rounded">
                        <p class="font-semibold">{{ request.date }}</p>
                        <p class="text-xs text-gray-500">Status: {{ request.status }}</p>
                        <p class="text-xs">Duration: {{ request.hours }} hrs</p>
                    </li>
                </ul>
            </div>

            <!-- Calendar Section -->
            <div class="col-span-3 p-4 border rounded-md bg-white shadow-md">
                <header class="flex items-center justify-between mb-4">
                    <button class="btn btn-sm btn-neutral" @click="handlePreviousMonth()">&lt;</button>
                    <p class="current-date font-bold text-xl">{{ currentDate }}</p>
                    <button class="btn btn-sm btn-neutral" @click="handleNextMonth()">&gt;</button>
                </header>

                <!-- Days of week -->
                <ul class="grid grid-cols-7 gap-4 text-center font-semibold text-md text-gray-600">
                    <li>Sun</li>
                    <li>Mon</li>
                    <li>Tue</li>
                    <li>Wed</li>
                    <li>Thu</li>
                    <li>Fri</li>
                    <li>Sat</li>
                </ul>

                <!-- Calendar days -->
                <ul class="grid grid-cols-7 text-center mt-2 text-lg font-semibold">
                    <li v-for="(days, index) in calendardays" :key="index"
                        class="p-3 border cursor-pointer hover:bg-slate-200 rounded-md transition duration-200">
                        <span :class="[['next', 'prev'].includes(days.type) ? 'text-gray-400' : '', '']">
                            {{ days.day }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'

const date = new Date()
const currentDate = ref('')
const currentYear = ref(date.getFullYear())
const currentMonth = ref(date.getMonth())
const lastDateOfMonth = ref(0)
const firstDayOfMonth = ref(0)
const lastDateOfLastMonth = ref(0)

const calendardays = ref([])

const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']

const handlePreviousMonth = () => {
    if (currentMonth.value === 0) {
        currentMonth.value = 11
        currentYear.value -= 1
    } else {
        currentMonth.value -= 1
    }

    updateCurrentDate(currentYear.value, currentMonth.value)
}

const handleNextMonth = () => {
    if (currentMonth.value === 11) {
        currentMonth.value = 0
        currentYear.value += 1
    } else {
        currentMonth.value += 1
    }

    updateCurrentDate(currentYear.value, currentMonth.value)
}


function updateCurrentDate(year, month) {
    currentDate.value = `${months[month]} ${year}`
    lastDateOfMonth.value = new Date(year, month + 1, 0).getDate()
    lastDateOfLastMonth.value = new Date(year, month, 0).getDate()
    firstDayOfMonth.value = new Date(year, month, 1).getDay()

    calendardays.value = [] // reset

    // previous month's trailing days (gray out)
    for (let i = firstDayOfMonth.value; i > 0; i--) {
        calendardays.value.push({
            day: lastDateOfLastMonth.value - i + 1,
            type: 'prev'
        })
    }

    // current month days
    for (let i = 1; i <= lastDateOfMonth.value; i++) {
        calendardays.value.push({
            day: i,
            type: 'current'
        })
    }

    // next month's leading days (to fill the rest of the grid)
    // use 42 to have 6 rows only (6x7)
    const remaining = 42 - calendardays.value.length
    for (let i = 1; i <= remaining; i++) {
        calendardays.value.push({
            day: i,
            type: 'next'
        })
    }
}


const recentRequests = ref([
    { date: '2025-07-25', status: 'Pending', hours: 3 },
    { date: '2025-07-22', status: 'Approved', hours: 2.5 },
    { date: '2025-07-20', status: 'Rejected', hours: 1 },
])
onMounted(() => {
    updateCurrentDate(currentYear.value, currentMonth.value)
})
</script>