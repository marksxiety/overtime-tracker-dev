<template>

    <Head title="Report Generator" />
    <div class="container p-4">

        <div v-if="reportLoaded" class="space-y-6">

            <!-- Filters -->
            <div class="flex flex-row gap-4 justify-end">
                <TextInput type="date" v-model="selectedDateRange.start_date"
                    :message="selectedDateRange.errors?.start_date" />
                <TextInput type="date" v-model="selectedDateRange.end_date"
                    :message="selectedDateRange.errors?.end_date" />

                <div class="join w-full sm:w-auto">
                    <input class="join-item btn flex-1" type="radio" name="options" aria-label="Weekly" value="weekly"
                        v-model="selectedReportType" />
                    <input class="join-item btn flex-1" type="radio" name="options" aria-label="Monthly" value="monthly"
                        v-model="selectedReportType" />
                    <input class="join-item btn flex-1" type="radio" name="options" aria-label="Yearly" value="yearly"
                        v-model="selectedReportType" />
                </div>
            </div>

            <!-- Bar Graph -->
            <div class="card bg-base-100 shadow p-4 h-full">
                <h2 class="font-bold mb-2">Approved Overtime Hours by Time</h2>
                <div class="h-full flex items-center justify-center text-gray-400">
                    <div ref="totalOvertimeViaTimeGraph" class="min-h-[50vh] w-full"></div>
                </div>
            </div>

            <!-- Rankings + Declined Reasons -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="card bg-base-100 shadow p-4 h-64">
                    <h2 class="font-bold mb-2">Employee Rankings (Approved)</h2>
                    <div class="h-full flex items-center justify-center text-gray-400">
                        [Ranking Chart Placeholder]
                    </div>
                </div>
                <div class="card bg-base-100 shadow p-4 h-64">
                    <h2 class="font-bold mb-2">Declined/Disapproved Breakdown</h2>
                    <div class="h-full flex items-center justify-center text-gray-400">
                        [Declined Reasons Chart Placeholder]
                    </div>
                </div>
            </div>

            <!-- Raw Data Table -->
            <div class="card bg-base-100 shadow p-4 h-80">
                <h2 class="font-bold mb-2">Overtime Raw Data</h2>
                <div class="h-full flex items-center justify-center text-gray-400">
                    [Table Placeholder]
                </div>
            </div>

            <!-- AI Summary -->
            <div class="card bg-base-100 shadow p-4 h-40">
                <h2 class="font-bold mb-2">AI-Generated Summary</h2>
                <div class="h-full flex items-center justify-center text-gray-400">
                    [Markdown Summary Placeholder]
                </div>
            </div>

        </div>


        <div v-else class="flex flex-col items-center justify-center min-h-[80vh] bg-base-300 px-6 py-10">
            <div class="card bg-base-100 shadow-sm rounded-md w-full max-w-5xl overflow-visible border border-base-200">
                <div class="grid md:grid-cols-2 gap-8">
                    <figure class="flex items-center justify-center bg-base-200 p-8 rounded-l-3xl">
                        <img :src="reportImage" alt="report"
                            class="object-contain w-full h-full max-h-[15rem] drop-shadow-md" />
                    </figure>
                    <div class="card-body flex flex-col justify-center">
                        <h2 class="text-3xl font-extrabold text-primary tracking-tight">Generate Report</h2>
                        <p class="text-base text-gray-500 mt-2">Choose a date range to generate your report.</p>

                        <div v-if="isLoading" class="flex items-center gap-3 mt-4 text-primary">
                            <span class="loading loading-spinner loading-md"></span>
                            <span class="font-medium">{{ loadingMessage }}</span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                            <TextInput name="Start Date:" type="date" v-model="selectedDateRange.start_date"
                                :message="selectedDateRange.errors?.start_date" />
                            <TextInput name="End Date:" type="date" v-model="selectedDateRange.end_date"
                                :message="selectedDateRange.errors?.end_date" />
                        </div>

                        <div class="card-actions justify-end mt-8 gap-3 flex-wrap">
                            <button class="btn btn-neutral w-full md:w-auto shadow-sm" @click="handleClearState"
                                :disabled="isLoading">
                                RESET
                            </button>
                            <button class="btn btn-primary w-full md:w-auto shadow-md" @click="handleGenerateReport"
                                :disabled="isLoading">
                                GENERATE
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import reportImage from '../../images/generate-report.svg'
import TextInput from '../Components/TextInput.vue'
import { theme } from '../utils/themeStore.js'
import { getTailwindColor } from '../utils/tailwindColorIdentifier.js'
import * as echarts from 'echarts'

const isLoading = ref(false)
const loadingMessage = ref('Processing request...')
const reportLoaded = ref(true)
const selectedReportType = ref('weekly')

const totalOvertimeViaTime = ref({
    weeks: [
        'Week 15',
        'Week 16',
        'Week 17',
        'Week 18',
        'Week 19',
        'Week 20',
        'Week 21',
        'Week 22',
        'Week 23',
        'Week 24',
        'Week 25'
    ],
    totalHours: [10, 52, 200, 400, 390, 280, 310, 420, 360, 295, 330],
    roa: [100, 250, 320, 300, 350, 310, 300, 400, 370, 320, 340]
})


const totalOvertimeViaTimeGraph = ref(null)
let totalOvertimeViaTimeGraphInstance = null

function rendertotalOvertimeViaTimeGraph(currTheme = theme.value) {
    if (!totalOvertimeViaTimeGraph.value) return

    if (totalOvertimeViaTimeGraphInstance) {
        totalOvertimeViaTimeGraphInstance.dispose()
    }

    if (currTheme === 'dark') {
        totalOvertimeViaTimeGraphInstance = echarts.init(totalOvertimeViaTimeGraph.value, 'dark')
    } else {
        totalOvertimeViaTimeGraphInstance = echarts.init(totalOvertimeViaTimeGraph.value)
    }

    let bgColor = getTailwindColor('bg-base-100')
    const option = {
        tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
        grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
        xAxis: [
            {
                type: 'category',
                data: totalOvertimeViaTime.value.weeks,
                axisTick: { alignWithLabel: true }
            }
        ],
        yAxis: [{ type: 'value' }],
        series: [
            {
                name: 'Total Hours',
                type: 'bar',
                barWidth: '60%',
                data: totalOvertimeViaTime.value.totalHours.map((val, idx) => {
                    let limit = totalOvertimeViaTime.value.roa[idx]
                    return {
                        value: val,
                        itemStyle: {
                            color: val > limit ? 'red' : '#5470c6'
                        }
                    }
                })
            },
            {
                name: 'ROA',
                type: 'line',
                smooth: true,
                data: totalOvertimeViaTime.value.roa
            }
        ]
    }
    totalOvertimeViaTimeGraphInstance.setOption(option)
}

const selectedDateRange = useForm({
    start_date: null,
    end_date: null
})

const props = defineProps({
    requests: Object,
    errors: Object,
    flash: Object,
    auth: Object,
})

const handleClearState = () => {
    selectedDateRange.start_date = ''
    selectedDateRange.end_date = ''
}

const handleGenerateReport = () => {
    isLoading.value = true

    setTimeout(() => {
        loadingMessage.value = 'This may take a while depending on date range. Kindly wait...'
    }, 5000);

    selectedDateRange.get(route('approver.generate.report.daterange'), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            reportLoaded.value = true
        },
        onError: (errors) => {
            console.log("Validation errors:", errors)
        },
        onFinish: () => {
            console.log("Reactive errors:", selectedDateRange.errors)
            isLoading.value = false
        }
    })
}

onMounted(() => {
    rendertotalOvertimeViaTimeGraph()
})
</script>