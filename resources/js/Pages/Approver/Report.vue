<template>
    <div class="flex flex-col items-center justify-center min-h-screen bg-base-300 px-4">
        <div class="card bg-base-100 shadow-2xl rounded-2xl w-full max-w-5xl overflow-visible">
            <div class="grid md:grid-cols-2 gap-6">
                <figure class="flex items-center justify-center bg-base-200 p-6">
                    <img :src="reportImage" alt="report" class="object-contain w-full h-full max-h-96 rounded-xl" />
                </figure>
                <div class="card-body flex flex-col justify-between">
                    <div class="flex flex-col gap-6">
                        <h2 class="text-2xl font-bold text-primary">Generate Report</h2>
                        <p class="text-sm text-gray-500">Choose your preferred options to generate a detailed overtime
                            tracking report.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button"
                                class="flex gap-2 text-sm items-center hover:border-primary bg-base-300 py-2 px-4 rounded-md text-center">
                                {{ selectedMode ? selectedMode : 'Select Mode:' }}
                            </div>
                            <ul tabindex="0"
                                class="menu menu-sm dropdown-content bg-base-200 rounded-box z-1 mt-3 p-2 w-full shadow">
                                <li v-for="mode in modes" :key=mode.value>
                                    <label class="label capitalize">
                                        <input type="radio" class="radio radio-primary rounded-md" :value="mode.value"
                                            v-model="selectedMode" />
                                        {{ mode.label }}
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <div class="dropdown dropdown-end">
                            <div tabindex="0" role="button"
                                class="flex gap-2 text-sm items-center hover:border-primary bg-base-300 py-2 px-4 rounded-md text-center">
                                Select Year:
                            </div>
                            <ul tabindex="0"
                                class="menu menu-sm dropdown-content bg-base-200 rounded-box z-1 mt-3 p-2 w-full shadow max-h-64 overflow-auto">
                                <li v-for="year in years" :key=year.value>
                                    <label class="label">
                                        <input type="checkbox" class="checkbox checkbox-primary rounded-md"
                                            :value="year.value" v-model="selectedYear" />
                                        {{ year.label }}
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <div class="dropdown dropdown-end" :disabled="selectedMode === 'monthly' ? false : true">
                            <div tabindex="0" role="button"
                                class="flex gap-2 text-sm items-center hover:border-primary bg-base-300 py-2 px-4 rounded-md text-center">
                                Select Month:
                            </div>
                            <ul tabindex="0"
                                class="menu menu-sm dropdown-content bg-base-200 rounded-box z-1 mt-3 p-2 w-full shadow max-h-64 overflow-auto">
                                <li v-for="month in months" :key=month.value>
                                    <label class="label">
                                        <input type="checkbox" class="checkbox checkbox-primary rounded-md"
                                            :value="month.value" v-model="selectedMonth" />
                                        {{ month.label }}
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <div class="dropdown dropdown-end" :disabled="selectedMode === 'weekly' ? false : true">
                            <div tabindex="0" role="button"
                                class="flex gap-2 text-sm items-center hover:border-primary bg-base-300 py-2 px-4 rounded-md text-center">
                                Select Week:
                            </div>
                            <ul tabindex="0"
                                class="menu menu-sm dropdown-content bg-base-200 rounded-box z-1 mt-3 p-2 w-full shadow max-h-64 overflow-y-auto">
                                <li v-for="week in weeks" :key=week.value>
                                    <label class="label">
                                        <input type="checkbox" class="checkbox checkbox-primary rounded-md"
                                            :value="week.value" v-model="selectedWeek" />
                                        {{ week.label }}
                                    </label>
                                </li>
                            </ul>
                        </div>
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
    { label: 'Select Mode:', value: null },
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
