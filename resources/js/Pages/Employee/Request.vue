<template>
    <div class="flex flex-col gap-6 p-4 min-h-screen">
        <!-- Breadcrumbs -->
        <div class="breadcrumbs text-sm">
            <ul>
                <li>
                    <Link :href="route('main')">Home</Link>
                </li>
                <li>
                    <Link :href="route('request')">Overtime Request</Link>
                </li>
            </ul>
        </div>

        <div class="flex justify-center items-center min-h-[50vh]">
            <div class="card bg-base-100 w-full shadow-sm h-[50vh] flex flex-col">
                <div class="card-body flex flex-col h-full">
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold">Overtime Requests</h1>
                    </div>

                    <div class="flex flex-row items-center gap-4 mb-4">
                        <TextInput name="" type="text" v-model="searchValue" :placeholder="'Search date or week'"
                            margin="" class="w-3/4" />
                        <SelectOption :options="weeks" v-model="selectedWeek" margin="" />
                        <SelectOption :options="statuses" v-model="selectedStatus" margin="" />

                        <button class="btn btn-primary flex flex-row items-center gap-2" @click="applyFilter">
                            <span>Apply Filter</span>
                            <Icon icon="proicons:filter" width="24" height="24" />
                        </button>
                    </div>


                    <hr>
                    <div class="overflow-x-auto rounded-box bg-base-100 flex-1">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Week</th>
                                    <th>Hours</th>
                                    <th>Reason</th>
                                    <th>Remarks</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="requests.length === 0">
                                    <th colspan="6" class="text-center italic opacity-50">Under Development</th>
                                </tr>

                                <tr v-else v-for="req in requests" :key="req.id">
                                    <th>{{ req.date }}</th>
                                    <th>{{ req.week }}</th>
                                    <th>{{ req.hours }}</th>
                                    <th>{{ req.reason }}</th>
                                    <th>{{ req.remarks ?? 'N/A' }}</th>
                                    <th>{{ req.status }}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <div class="text-sm">
                            Showing 3 out of 10 entries
                        </div>
                        <div class="join">
                            <button class="join-item btn">1</button>
                            <button class="join-item btn btn-active">2</button>
                            <button class="join-item btn">3</button>
                            <button class="join-item btn">4</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { weeks, statuses } from '../utils/dropdownOptions.js'
import SelectOption from '../Components/SelectOption.vue'
import TextInput from '../Components/TextInput.vue'
import { Icon } from "@iconify/vue"

const props = defineProps({
    info: Object,
    payload: Object,
    errors: Object,
    flash: Object,
    success: Boolean,
    message: String,
    auth: Object,
})

const selectedWeek = ref('')
const selectedStatus = ref('')
const searchValue = ref('')
const requests = ref([...props.info?.requests] ?? [])

</script>