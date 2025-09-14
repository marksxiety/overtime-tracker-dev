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

            <!-- Summary Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

                <!-- Approved Overtime Hours -->
                <div class="stat bg-base-100 text-success-content rounded-lg shadow">
                    <div class="stat-title">Approved Overtime</div>
                    <div class="stat-value text-success">{{ card.filed }}h</div>
                    <div class="stat-desc">Filed Requests hours</div>
                </div>

                <!-- Pending Requests -->
                <div class="stat bg-base-100 text-warning-content rounded-lg shadow">
                    <div class="stat-title">Pending Requests</div>
                    <div class="stat-value text-warning">{{ card.pending }}</div>
                    <div class="stat-desc">Waiting for approval</div>
                </div>

                <!-- Tentative Overtime Hours (Approved + Pending) -->
                <div class="stat bg-base-100 text-info-content rounded-lg shadow">
                    <div class="stat-title">Tentative Overtime</div>
                    <div class="stat-value text-info">{{ card.tentative }}h</div>
                    <div class="stat-desc">Approved + pending hours</div>
                </div>

                <!-- Total Filed Requests -->
                <div class="stat bg-base-100 text-primary-content rounded-lg shadow">
                    <div class="stat-title">Total Overtime Requests</div>
                    <div class="stat-value text-primary">{{ card.requests }}</div>
                    <div class="stat-desc">All requests from employees</div>
                </div>
            </div>


            <!-- Graphs -->
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                <div class="col-span-2 card bg-base-100 shadow p-4 h-full">
                    <h2 class="font-bold mb-2">Consumed Overtime</h2>
                    <div class="h-full flex items-center justify-center text-gray-400">
                        <div ref="totalOvertimeViaTimeGraph" class="min-h-[50vh] w-full"></div>
                    </div>
                </div>
                <div class="col-span-1 card bg-base-100 shadow p-4 h-full">
                    <h2 class="font-bold mb-2">Employee Rankings</h2>
                    <div class="h-full flex items-center justify-center text-gray-400">
                        <div ref="totalOvertimeViaEmployeeChart" class="min-h-[50vh] w-full"></div>
                    </div>
                </div>
            </div>

            <!-- AI Summary -->
            <div class="card bg-base-100 shadow p-4 h-40">
                <h2 class="font-bold mb-2">AI-Generated Summary</h2>
                <div class="h-full flex items-center justify-center text-gray-400">
                    <button class="btn btn-primary">Generate
                        <Icon icon="mingcute:ai-line" width="24" height="24" />
                    </button>
                </div>
            </div>

            <!-- Request Table -->
            <div class="card bg-base-100 shadow p-4 h-80">
                <h2 class="font-bold mb-2">Overtime Request Details</h2>
                <div class="h-full flex items-center justify-center text-gray-400">
                    [Table Placeholder]
                </div>
            </div>

        </div>

        <!-- Empty State -->
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
import { watch, ref, nextTick } from 'vue'
import { useForm } from '@inertiajs/vue3'
import reportImage from '../../images/generate-report.svg'
import TextInput from '../Components/TextInput.vue'
import { theme } from '../utils/themeStore.js'
import { getTailwindColor } from '../utils/tailwindColorIdentifier.js'
import * as echarts from 'echarts'
import { Icon } from "@iconify/vue"

const isLoading = ref(false)
const loadingMessage = ref('Processing request...')
const reportLoaded = ref(false)
const selectedReportType = ref('weekly')

const totalOvertimeViaTime = ref({})

const card = ref({
    filed: 0,
    pending: 0,
    tentative: 0,
    requests: 0
})

const totalOvertimeViaEmployee = ref({
    names: [
        'user1',
        'user2',
        'user3',
        'user4',
        'user5',
        'user6',
    ],
    totalHours: [60, 50, 40, 30, 20, 10]
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

    // --- Sort weeks with corresponding values ---
    let data = totalOvertimeViaTime.value.weeks.map((week, idx) => ({
        week,
        hours: totalOvertimeViaTime.value.totalHours[idx],
        roa: totalOvertimeViaTime.value.roa[idx]
    }))

    // ascending order by week
    data.sort((a, b) => a.week - b.week)

    const option = {
        backgroundColor: bgColor,
        tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
        grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
        xAxis: [
            {
                type: 'category',
                data: data.map(d => `Week ${d.week}`),
                axisTick: { alignWithLabel: true }
            }
        ],
        yAxis: [{ type: 'value' }],
        series: [
            {
                name: 'Total Hours',
                type: 'bar',
                barWidth: '60%',
                data: data.map(d => ({
                    value: d.hours,
                    itemStyle: {
                        color: d.hours > d.roa ? 'red' : '#5470c6'
                    }
                }))
            },
            {
                name: 'ROA',
                type: 'line',
                smooth: true,
                data: data.map(d => d.roa)
            }
        ]
    }

    totalOvertimeViaTimeGraphInstance.setOption(option)
}


const totalOvertimeViaEmployeeChart = ref(null)
let totalOvertimeViaEmployeeInstance = null

function rendertotalOvertimeViaEmployeeGraph(currTheme = theme.value) {
    if (!totalOvertimeViaEmployeeChart.value) return

    if (totalOvertimeViaEmployeeInstance) {
        totalOvertimeViaEmployeeInstance.dispose()
    }

    if (currTheme === 'dark') {
        totalOvertimeViaEmployeeInstance = echarts.init(totalOvertimeViaEmployeeChart.value, 'dark')
    } else {
        totalOvertimeViaEmployeeInstance = echarts.init(totalOvertimeViaEmployeeChart.value)
    }

    let bgColor = getTailwindColor('bg-base-100')

    // --- Sort employees by total hours (descending) ---
    let employees = totalOvertimeViaEmployee.value.employees.map((id, idx) => ({
        id,
        name: totalOvertimeViaEmployee.value.names[idx],
        hours: totalOvertimeViaEmployee.value.totalHours[idx]
    }))

    employees.sort((a, b) => b.hours - a.hours)

    const option = {
        backgroundColor: bgColor,
        tooltip: { trigger: 'axis', axisPointer: { type: 'shadow' } },
        grid: { left: '3%', right: '4%', bottom: '3%', containLabel: true },
        xAxis: { type: 'value' },
        yAxis: {
            type: 'category',
            data: employees.map(e => e.name),
            axisTick: { alignWithLabel: true },
            axisLabel: {
                formatter: function (value) {
                    return value.length > 4 ? value.slice(0, 4) + '…' : value
                }
            },
            inverse: true
        },
        series: [
            {
                name: 'Total Hours',
                type: 'bar',
                barWidth: '60%',
                data: employees.map(e => e.hours),
                label: {
                    show: true,
                    position: 'inside',
                    formatter: '{c}h',
                    color: '#fff',
                }
            }
        ]
    }

    totalOvertimeViaEmployeeInstance.setOption(option)
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

function handleDataManipulationViaReportType(data) {

    // filter and compute data according to status to populate the value in cards
    card.value.filed = data.list.filter(req => req.status === 'FILED').reduce((sum, req) => sum + req.hours, 0)
    card.value.pending = data.list.filter(req => req.status === 'PENDING').length
    card.value.tentative = data.list.filter(req => req.status === 'PENDING' || req.status === 'APPROVED').reduce((sum, req) => sum + req.hours, 0)
    card.value.requests = data.list.filter(req => req.status !== 'CANCELED' && req.status !== 'DECLINED').length

    let type = selectedReportType.value

    let computedConsumedOvertime = {}
    let computedEmployeeRankings = {}

    if (type === 'weekly') {
        // initialize structures
        computedConsumedOvertime.weeks = []
        computedConsumedOvertime.totalHours = []

        computedEmployeeRankings.employees = []
        computedEmployeeRankings.names = []
        computedEmployeeRankings.totalHours = []

        // Aggregate data
        data.list.forEach(element => {

            // ----- Weekly Overtime -----
            if (!computedConsumedOvertime.weeks.includes(element.week)) {
                computedConsumedOvertime.weeks.push(element.week)
            }

            let weekIndex = computedConsumedOvertime.weeks.indexOf(element.week)
            if (computedConsumedOvertime.totalHours[weekIndex] === undefined) {
                computedConsumedOvertime.totalHours[weekIndex] = 0
            }
            computedConsumedOvertime.totalHours[weekIndex] += element.hours

            // ----- Employee Rankings -----
            let idx = computedEmployeeRankings.employees.indexOf(element.user_id)
            if (idx === -1) {
                computedEmployeeRankings.employees.push(element.user_id)
                computedEmployeeRankings.names.push(element.user_name) // for display
                computedEmployeeRankings.totalHours.push(0)
                idx = computedEmployeeRankings.employees.length - 1
            }
            computedEmployeeRankings.totalHours[idx] += element.hours
        })

        // Populate ROA aligned with weeks
        computedConsumedOvertime.roa = computedConsumedOvertime.weeks.map(week => {
            let match = data.required_hours.find(e => e.week === week)
            return match ? match.required_hours : 0
        })
    }

    totalOvertimeViaTime.value = computedConsumedOvertime
    totalOvertimeViaEmployee.value = computedEmployeeRankings

    nextTick(() => {
        rendertotalOvertimeViaTimeGraph()
        rendertotalOvertimeViaEmployeeGraph()
    })
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
            handleDataManipulationViaReportType(props?.requests)
        },
        onError: (errors) => {
            console.log("Validation errors:", errors)
        },
        onFinish: () => {
            isLoading.value = false
        }
    })
}

watch(theme, (newTheme) => {
    if (!newTheme) return
    rendertotalOvertimeViaTimeGraph(newTheme)
}, { immediate: true })

</script>