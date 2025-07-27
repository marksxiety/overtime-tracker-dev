<template>
    <div class="flex flex-col">
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

        <div class="h-screen mt-4">
            <div class="flex flex-col gap-4 border rounded-md p-4">
                <header class="flex items-center justify-between">
                    <button class="btn btn-sm btn-neutral">&lt;</button>
                    <p class="current-date font-semibold text-md">{{ currentDate }}</p>
                    <button class="btn btn-sm btn-neutral">&gt;</button>
                </header>

                <hr>
                <div class="calendar">
                    <ul class="grid grid-cols-7 gap-6 text-center font-semibold text-md">
                        <li>Sun</li>
                        <li>Mon</li>
                        <li>Tue</li>
                        <li>Wed</li>
                        <li>Thu</li>
                        <li>Fri</li>
                        <li>Sat</li>
                    </ul>

                    <ul class="grid grid-cols-7 gap-4 text-center mt-2 text-md">
                        <li v-for="day in lastDateOfMonth" :key="day">
                            {{ day }}
                        </li>
                    </ul>
                </div>
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

const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']

onMounted(() => {
    currentDate.value = `${months[currentMonth.value]} ${currentYear.value}`
    lastDateOfMonth.value = new Date(currentYear.value, currentMonth.value + 1, 0).getDate()
})
</script>