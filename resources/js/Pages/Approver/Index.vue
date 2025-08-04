<template>
    <Modal ref="manageRequestModal" width="w-11/12">
        <div class="py-4 mt-2">
            <div class="flex justify-end">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                    @click="closeManageRequestModal()">✕</button>
            </div>
            <div class="flex flex-col gap-2 w-full">
                <fieldset class="bg-base-200 border border-base-300 p-4 rounded-md w-full">
                    <legend class="text-sm font-semibold px-2">Employee Information</legend>
                    <div class="flex flex-col gap-4 mt-4 w-full">
                        <div class="grid grid-cols-2 gap-4">
                            <TextInput name="Name:" type="text" v-model="user.name" :readonly="true"
                                :placeholder="''" />
                            <TextInput name="Email:" type="text" v-model="user.email" :readonly="true"
                                :placeholder="''" />
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <TextInput name="Employee ID:" type="text" v-model="user.employee_id" :readonly="true"
                                :placeholder="''" />
                            <TextInput name="Role:" type="text" v-model="user.role" :readonly="true"
                                :placeholder="''" />
                        </div>
                    </div>
                </fieldset>
                <fieldset class="bg-base-200 border border-base-300 p-4 rounded-md w-full mt-6">
                    <legend class="text-sm font-semibold px-2">Schedule Information</legend>
                    <div class="flex flex-col gap-4 mt-4 w-full">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1">
                                <TextInput name="Date:" type="text" v-model="schedule.date" :readonly="true"
                                    :placeholder="''" />
                            </div>
                            <div class="col-span-1">
                                <TextInput name="Week:" type="text" v-model="schedule.week" :readonly="true"
                                    :placeholder="''" />
                            </div>
                        </div>
                        <div class="grid grid-cols-5 gap-4">
                            <div class="col-span-1">
                                <TextInput name="Date:" type="text" v-model="schedule.shift_code" :readonly="true"
                                    :placeholder="''" />
                            </div>
                            <div class="col-span-2">
                                <TextInput name="Week:" type="text" v-model="schedule.shift_start" :readonly="true"
                                    :placeholder="''" />
                            </div>
                            <div class="col-span-2">
                                <TextInput name="Week:" type="text" v-model="schedule.shift_end" :readonly="true"
                                    :placeholder="''" />
                            </div>
                        </div>
                    </div>
                </fieldset>
                <fieldset class="bg-base-200 border border-base-300 p-4 rounded-md w-full mt-6">
                    <legend class="text-sm font-semibold px-2">Overtime Request</legend>
                    <div class="flex flex-col gap-4 mt-4 w-full">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-1">
                                <TextInput name="Date:" type="text" v-model="schedule.date" :readonly="true"
                                    :placeholder="''" />
                            </div>
                            <div class="col-span-1">
                                <TextInput name="Week:" type="text" v-model="schedule.week" :readonly="true"
                                    :placeholder="''" />
                            </div>
                        </div>
                        <div class="grid grid-cols-5 gap-4">
                            <div class="col-span-1">
                                <TextInput name="Date:" type="text" v-model="schedule.shift_code" :readonly="true"
                                    :placeholder="''" />
                            </div>
                            <div class="col-span-2">
                                <TextInput name="Week:" type="text" v-model="schedule.shift_start" :readonly="true"
                                    :placeholder="''" />
                            </div>
                            <div class="col-span-2">
                                <TextInput name="Week:" type="text" v-model="schedule.shift_end" :readonly="true"
                                    :placeholder="''" />
                            </div>
                        </div>
                    </div>
                </fieldset>
                <div class="flex justify-end gap-4">
                    <button type="submit" class="btn btn-success mt-4">
                        <span>APPROVE</span>
                    </button>
                    <button type="submit" class="btn btn-error mt-4">
                        <span>DISAPPROVE</span>
                    </button>
                    <button type="submit" class="btn btn-info mt-4">
                        <span>FILED</span>
                    </button>
                </div>
            </div>
        </div>
    </Modal>
    <div class="flex flex-col gap-6 p-4 h-screen">
        <!-- Stat Cards -->
        <div class="stats stats-horizontal shadow flex-wrap">
            <Card title="Total Approved" :value="props?.info?.total_approved ?? 0" />
            <Card title="Pending Approvals" :value="props?.info?.total_pending ?? 0" />
            <Card title="Required Hours Left"
                :value="((props?.info?.required.hours ?? 0) - (props?.info?.total_hours ?? 0)) + ' hrs'" />
            <Card title="Total Filed Requests" :value="props?.info?.total_filed ?? 0" />
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
                    <progress class="progress progress-primary w-full" :value="props?.info?.total_hours"
                        :max="props?.info?.required?.hours">
                    </progress>

                    <p class="text-sm text-right mt-1">
                        {{ props?.info?.total_hours }} / {{ props?.info?.required?.hours }} hrs consumed
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
import { weeks, currentWeek } from '../utils/dropdownOptions.js'
import Modal from '../Components/Modal.vue'

const selectedWeek = ref(currentWeek())
const selectedYear = ref('')

const props = defineProps({
    info: Object,
    success: Boolean,
    message: String
})

console.log(props.info)

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

watch(() => props?.info?.requests, (updatedRequest) => {
    requests.value = [...updatedRequest]
})


const manageRequestModal = ref(null)

const openManageRequestModal = (data) => {
    manageRequestModal.value?.open()
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

}

const closeManageRequestModal = () => {
    manageRequestModal.value?.close()
}

</script>