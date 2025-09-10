<template>
    <Head title="Report Generator"  />
    <div class="flex flex-col items-center justify-center min-h-screen bg-base-300 px-4">
        <div class="card bg-base-100 shadow-2xl rounded-2xl w-full max-w-5xl overflow-visible">
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
                        <SelectOption name="Select Year:" :options="years" v-model="selectedYear"
                            :disabled="selectedMode !== '' ? true : false" />
                        <SelectOption name="Select Month:" :options="months" v-model="selectedMonth" />
                        <SelectOption name="Select Week:" :options="weeks" v-model="selectedWeek" />
                    </div>
                    <div class="card-actions justify-end mt-6">
                        <button class="btn btn-primary w-full md:w-auto">Generate</button>
                        <button class="btn btn-neutral w-full md:w-auto" @click="handleClearState()">Clear</button>
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
import { years, weeks } from '../utils/dropdownOptions.js'

const selectedMode = ref(null)
const selectedYear = ref([])
const selectedMonth = ref([])
const selectedWeek = ref([])

const modes = ref([
    { label: 'Yearly', value: 'yearly' },
    { label: 'Monthly', value: 'monthly' },
    { label: 'Weekly', value: 'weekly' },
])

const months = ref([
    { label: 'January', value: '01' },
    { label: 'February', value: '02' },
    { label: 'March', value: '03' },
    { label: 'April', value: '04' },
    { label: 'May', value: '05' },
    { label: 'June', value: '06' },
    { label: 'July', value: '07' },
    { label: 'August', value: '08' },
    { label: 'September', value: '09' },
    { label: 'October', value: '10' },
    { label: 'November', value: '11' },
    { label: 'December', value: '12' }
])


const handleClearState = () => {
    selectedMode.value = ''
    selectedYear.value = ''
    selectedMonth.value = ''
    selectedWeek.value = ''
}
</script>
