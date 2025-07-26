<template>
    <div class="flex flex-col gap-6 h-full">
        <div class="breadcrumbs text-sm">
            <ul>
                <li>
                    <Link :href="route('main')">Home</Link>
                </li>
                <li>
                    <Link :href="route('schedule')">Manage Schedule</Link>
                </li>
            </ul>
        </div>
        <!-- Page Heading -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Manage Schedule</h1>
            <div class="grid grid-cols-2 gap-4 w-1/3">
                <div class="col-span-1 w-full">
                    <SelectOption name="Year" :options="years" v-model="selectedYear" />
                </div>
                <div class="col-span-1 w-full">
                    <SelectOption name="Week" :options="weeks" v-model="selectedWeek" />
                </div>
            </div>
        </div>
        <hr>

        <div class="overflow-x-auto">
            <table class="table table-zebra min-h-96">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Week</th>
                        <th>Day</th>
                        <th class="w-1/3">Shift Code</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="isLoading">
                        <td colspan="4" class="text-center italic text-gray-400 py-4">
                            <span class="loading loading-spinner"></span> Loading Schedules...
                        </td>
                    </tr>

                    <template v-else-if="schedules.length > 0">
                        <tr v-for="schedule in schedules" :key="schedule.id">
                            <td>{{ schedule.date }}</td>
                            <td>{{ schedule.week }}</td>
                            <td>{{ schedule.day }}</td>
                            <td class="w-1/3">
                                <SelectOption :options="shifts" v-model="schedule.shift_code" />
                            </td>
                        </tr>
                    </template>

                    <tr v-else>
                        <td colspan="4" class="text-center italic text-gray-400 py-4">
                            {{ tableText.value }}
                        </td>
                    </tr>
                </tbody>

            </table>
            <hr>
            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary mt-4">
                    <span>Submit</span>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import SelectOption from '../Components/SelectOption.vue'
import { onMounted, ref } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { years, weeks, currentWeek } from '../utils/dropdownOptions.js'
import { fetchShiftList } from '../api/shift.js'
import { fetchSchedule } from '../api/schedule.js'

const selectedYear = ref(new Date().getFullYear())
const selectedWeek = ref(currentWeek())
const page = usePage()
const tableText = ref('No registered Schedule.')

// fetch the user's id (session) to fetch specific schedule
const user_id = ref(page?.props?.auth?.user?.id)

const isLoading = ref(true)
const initshifts = ref([])
const shifts = ref([])
const schedules = ref([])

onMounted(async () => {
    const schedulresponse = await fetchSchedule(user_id.value, selectedYear.value, selectedWeek.value)

    if (schedulresponse.data.success) {
        const shiftsreponse = await fetchShiftList()
        initshifts.value = shiftsreponse.data
        schedules.value = schedulresponse.data.schedules
        // after fetching the initialize shift codes
        // format it for the label and value in able to properly populate it in SelectOption value
        // since that component expects key of 'label' and 'value' only
        initshifts.value.data.forEach(element => {
            shifts.value.push({
                label: `${element.code}: ${element.start_time} - ${element.end_time}`,
                value: element.id
            })
        })
    } else {
        tableText.value = 'Failed to load schedules.'
    }
    isLoading.value = false
})

</script>