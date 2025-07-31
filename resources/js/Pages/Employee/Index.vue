<template>
    <Modal ref="modalRef">
        <h2 class="text-lg font-bold mb-4">File Overtime Request</h2>
        <div class="flex flex-col gap-4">
            <form @submit.prevent="submitOvertime()" class="flex flex-col gap-1 min-h-96">
                <div class="flex items-center justify-center h-64 gap-4 text-center" v-if="fetchingSchedule">
                    <span class="loading loading-spinner"></span> Loading Schedule...
                </div>
                <div v-else>
                    <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
                        <legend class="fieldset-legend">Requested Overtime Date</legend>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1">
                                <TextInput name="Date:" type="text" v-model="form.date" :readonly="true"
                                    :placeholder="''" />
                            </div>
                            <div class="col-span-1">
                                <TextInput name="Week:" type="text" v-model="form.week" :readonly="true"
                                    :placeholder="''" />
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
                        <legend class="fieldset-legend">Your Scheduled Shift</legend>
                        <div class="grid grid-cols-5 gap-4">
                            <div class="col-span-1">
                                <TextInput name="Shift Code:" type="text" v-model="form.shift_code" :readonly="true"
                                    :placeholder="''" />
                            </div>
                            <div class="col-span-2">
                                <TextInput name="Start:" type="text" v-model="form.shift_start_time" :readonly="true"
                                    :placeholder="''" />
                            </div>
                            <div class="col-span-2">
                                <TextInput name="End:" type="text" v-model="form.shift_end_time" :readonly="true"
                                    :placeholder="''" />
                            </div>
                        </div>
                    </fieldset>


                    <fieldset class="fieldset bg-base-200 border-base-300 rounded-box w-xs border p-4">
                        <legend class="fieldset-legend">Overtime Duration and Reason</legend>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1">
                                <TextInput name="Start Time:" type="time" v-model="form.start_time" />
                            </div>
                            <div class="col-span-1">
                                <TextInput name="End Time:" type="time" v-model="form.end_time" />
                            </div>
                        </div>
                        <TextArea name="Reason:" type="text" v-model="form.reason" />
                    </fieldset>
                    <div v-if="withShedule">
                        <div class="flex justify-end gap-4">
                            <button type="button" class="btn btn-neutral mt-4" :disabled="form.processing"
                                @click="closeModal()">Cancel</button>
                            <button type="submit" class="btn btn-primary mt-4" :disabled="form.processing">
                                <span v-if="form.processing" class="loading loading-spinner"></span>
                                <span>Submit</span>
                            </button>
                        </div>
                    </div>
                    <div v-else
                        class="flex flex-col items-center justify-center mt-4 text-error font-semibold text-center">
                        ⚠️ No registered Schedule.
                        <div class="flex justify-center gap-6">
                            <button type="button" class="btn btn-secondary mt-4 w-full"
                                @click="closeModal()">Close</button>
                            <Link :href="route('schedule')" type="button" class="btn btn-primary mt-4 w-full">Add
                            Schedule</Link>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </Modal>
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
            <Link :href="route('schedule')" class="btn btn-neutral">
            Manage Schedules
            </Link>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar: Overtime Requests -->
            <div class="col-span-1 border rounded-md p-4 shadow">
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
            <div class="col-span-3 p-4 border rounded-md shadow-md">
                <header class="flex items-center justify-between mb-4">
                    <button class="btn btn-sm btn-neutral" @click="handlePreviousMonth()">&lt;</button>
                    <p class="current-date font-bold text-xl">{{ currentMonthYear }}</p>
                    <button class="btn btn-sm btn-neutral" @click="handleNextMonth()">&gt;</button>
                </header>

                <!-- Days of week -->
                <ul class="grid grid-cols-7 gap-4 text-center font-semibold text-md">
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
                    <li v-for="(days, index) in calendardays" :key="index" :class="[
                        ['next', 'prev'].includes(days.type) ? 'pointer-events-none' : '',
                        'p-3 border  flex justify-center items-center'
                    ]" @click="showModal(currentYear, currentMonth, days.day)">
                        <span :class="[
                            ['next', 'prev'].includes(days.type) ? 'text-gray-400 pointer-events-none' : '',
                            'hover:bg-slate-200  cursor-pointer transition duration-200 w-10 h-10 flex items-center justify-center rounded-full'
                        ]">
                            {{ days.day }}
                        </span>
                    </li>
                </ul>

            </div>
        </div>

    </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import { onMounted, ref, inject } from 'vue'
import Modal from '../Components/Modal.vue'
import TextInput from '../Components/TextInput.vue'
import TextArea from '../Components/TextArea.vue'
import { fetchUserSchedule } from '../api/schedule.js'

const date = new Date()
const currentMonthYear = ref('')
const currentDate = ref('')
const currentYear = ref(date.getFullYear())
const currentMonth = ref(date.getMonth())
const lastDateOfMonth = ref(0)
const firstDayOfMonth = ref(0)
const lastDateOfLastMonth = ref(0)
const modalRef = ref(null)
const calendardays = ref([])
const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
const toast = inject('toast')
const fetchingSchedule = ref(false)
const withShedule = ref(true)

const closeModal = () => {
    modalRef.value?.close()
}

const handlePreviousMonth = () => {
    if (currentMonth.value === 0) {
        currentMonth.value = 11
        currentYear.value -= 1
    } else {
        currentMonth.value -= 1
    }

    updateCurrentMonthYear(currentYear.value, currentMonth.value)
}

const handleNextMonth = () => {
    if (currentMonth.value === 11) {
        currentMonth.value = 0
        currentYear.value += 1
    } else {
        currentMonth.value += 1
    }

    updateCurrentMonthYear(currentYear.value, currentMonth.value)
}


function updateCurrentMonthYear(year, month) {
    currentMonthYear.value = `${months[month]} ${year}`
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

const form = useForm({
    date: '',
    week: '',
    employee_schedule_id: '',
    shift_code: '',
    shift_start_time: '',
    shift_end_time: '',
    start_time: '',
    end_time: '',
    reason: ''
})


const submitOvertime = () => {
    form.post(route('overtime.request'), {
        onSuccess: () => {
            toast('Overtime Request Filing successful!', 'success')
            form.reset()
            closeModal()
        },
        onError: () => {
            toast('Overtime Request failed. Please try again', 'error')
        }
    })
}

const showModal = async (year, month, day) => {
    modalRef.value?.open()
    fetchingSchedule.value = true
    form.reset()
    let scheduleResponse = await fetchUserSchedule(year, month + 1, day)
    if (scheduleResponse.data?.success) {
        let scheduledata = scheduleResponse.data?.schedule

        if (Object.keys(scheduledata).length > 0) {
            withShedule.value = true
            form.date = scheduledata.date
            form.week = scheduledata.week
            form.shift_code = scheduledata.shift_code
            form.employee_schedule_id = scheduledata.id
            form.shift_start_time = scheduledata.shift_start_time
            form.shift_end_time = scheduledata.shift_end_time
        } else {
            withShedule.value = false
        }

        fetchingSchedule.value = false
    } else {
        toast('Failed to load schedule. Please try again', 'error')
        fetchingSchedule.value = false
    }
}

const recentRequests = ref([
    { date: '2025-07-25', status: 'Pending', hours: 3 },
    { date: '2025-07-22', status: 'Approved', hours: 2.5 },
    { date: '2025-07-20', status: 'Rejected', hours: 1 },
])
onMounted(async () => {
    updateCurrentMonthYear(currentYear.value, currentMonth.value)
})
</script>