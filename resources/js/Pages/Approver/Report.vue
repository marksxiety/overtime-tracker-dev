<template>
    <div class="flex flex-col items-center justify-center min-h-screen bg-base-300 px-4">
        <div class="card bg-base-100 shadow-2xl rounded-2xl w-full max-w-5xl overflow-hidden">
            <div class="grid md:grid-cols-2 gap-6">

                <figure class="flex items-center justify-center bg-base-200 p-6">
                    <img :src="reportImage" alt="report" class="object-contain w-full h-full max-h-96 rounded-xl" />
                </figure>

                <div class="card-body flex flex-col">
                    <h2 class="text-2xl font-bold text-primary">Generate Report</h2>
                    <p class="text-sm text-gray-500">Choose your preferred options to generate a detailed overtime
                        tracking report.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <SelectOption name="Select Mode:" :options="modes" v-model="selectedMode" />
                        <SelectOption name="Select Year:" :options="years" v-model="selectedYear" />
                        <SelectOption name="Select Month:" :options="months" v-model="selectedMonth" />
                        <SelectOption name="Select Week:" :options="weeks" v-model="selectedWeek" />
                    </div>

                    <div class="card-actions justify-end mt-6">
                        <button class="btn btn-primary w-full md:w-auto">Generate</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import reportImage from '../../images/generate-report.svg'
import SelectOption from '../Components/SelectOption.vue'

const selectedMode = ref('')
const selectedYear = ref('')
const selectedMonth = ref('')
const selectedWeek = ref('')

const modes = ref([
    { label: 'Yearly', value: 'yearly' },
    { label: 'Monthly', value: 'monthly' },
    { label: 'Weekly', value: 'weekly' },
])

const years = ref([
    { label: '2023', value: '2023' },
    { label: '2024', value: '2024' },
    { label: '2025', value: '2025' },
])

const months = ref([
    { label: 'January', value: '01' },
    { label: 'February', value: '02' },
    { label: 'March', value: '03' },
    { label: 'April', value: '04' },
])

const weeks = ref([
    { label: 'Week 1', value: '1' },
    { label: 'Week 2', value: '2' },
    { label: 'Week 3', value: '3' },
    { label: 'Week 4', value: '4' },
])
</script>
