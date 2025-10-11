<template>
    <Modal ref="overtimeFilingModal">
        <div class="py-4 mt-2">
            <div class="flex flex-col gap-2 w-full">
                <h2 class="text-lg font-bold mb-4">File Overtime Request</h2>
                <div class="flex flex-col gap-4">
                    <form @submit.prevent="submitOvertime()" class="flex flex-col gap-1 min-h-96">
                        <div class="flex items-center justify-center h-64 gap-4 text-center" v-if="fetchingSchedule">
                            <span class="loading loading-spinner"></span> Loading Schedule...
                        </div>
                        <div v-else>
                            <fieldset class="fieldset bg-base-200 border-base-300 rounded-md w-xs border p-4">
                                <legend class="fieldset-legend">Requested Overtime Date</legend>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="col-span-1">
                                        <TextInput name="Date:" type="text" v-model="formFiling.date" :readonly="true"
                                            :placeholder="''" />
                                    </div>
                                    <div class="col-span-1">
                                        <TextInput name="Week:" type="text" v-model="formFiling.week" :readonly="true"
                                            :placeholder="''" />
                                    </div>
                                </div>
                            </fieldset>

                            <fieldset class="fieldset bg-base-200 border-base-300 rounded-md w-xs border p-4">
                                <legend class="fieldset-legend">Your Scheduled Shift</legend>
                                <div class="grid grid-cols-5 gap-4">
                                    <div class="col-span-1">
                                        <TextInput name="Shift Code:" type="text" v-model="formFiling.shift_code"
                                            :readonly="true" :placeholder="''" />
                                    </div>
                                    <div class="col-span-2">
                                        <TextInput name="Start:" type="text" v-model="formFiling.shift_start_time"
                                            :readonly="true" :placeholder="''" />
                                    </div>
                                    <div class="col-span-2">
                                        <TextInput name="End:" type="text" v-model="formFiling.shift_end_time"
                                            :readonly="true" :placeholder="''" />
                                    </div>
                                </div>
                            </fieldset>


                            <fieldset class="fieldset bg-base-200 border-base-300 rounded-md w-xs border p-4">
                                <legend class="fieldset-legend">Overtime Duration and Reason</legend>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="col-span-1">
                                        <SelectOption name="Start Time:" :message="formFiling.errors?.start_time"
                                            v-model="formFiling.start_time" :options="timeOptions" />
                                    </div>
                                    <div class="col-span-1">
                                        <SelectOption name="End Time:" :message="formFiling.errors?.end_time"
                                            v-model="formFiling.end_time" :options="timeOptions" />
                                    </div>
                                </div>
                                <TextArea name="Reason:" type="text" v-model="formFiling.reason"
                                    :message="formFiling.errors?.reason" />
                            </fieldset>
                            <div v-if="withShedule">
                                <div class="flex justify-end gap-4">
                                    <button type="button" class="btn btn-neutral mt-4" :disabled="formFiling.processing"
                                        @click="closeOvertimeFilingModal()">Cancel</button>
                                    <button type="submit" class="btn btn-primary mt-4"
                                        :disabled="formFiling.processing">
                                        <span v-if="formFiling.processing" class="loading loading-spinner"></span>
                                        <span>Submit</span>
                                    </button>
                                </div>
                            </div>
                            <div v-else
                                class="flex flex-col items-center justify-center mt-4 text-error font-semibold text-center">
                                <div class="flex flex-row gap-2 justify-center">
                                    <Icon icon="ph:warning" width="24" height="24" />
                                    <p>No registered Schedule</p>
                                </div>
                                <div class="flex justify-center gap-6">
                                    <button type="button" class="btn btn-neutral mt-4 w-full"
                                        @click="closeOvertimeFilingModal()">Close</button>
                                    <Link :href="route('schedule')" type="button" class="btn btn-primary mt-4 w-full">
                                    Add
                                    Schedule</Link>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </Modal>
    <Modal ref="overtimeRequestModal">
        <div class="flex flex-col gap-2 w-full">
            <div class="flex justify-end">
                <span class="hover:bg-base-300 rounded-full p-2 cursor-pointer" @click="closeOvertimeRequestModal()">
                    <Icon icon="material-symbols:close-rounded" width="18" height="18" />
                </span>
            </div>
            <div class="max-w-lg mx-auto p-3 bg-base-100 space-y-6 w-full">
                <form @submit.prevent="submitCancelation()">
                    <!-- Stepper -->
                    <Stepper :status="formFilledOvertime.current_status" />
                    <!-- Filing Information -->
                    <div class="space-y-6 text-sm">

                        <!-- Meta Info -->
                        <fieldset class="bg-base-200 border border-base-300 p-4 rounded-md">
                            <legend class="text-sm font-semibold px-2">Meta Information</legend>
                            <div class="grid gap-2 mt-4">
                                <div class="flex flex-row gap-2">
                                    <label class="label">
                                        <span class="label-text">Date Filed:</span>
                                    </label>
                                    <span class="font-medium">{{ formFilledOvertime.created_at }}</span>
                                </div>
                                <div class="flex flex-row gap-2">
                                    <label class="label">
                                        <span class="label-text">Week:</span>
                                    </label>
                                    <span class="font-medium">{{ formFilledOvertime.week }}</span>
                                </div>
                                <div class="flex flex-row gap-2">
                                    <label class="label">
                                        <span class="label-text">Status: </span>
                                    </label>
                                    <span :class="['font-medium',
                                        formFilledOvertime.status === 'PENDING' ? 'text-warning' : (formFilledOvertime.status === 'APPROVED' ? 'text-success' : (['DISAPPROVED', 'CANCELED'].includes(formFilledOvertime.status) ? 'text-error' : (formFilledOvertime.status === 'FILED' ? 'text-primary' : '')))
                                    ]">
                                        {{ formFilledOvertime.current_status }}</span>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Overtime Schedule -->
                        <fieldset class="bg-base-200 border border-base-300 p-4 rounded-md">
                            <legend class="text-sm font-semibold px-2">Overtime Schedule</legend>
                            <div class="grid gap-2 mt-4">
                                <div class="flex flex-row gap-2">
                                    <label class="label">
                                        <span class="label-text">Date: </span>
                                    </label>
                                    <span class="font-medium">{{ formFilledOvertime.date }}</span>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <TextInput name="Start:" type="text" v-model="formFilledOvertime.start_time"
                                        :placeholder="''" :disabled="formFilledOvertime.status !== 'PENDING'" />
                                    <TextInput name="End:" type="text" v-model="formFilledOvertime.end_time"
                                        :placeholder="''" :disabled="formFilledOvertime.status !== 'PENDING'" />
                                </div>
                                <div class="flex flex-row gap-2">
                                    <label class="label">
                                        <span class="label-text">Total Hour(s): </span>
                                    </label>
                                    <span class="font-medium">{{ formFilledOvertime.hours }}</span>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Reason -->
                        <fieldset class="bg-base-200 border border-base-300 p-4 rounded-md">
                            <legend class="text-sm font-semibold px-2">Reason</legend>
                            <TextArea type="text" v-model="formFilledOvertime.reason"
                                :message="formFilledOvertime.errors?.reason"
                                :disabled="formFilledOvertime.status !== 'PENDING'" />
                        </fieldset>

                        <!-- Remarks -->
                        <fieldset class="bg-base-200 border border-base-300 p-4 rounded-md">
                            <legend class="text-sm font-semibold px-2">Remarks</legend>
                            <p class="mt-2">{{ formFilledOvertime.remarks }}</p>
                        </fieldset>
                    </div>

                    <!-- Action Button -->
                    <div v-if="formFilledOvertime.current_status == 'PENDING'">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1">
                                <button v-if="!confirmingCancel" type="submit" class="btn btn-outline w-full mt-6"
                                    :disabled="formFilledOvertime.processing" @click="modeUpdate = true">
                                    <span v-if="formFilledOvertime.processing && modeUpdate"
                                        class="loading loading-spinner"></span>
                                    Update
                                </button>
                            </div>
                            <div class="col-span-1">
                                <button v-if="!confirmingCancel" type="button" class="btn btn-outline w-full mt-6"
                                    @click="confirmingCancel = true" :disabled="formFilledOvertime.processing">
                                    Cancel Request
                                </button>
                                <div v-else class="flex justify-end gap-6 mt-6">
                                    <button type="submit" class="btn btn-error" @click="modeUpdate = false"
                                        :disabled="formFilledOvertime.processing">
                                        <span v-if="formFilledOvertime.processing"
                                            class="loading loading-spinner"></span>
                                        <span> Yes, Cancel.</span></button>
                                    <button type="button" class="btn btn-neutral" @click="confirmingCancel = false"
                                        :disabled="formFilledOvertime.processing">No</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </Modal>
    <div class="flex flex-col px-4 py-6 gap-6 h-full">

        <div class="stats shadow grid grid-cols-4">
            <Card title="Total Overtime Hours" :value="totalovertime + ' hrs'" />
            <Card title="Approved Requests" :value="approvedovertime" />
            <Card title="Pending Requests" :value="pendingovertime" />
            <Card title="Rejected Requests" :value="rejectedovertime" />
        </div>

        <div class="gap-4 grid grid-cols-5">

            <div
                class="col-span-3 flex flex-col justify-center p-4 sm:p-6 rounded-lg shadow bg-base-100 h-auto border border-base-300">
                <header class="flex items-center justify-between mb-4 sm:mb-6">
                    <button class="btn btn-circle btn-sm sm:btn-md btn-ghost" @click="handlePreviousMonth()">
                        <Icon icon="ic:round-navigate-before" class="w-12 h-12 sm:w-12 sm:h-12" />
                    </button>

                    <p class="font-bold text-lg sm:text-2xl text-base-content">
                        {{ currentMonthYear }}
                    </p>

                    <button class="btn btn-circle btn-sm sm:btn-md btn-ghost" @click="handleNextMonth()">
                        <Icon icon="ic:round-navigate-next" class="w-12 h-12 sm:w-12 sm:h-12" />
                    </button>
                </header>

                <ul
                    class="grid grid-cols-7 gap-2 text-center font-medium uppercase tracking-wide text-xs sm:text-sm text-base-content/70">
                    <li>Sun</li>
                    <li>Mon</li>
                    <li>Tue</li>
                    <li>Wed</li>
                    <li>Thu</li>
                    <li>Fri</li>
                    <li>Sat</li>
                </ul>

                <ul class="grid grid-cols-7 gap-y-2 text-center mt-3 sm:mt-4 text-sm sm:text-lg font-semibold">
                    <li v-for="(days, index) in calendardays" :key="index" :class="[
                        ['next', 'prev'].includes(days.type) ? 'pointer-events-none opacity-40' : '',
                        'flex justify-center items-center'
                    ]">
                        <span :class="[
                            'w-14 h-14 sm:w-16 sm:h-16 flex items-center justify-center rounded-md cursor-pointer',
                            (actualDay === parseInt(days.day) &&
                                actualYear === parseInt(days.year) &&
                                actualMonth === parseInt(days.month))
                                ? 'bg-primary text-primary-content'
                                : 'hover:bg-base-300'
                        ]" @click="showOvertimeFilingModal(currentYear, currentMonth, days.day)">
                            {{ days.day }}
                        </span>
                    </li>
                </ul>
            </div>

            <div class="col-span-2 flex flex-col gap-4">

                <!-- Upcoming Holidays -->
                <div class="rounded-md p-4 shadow flex flex-col bg-base-100 h-80">
                    <h2 class="text-lg font-bold mb-4">Upcoming Holidays</h2>
                    <ul class="flex-1 space-y-2 overflow-y-auto mt-2 pb-2 text-sm">
                        <li v-if="loadingHolidays">
                            <p class="font-light italic text-center mt-5"><span class="loading loading-spinner"></span>
                                Loading Upcoming Holidays...</p>
                        </li>
                        <li v-else-if="!loadingHolidays && holidays.length > 0" v-for="(h, idx) in holidays" :key="idx"
                            class="card w-full shadow-md border border-base-200 rounded-md p-4 hover:shadow-md hover:border-primary duration-300 cursor-pointer">
                            <div class="flex flex-row justify-between gap-8">
                                <!-- <p class="text-sm opacity-70">{{ h.date }}</p> -->
                                <div class="w-2/4">
                                    <p class="text-lg font-semibold overflow-hidden text-ellipsis">{{ h.localName }}</p>
                                    <p class="text-sm opacity-75">{{ h.name }}</p>
                                </div>

                                <div class="grid items-center">
                                    <div class="badge badge-primary h-auto">{{ h.date }}</div>
                                </div>
                            </div>
                        </li>
                        <li v-else-if="holidayMessage.length > 0">
                            <p class="font-light italic text-center mt-5">{{ holidayMessage }}</p>
                        </li>
                        <li v-else="holidays.length === 0">
                            <p class="font-light italic text-center mt-5">No Upcoming Holidays...</p>
                        </li>
                    </ul>
                </div>

                <!-- My Requests -->
                <div class="rounded-md p-4 shadow flex flex-col bg-base-100 h-96">
                    <div class="flex justify-between items-center mb-4 p-2">
                        <h2 class="text-lg font-bold">My Recent Requests</h2>
                        <Link :href="route('overtime.requests.employee')"
                            class="btn btn-sm btn-primary flex items-center gap-1">
                        See More
                        <Icon icon="ic:round-navigate-next" width="18" height="18" />
                        </Link>
                    </div>
                    <ul class="flex-1 space-y-2 overflow-y-auto pb-2 text-sm">
                        <li v-if="recentRequests.length === 0">
                            <p class="font-light italic text-center mt-5">No Recent Request...</p>
                        </li>
                        <li v-for="request in recentRequests" :key="request.id"
                            @click="showOvertimeRequestModal(request)"
                            class="card w-full shadow-md border border-base-200 rounded-md p-4 hover:shadow-md hover:border-primary duration-300 cursor-pointer">
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col gap-1">
                                    <p class="text-sm opacity-70">{{ request.date }}</p>
                                    <p class="text-lg font-semibold">
                                        {{ request.start_time }} → {{ request.end_time }}
                                    </p>
                                    <p class="text-sm opacity-75">{{ request.hours }} hr(s)</p>
                                </div>
                                <div>
                                    <div :class="['badge', 'badge-outline',
                                        request.status === 'PENDING' ? 'badge-warning' :
                                            (request.status === 'APPROVED' ? 'badge-success' :
                                                (['DISAPPROVED', 'CANCELED'].includes(request.status) ? 'badge-error' :
                                                    (request.status === 'FILED' ? 'badge-primary' : '')))]">
                                        {{ request.status }}
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
// ========== Imports ==========
import { Link, useForm, router } from '@inertiajs/vue3'
import { onMounted, ref, inject, watch, computed } from 'vue'
import Modal from '../Components/Modal.vue'
import TextInput from '../Components/TextInput.vue'
import TextArea from '../Components/TextArea.vue'
import SelectOption from '../Components/SelectOption.vue'
import Card from '../Components/Card.vue'
import { fetchUserSchedule } from '../api/schedule.js'
import { getEmployeeOvertimeStats } from '../utils/overtimeMapper.js'
import fetchUpcomingHolidays from '../api/upcomingHolidays.js'
import { getTimeOptions } from '../utils/dropdownOptions.js'
import Stepper from '../Components/Stepper.vue'
import { Icon } from "@iconify/vue"


// ========== Global Constants ==========
const date = new Date()
const months = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
]
const toast = inject('toast')
const confirmingCancel = ref(false)
const modeUpdate = ref(false)
const greetingMessage = ref('')
const loadingHolidays = ref(false)
const timeOptions = computed(() => getTimeOptions())
const holidayMessage = ref('')

// ========= Props =============
const props = defineProps({
    info: Object,
    payload: Object,
    errors: Object,
    flash: Object,
    success: Boolean,
    message: String,
    auth: Object,
})

// ========== Calendar Refs ==========
const currentMonthYear = ref('')
const currentYear = ref(props?.payload?.year)
const currentMonth = ref(props?.payload?.month - 1)

const lastDateOfMonth = ref(0)
const firstDayOfMonth = ref(0)
const lastDateOfLastMonth = ref(0)
const calendardays = ref([])

// actual date (should not updated to defined specific 'day' in the calendar)
const actualYear = ref(props?.payload?.actual?.year)
const actualMonth = ref(props?.payload?.actual?.month - 1)
const actualDay = ref(props?.payload?.actual?.day)

// ========== Modal Refs ==========
const overtimeFilingModal = ref(null)
const overtimeRequestModal = ref(null)
const fetchingSchedule = ref(false)
const withShedule = ref(true)

// ========== Overtime Request ==========
const recentRequests = ref([...props.info?.overtimelist] ?? [])
const holidays = ref([])


// ========== Card Stats ==========
const totalovertime = ref(0)
const pendingovertime = ref(0)
const approvedovertime = ref(0)
const rejectedovertime = ref(0)

// ========== Form(s) ==========
const formFiling = useForm({
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

const formFilledOvertime = useForm({
    id: '',
    date: '',
    created_at: '',
    week: '',
    hours: '',
    start_time: '',
    end_time: '',
    current_status: '',
    update_status: 'CANCELED',
    reason: '',
    remarks: ''
})



// ========== Lifecycle ==========
onMounted(async () => {
    updateCurrentMonthYear(currentYear.value, currentMonth.value)
    let { totalovertimehours, approvedrequests, pendingrequests, rejectedrequests } = getEmployeeOvertimeStats(recentRequests.value)

    totalovertime.value = totalovertimehours
    approvedovertime.value = approvedrequests
    pendingovertime.value = pendingrequests
    rejectedovertime.value = rejectedrequests

    if (props.flash?.message) {
        greetingMessage.value = props.flash?.message
    }

    loadingHolidays.value = true
    let response = await fetchUpcomingHolidays()

    if (response.success) {
        if (response.holidays.length > 0) {
            holidays.value = response.holidays
        } else {
            holidayMessage.value = 'No upcoming holidays found.'
        }
    } else {
        holidayMessage.value = 'Failed to load Upcoming Holidays.'
    }

    loadingHolidays.value = false
})


// ========== Modal Handlers ==========
const closeOvertimeFilingModal = () => {
    overtimeFilingModal.value?.close()
}

const closeOvertimeRequestModal = () => {
    overtimeRequestModal.value?.close()
    formFilledOvertime.reset()
}


const showOvertimeFilingModal = async (year, month, day) => {
    overtimeFilingModal.value?.open()
    fetchingSchedule.value = true
    formFiling.reset()

    let scheduleResponse = await fetchUserSchedule(year, month + 1, day)

    if (scheduleResponse.data?.success) {
        let scheduledata = scheduleResponse.data?.schedule

        if (Object.keys(scheduledata).length > 0) {
            withShedule.value = true
            formFiling.date = scheduledata.date
            formFiling.week = scheduledata.week
            formFiling.shift_code = scheduledata.shift_code
            formFiling.employee_schedule_id = scheduledata.id
            formFiling.shift_start_time = scheduledata.shift_start_time
            formFiling.shift_end_time = scheduledata.shift_end_time
        } else {
            withShedule.value = false
        }

        fetchingSchedule.value = false
    } else {
        toast('Failed to load schedule. Please try again', 'error')
        fetchingSchedule.value = false
    }
}

const handleMonthSelection = (year, month) => {
    router.get(route('main'), {
        year: year,
        month: month
    }, {
        preserveState: true
    })
}


const showOvertimeRequestModal = (data) => {
    formFilledOvertime.id = data.id
    formFilledOvertime.date = data.date
    formFilledOvertime.created_at = data.created_at
    formFilledOvertime.week = data.week
    formFilledOvertime.hours = data.hours
    formFilledOvertime.start_time = data.start_time
    formFilledOvertime.end_time = data.end_time
    formFilledOvertime.current_status = data.status
    formFilledOvertime.reason = data.reason
    formFilledOvertime.remarks = data.remarks
    overtimeRequestModal.value?.open()
}


// ========== Calendar Navigation ==========
const handlePreviousMonth = () => {
    if (currentMonth.value === 0) {
        currentMonth.value = 11
        currentYear.value -= 1
    } else {
        currentMonth.value -= 1
    }

    updateCurrentMonthYear(currentYear.value, currentMonth.value)
    handleMonthSelection(currentYear.value, currentMonth.value + 1)
}

const handleNextMonth = () => {
    if (currentMonth.value === 11) {
        currentMonth.value = 0
        currentYear.value += 1
    } else {
        currentMonth.value += 1
    }

    updateCurrentMonthYear(currentYear.value, currentMonth.value)
    handleMonthSelection(currentYear.value, currentMonth.value + 1)
}


// ========== Calendar Core Logic ==========
function updateCurrentMonthYear(year, month) {
    currentMonthYear.value = `${months[month]} ${year}`
    lastDateOfMonth.value = new Date(year, month + 1, 0).getDate()
    lastDateOfLastMonth.value = new Date(year, month, 0).getDate()
    firstDayOfMonth.value = new Date(year, month, 1).getDay()

    calendardays.value = [] // reset

    // previous month's trailing days (gray out)
    const prevMonth = month === 0 ? 11 : month - 1
    const prevYear = month === 0 ? year - 1 : year
    for (let i = firstDayOfMonth.value; i > 0; i--) {
        calendardays.value.push({
            year: prevYear,
            month: prevMonth,
            day: lastDateOfLastMonth.value - i + 1,
            type: 'prev'
        })
    }

    // current month days
    for (let i = 1; i <= lastDateOfMonth.value; i++) {
        calendardays.value.push({
            year: year,
            month: month,
            day: i,
            type: 'current'
        })
    }

    // next month's leading days (to fill the rest of the grid)
    // // use 42 to have 6 rows only (6x7)
    const nextMonth = month === 11 ? 0 : month + 1
    const nextYear = month === 11 ? year + 1 : year
    const remaining = 42 - calendardays.value.length
    for (let i = 1; i <= remaining; i++) {
        calendardays.value.push({
            year: nextYear,
            month: nextMonth,
            day: i,
            type: 'next'
        })
    }
}



// ========== useForm Request(s) Handler ==========
const submitOvertime = () => {
    formFiling.post(route('overtime.request'), {
        onSuccess: () => {
            toast('Overtime Request Filing successful!', 'success')
            formFiling.reset()
            closeOvertimeFilingModal()
            modeUpdate.value = false
        },
        onError: () => {
            toast('Overtime Request failed.', 'error')
        }
    })
}

const submitCancelation = () => {

    if (modeUpdate.value) {
        formFilledOvertime.update_status = 'PENDING'
    } else {
        formFilledOvertime.update_status = 'CANCELED'
    }

    formFilledOvertime.post(route('overtime.update.employee'), {
        onSuccess: () => {
            if (modeUpdate) {
                toast('Updating Successful', 'success')
            } else {
                toast('Cancelation Successful', 'success')
            }

            formFilledOvertime.reset()
            closeOvertimeRequestModal()
        },
        onError: () => {
            toast('Cancelation Request failed.', 'error')
        }
    })
}


// ======== Watchers ===========

watch(() => props.info?.overtimelist, (updatedRequests) => {
    recentRequests.value = [...updatedRequests]

    let { totalovertimehours, approvedrequests, pendingrequests, rejectedrequests } = getEmployeeOvertimeStats(recentRequests.value)

    totalovertime.value = totalovertimehours
    approvedovertime.value = approvedrequests
    pendingovertime.value = pendingrequests
    rejectedovertime.value = rejectedrequests
})

watch(() => props.info?.payload, (updatedPayload) => {
    currentYear.value = updatedPayload.year
    currentMonth.value = updatedPayload.month
})
</script>