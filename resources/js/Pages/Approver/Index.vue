<template>
    <div class="flex flex-col gap-6 p-4 h-screen">
        <!-- Stat Cards -->
        <div class="stats stats-horizontal shadow flex-wrap">
            <Card title="Total Approved" :value="props?.info?.total_approved ?? 0" />
            <Card title="Pending Approvals" :value="props?.info?.total_pending ?? 0" />
            <Card title="Required Hours Left"
                :value="(props?.info?.required.hours - props?.info?.total_hours) + ' hrs' ?? 0" />
            <Card title="Total Filed Requests" :value="props?.info?.total_filed ?? 0" />
        </div>

        <!-- Weekly Overview -->
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">Week 32 Overview</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore laudantium nemo id ut, fugiat
                        reiciendis excepturi iusto aspernatur optio harum.</p>
                </div>
            </div>

            <!-- Weekly Hours Progress -->
            <div class="col-span-1 card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">Weekly Hours Progress</h2>
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
                                    <button class="btn btn-xs text-sm btn-primary">Manage</button>
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

const selectedWeek = ref(currentWeek())
const selectedYear = ref('')

const props = defineProps({
    info: Object,
    success: Boolean,
    message: String
})

console.log(props.info)

const requests = ref([...props?.info?.requests ?? []])

watch(() => props?.info?.requests, (updatedRequest) => {
    requests.value = [...updatedRequest]
})

</script>