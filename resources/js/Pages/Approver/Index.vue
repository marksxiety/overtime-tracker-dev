<template>
    <Modal ref="manageRequestModal" width="w-5xl">
        <div class="py-4 mt-2">
            <div class="flex flex-col gap-2 w-full">
                <Stepper :status="overtime.status" />
                <fieldset class="bg-base-200 border border-base-300 p-4 rounded-md">
                    <legend class="text-sm font-semibold px-2">Employee Information</legend>
                    <div class="grid gap-2 mt-4">
                        <div class="flex flex-row gap-2">
                            <label class="label">
                                <span class="label-text">Name:</span>
                            </label>
                            <span class="font-medium">{{ user.name }}</span>
                        </div>
                        <div class="flex flex-row gap-2">
                            <label class="label">
                                <span class="label-text">Email:</span>
                            </label>
                            <span class="font-medium">{{ user.email }}</span>
                        </div>
                        <div class="flex flex-row gap-2">
                            <label class="label">
                                <span class="label-text">Employee ID: </span>
                            </label>
                            <span class="font-medium'">
                                {{ user.employee_id }}</span>
                        </div>
                        <div class="flex flex-row gap-2">
                            <label class="label">
                                <span class="label-text">Role: </span>
                            </label>
                            <span class="font-medium capitalize">
                                {{ user.role }}</span>
                        </div>
                    </div>
                </fieldset>


                <fieldset class="bg-base-200 border border-base-300 p-4 rounded-md">
                    <legend class="text-sm font-semibold px-2">Registered Schedule</legend>
                    <div class="grid gap-2 mt-4">
                        <div class="flex flex-row gap-2">
                            <label class="label">
                                <span class="label-text">Date: </span>
                            </label>
                            <span class="font-medium">{{ schedule.date }}</span>
                        </div>
                        <div class="flex flex-row gap-2">
                            <label class="label">
                                <span class="label-text">Week: </span>
                            </label>
                            <span class="font-medium">{{ schedule.week }}</span>
                        </div>
                        <div class="flex flex-row gap-2">
                            <label class="label">
                                <span class="label-text">Shift Code: </span>
                            </label>
                            <span class="font-medium">{{ schedule.shift_code }}</span>
                        </div>
                        <div class="flex flex-row gap-2">
                            <label class="label">
                                <span class="label-text">Schedule: </span>
                            </label>
                            <span class="font-medium">{{ schedule.shift_start }} → {{
                                schedule.shift_end }}</span>
                        </div>

                    </div>
                </fieldset>
                <fieldset class="bg-base-200 border border-base-300 p-4 rounded-md">
                    <legend class="text-sm font-semibold px-2">Overtime Request</legend>
                    <div class="grid gap-2 mt-4">
                        <div class="flex flex-row gap-2">
                            <label class="label">
                                <span class="label-text">Filing Date: </span>
                            </label>
                            <span class="font-medium">{{ overtime.created_at }}</span>
                        </div>
                        <div class="flex flex-row gap-2">
                            <label class="label">
                                <span class="label-text">Time: </span>
                            </label>
                            <span class="font-medium">{{ overtime.start_time }} → {{
                                overtime.end_time }}</span>
                        </div>
                        <div class="flex flex-row gap-2">
                            <label class="label">
                                <span class="label-text">Hour(s): </span>
                            </label>
                            <span class="font-medium">{{ overtime.hours }}</span>
                        </div>
                        <div class="flex flex-row gap-2">
                            <label class="label">
                                <span class="label-text">Status: </span>
                            </label>
                            <span :class="['font-medium', `text-${identifyColorStatus(overtime.status)}`]">{{
                                overtime.status }}</span>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="bg-base-200 border border-base-300 p-4 rounded-md">
                    <legend class="text-sm font-semibold px-2">Reason</legend>
                    <p class="mt-2">{{ overtime.reason }}</p>
                </fieldset>
                <fieldset class="bg-base-200 border border-base-300 p-4 rounded-md">
                    <legend class="text-sm font-semibold px-2">Remarks</legend>
                    <TextArea type="text" v-model="overtimeRequestForm.remarks"
                        :message="overtimeRequestForm.errors?.remarks"
                        :disabled="['FILED', 'DECLINED', 'CANCELED'].includes(overtime.status)"
                        :placeholder="['FILED', 'DECLINED', 'APPROVED'].includes(overtime.status) ? '' : 'Enter any remarks regarding to request...'" />
                </fieldset>
                <div class="divider"></div>
                <div class="flex justify-between gap-4">
                    <div>
                        <button class="btn btn-neutral" @click="closeManageRequestModal()">CLOSE</button>
                    </div>
                    <div>
                        <div v-if="overtime.status === 'PENDING'" class="flex flex-end gap-2">
                            <button class="btn btn-secondary"
                                @click="updateOvertiemRequestStatus('DISAPPROVED')">DISAPPROVE</button>
                            <button class="btn btn-primary"
                                @click="updateOvertiemRequestStatus('APPROVED')">APPROVE</button>
                        </div>
                        <div v-if="overtime.status === 'APPROVED'" class="flex flex-end gap-2">
                            <button class="btn btn-secondary"
                                @click="updateOvertiemRequestStatus('DECLINED')">DECLINE</button>
                            <button class="btn btn-primary" @click="updateOvertiemRequestStatus('FILED')">FILE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Modal>
    <div class="flex flex-col gap-6 p-4 h-screen">
        <!-- Stat Cards -->
        <div class="stats stats-horizontal shadow flex-wrap">
            <Card title="Total Filed" :value="card.total_filed" />
            <Card title="For Filing" :value="card.total_approved" />
            <Card title="Pending Approvals" :value="card.total_pending" />
            <Card title="Required Hours Left" :value="((card.required_hours) - (card.total_hours)) + ' hrs'" />
            <Card title="Total Requests" :value="card.total_requests" />
        </div>

        <!-- Weekly Overview -->
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">Week {{ selectedWeek }} Overview</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore laudantium nemo id ut, fugiat
                        reiciendis excepturi iusto aspernatur optio harum.</p>
                </div>
            </div>

            <!-- Weekly Hours Progress -->
            <div class="col-span-1 card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">Weekly Overtime Usage</h2>
                    <progress class="progress progress-primary w-full" :value="card.total_hours"
                        :max="card.required_hours">
                    </progress>

                    <p class="text-sm text-right mt-1">
                        {{ card.total_hours }} / {{ card.required_hours }} hrs consumed
                    </p>

                </div>
            </div>
        </div>

        <!-- Pending Approvals Table -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <div class="flex justify-between mb-4">
                    <h2 class="card-title">Pending Approvals</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-1">
                            <SelectOption :options="weeks" v-model="selectedWeek" margin='' />
                        </div>
                        <div class="col-span-1">
                            <TextInput type="text" placeholder="Search here..." v-model="selectedYear" margin="" />
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto min-h-[40vh] max-h-[60vh]">
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
                                    No pending request(s)
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
import { weeks, currentWeek } from '../utils/dropdownOptions.js'
import Modal from '../Components/Modal.vue'
import { identifyColorStatus } from '../utils/colorIdentifier.js'
import { useForm } from '@inertiajs/vue3'

const selectedWeek = ref(currentWeek())
const selectedYear = ref('')
const toast = inject('toast')

const props = defineProps({
    info: Object,
    success: Boolean,
    message: String
})



const requests = ref([...props?.info?.requests ?? []])

const card = ref({
    total_requests: props?.info?.totals?.total_requests ?? 0,
    total_approved: props?.info?.totals?.total_approved ?? 0,
    total_pending: props?.info?.totals?.total_pending ?? 0,
    required_hours: props?.info?.totals?.required_hours ?? 0,
    total_filed: props?.info?.totals?.total_filed ?? 0,
    total_hours: props?.info?.totals?.total_hours ?? 0,
})

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

const overtimeRequestForm = useForm({
    id: '',
    current_status: '',
    update_status: '',
    remarks: ''
})

watch(() => props?.info?.requests, (updatedRequest) => {
    requests.value = [...updatedRequest]
})

watch(() => props?.info?.totals, (updatedTotals) => {
    Object.assign(card.value, updatedTotals)
})

const manageRequestModal = ref(null)

const openManageRequestModal = (data) => {
    manageRequestModal.value?.open()

    // id of the overtime request itself (will use for update status)
    // for events like approval, disapproval, declining, or filing
    user.value = {
        name: data?.user?.name,
        employee_id: data?.user?.employee_id,
        role: data?.user?.role,
        email: data?.user?.email
    }

    schedule.value = {
        date: data?.schedule?.date,
        shift_code: data?.schedule?.shift_code,
        shift_start: data?.schedule?.shift_start,
        shift_end: data?.schedule?.shift_end,
        week: data?.schedule?.week,
    }

    overtime.value = {
        start_time: data?.overtime?.start_time,
        end_time: data?.overtime?.end_time,
        hours: data?.overtime?.hours,
        status: data?.overtime?.status,
        created_at: data?.overtime?.created_at,
        reason: data?.overtime?.reason,
        remarks: data?.overtime?.remarks,
    }

    // populate the data for form (to use in updating the request's status)
    overtimeRequestForm.id = data?.id
    overtimeRequestForm.current_status = data?.overtime?.status,
        overtimeRequestForm.remarks = data?.overtime?.remarks
}

const closeManageRequestModal = () => {
    manageRequestModal.value?.close()
}

const updateOvertiemRequestStatus = (status) => {
    if (status && overtimeRequestForm.id) {
        overtimeRequestForm.update_status = status
        overtimeRequestForm.post(route('overtime.update'), {
            onSuccess: () => {
                overtimeRequestForm.reset()
                closeManageRequestModal()
                setTimeout(() => {
                    toast(`Overtime request has been ${status}`, 'success')
                }, 200);
            },
            onError: (errors) => {
                toast('Failed to update schedule. Please try again', 'error')
                console.log(errors)
            }
        })
    } else {
        toast('Failed to update schedule. Please try again', 'error')
    }
}

</script>