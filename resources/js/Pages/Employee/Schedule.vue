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
                        <th class="w-1/3">Shift Code</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="schedules.length > 0" v-for="schedules in schedules" :key="schedules.id">
                        <td>{{ schedules.date }}</td>
                        <td>{{ schedules.week }}</td>
                        <td class="w-1/3">
                            <SelectOption :options="shifts" v-model="schedules.shift_code_id" />
                        </td>
                    </tr>
                    <tr v-else>
                        <td colspan="4" class="text-center italic text-gray-400 py-4">
                            No registered Schedule.
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
import { Link } from '@inertiajs/vue3'
import { years, weeks, currentWeek } from '../utils/dropdownOptions.js'
import { fetchShiftList } from '../api/shift.js'

const selectedYear = ref(new Date().getFullYear())
const selectedWeek = ref(currentWeek())

const initshifts = ref([])
const shifts = ref([])
const schedules = ref([
    {
        date: '2025-07-24',
        week: '30',
        shift_code_id: 2
    },
    {
        date: '2025-07-24',
        week: '30',
        shift_code_id: 3
    },
    {
        date: '2025-07-24',
        week: '30',
        shift_code_id: 4
    }
])

onMounted(async () => {
    const shiftsreponse = await fetchShiftList()
    initshifts.value = shiftsreponse.data

    // after fetching the initialize shift codes
    // format it for the label and value in able to properly populate it in SelectOption value
    // since that component expects key of 'label' and 'value' only
    initshifts.value.data.forEach(element => {
        shifts.value.push({
            label: `${element.code}: ${element.start_time} - ${element.end_time}`,
            value: element.id
        })
    })
})

</script>