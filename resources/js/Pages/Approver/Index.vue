<template>
    <div class="flex flex-col gap-6 p-4 min-h-screen max-h-auto">
        <!-- Stat Cards -->
        <div class="stats stats-horizontal shadow flex-wrap">
            <Card title="Total Filed" :value="card.total_filed" />
            <Card title="For Filing" :value="card.total_approved" routename="overtime.filing"
                :parameters="{ status: 'APPROVED', page: 'Approver/Filing' }" />
            <Card title="Pending Approvals" :value="card.total_pending" routename="overtime.pending"
                :parameters="{ status: 'PENDING', page: 'Approver/Pending' }" />
            <Card title="Total Requests" :value="card.total_requests" />
            <Card title="ROA Hours Left" :value="((card.required_hours - card.total_hours) % 1 === 0
                ? (card.required_hours - card.total_hours).toFixed(0)
                : (card.required_hours - card.total_hours).toFixed(2)) + ' hrs'" />
        </div>

        <!-- Weekly Overview -->
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">Week {{ selectedWeek }} Overtime Overview</h2>
                    <p>
                        In Week {{ selectedWeek }}, a total of {{ card.total_requests }} overtime requests were
                        submitted,
                        with {{ card.total_approved }} approved and {{ card.total_pending }} still pending review.
                        Employees filed for {{ card.total_filed }} hours, contributing {{ card.total_hours }} actual
                        hours of overtime.
                        The overtime limit for the week was {{ card.required_hours }} hours.
                    </p>
                </div>
            </div>

            <!-- Weekly Hours Progress -->
            <div class="col-span-1 card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">Weekly Overtime Usage</h2>
                    <progress class="progress progress-primary w-full" :value="card.total_hours ?? 0"
                        :max="card.required_hours ?? 0">
                    </progress>

                    <p class="text-sm text-right mt-1">
                        {{ (card.total_hours ?? 0) }} / {{ card.required_hours }} hrs consumed
                    </p>

                </div>
            </div>
        </div>
        <div class="flex flex-row flex-end gap-4 w-1/4">
            <SelectOption :options="years" v-model="selectedYear" margin='' @change="handleWeekSelection()" />
            <SelectOption :options="weeks" v-model="selectedWeek" margin='' @change="handleWeekSelection()" />
        </div>
        <div class="grid grid-cols-3 gap-4">
            <div class="col-span-2">
                <div ref="overtimeWeeklyBarGraph" class="min-h-[50vh] w-full"></div>
            </div>
            <div class="col-span-1">
                <div ref="overtimeRequestStatus" class="min-h-[50vh] w-full"></div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, inject, watch, onMounted, computed } from 'vue'
import Card from '../Components/Card.vue'
import SelectOption from '../Components/SelectOption.vue'
import { weeks, years } from '../utils/dropdownOptions.js'
import { router } from '@inertiajs/vue3'
import * as echarts from 'echarts';

const props = defineProps({
    info: Object,
    success: Boolean,
    message: String
})

console.log(props?.info?.result?.requests)

const card = ref({
    total_requests: props.info?.result?.totals.TOTAL_REQUESTS ?? 0,
    total_approved: props.info?.result?.totals.APPROVED ?? 0,
    total_pending: props.info?.result?.totals.PENDING ?? 0,
    total_filed: props.info?.result?.totals.FILED ?? 0,
    required_hours: props?.info?.result?.required_hours ?? 0,
    total_hours: props?.info?.totals?.total_hours ?? 0,
})

const selectedWeek = ref(props?.info?.payload?.week)
const selectedYear = ref(props?.info?.payload?.year)
const pieData = ref({ ...props?.info?.result?.requests } ?? {})

const pieSeriesData = computed(() => {
    return Object.entries(pieData.value).map(([status, data]) => ({
        name: capitalize(status.toLowerCase()),
        value: data.value,
        remarks: data.remarks ?? [],
    }))
})


// Helper function (optional)
function capitalize(str) {
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase()
}
// ===== Watchers =====

watch(() => props?.info?.totals, (updatedTotals) => {
    Object.assign(card.value, updatedTotals)
})

watch(() => props.info.payload.week, (newWeek) => {
    selectedWeek.value = newWeek
})

watch(() => props.info.payload.year, (newYear) => {
    selectedYear.value = newYear
})



const handleWeekSelection = () => {
    router.get(route('main'), {
        year: selectedYear.value,
        week: selectedWeek.value
    }, {
        preserveState: true
    })
}

function getTailwindColor(className) {
    const div = document.createElement('div')
    div.className = className
    div.style.display = 'none'
    document.body.appendChild(div)
    const color = getComputedStyle(div).backgroundColor
    document.body.removeChild(div)
    return color
}

const bgColor = getTailwindColor('bg-base-300')


const overtimeWeeklyBarGraph = ref(null);
let BarchartInstance = null;
function displayOvertimeWeeklyBarGraph() {
    if (!overtimeWeeklyBarGraph.value) return

    if (BarchartInstance) {
        BarchartInstance.dispose()
    }

    BarchartInstance = echarts.init(overtimeWeeklyBarGraph.value, 'dark')

    const option = {
        backgroundColor: bgColor,
        title: {
            text: 'Daily Overtime Breakdown'
        },
        tooltip: {
            trigger: 'axis',
            axisPointer: { type: 'shadow' }
        },
        legend: {
            data: [
                'Alice',
                'Bob',
                'Charlie',
                'David',
                'Eve',
                'Frank',
                'Grace',
                'Heidi',
                'Ivan'
            ]
        },
        xAxis: {
            type: 'category',
            data: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
        },
        yAxis: {
            type: 'value',
            name: 'Overtime Hours'
        },
        series: [
            {
                name: 'Alice',
                type: 'bar',
                stack: 'total',
                data: [10, 12, 8, 15, 13, 11, 10]
            },
            {
                name: 'Bob',
                type: 'bar',
                stack: 'total',
                data: [9, 10, 7, 12, 11, 10, 9]
            },
            {
                name: 'Charlie',
                type: 'bar',
                stack: 'total',
                data: [8, 9, 6, 11, 10, 9, 8]
            },
            {
                name: 'David',
                type: 'bar',
                stack: 'total',
                data: [7, 8, 5, 10, 9, 8, 7]
            },
            {
                name: 'Eve',
                type: 'bar',
                stack: 'total',
                data: [6, 7, 4, 9, 8, 7, 6]
            },
            {
                name: 'Frank',
                type: 'bar',
                stack: 'total',
                data: [5, 6, 3, 8, 7, 6, 5]
            },
            {
                name: 'Grace',
                type: 'bar',
                stack: 'total',
                data: [4, 5, 3, 7, 6, 5, 4]
            },
            {
                name: 'Heidi',
                type: 'bar',
                stack: 'total',
                data: [3, 4, 2, 6, 5, 4, 3]
            },
            {
                name: 'Ivan',
                type: 'bar',
                stack: 'total',
                data: [2, 3, 2, 5, 4, 3, 2]
            },
            {
                name: 'Total',
                type: 'line',
                data: [54, 64, 40, 93, 83, 73, 64],
                smooth: true
            },
            {
                name: 'ROA',
                type: 'line',
                data: [100, 120, 100, 120, 100, 120, 100],
                smooth: true,
                lineStyle: {
                    type: 'dashed',
                }
            }
        ]
    }
    BarchartInstance.setOption(option);
}


const overtimeRequestStatus = ref(null);
let PieChartInstance = null;
function displayOvertimeRequestStatus() {
    if (!overtimeRequestStatus.value) return

    if (PieChartInstance) {
        PieChartInstance.dispose()
    }

    PieChartInstance = echarts.init(overtimeRequestStatus.value, 'dark')

    let option = {
        backgroundColor: bgColor,
        title: {
            text: 'Overtime Requests',
            left: 'center'
        },
        tooltip: {
            trigger: 'item',
            formatter: function (params) {
                let extraInfo = ''
                if (Array.isArray(params.data.remarks)) {
                    extraInfo = params.data.remarks.map((item) => `• ${item}`).join('<br/>')
                } else {
                    extraInfo = params.data.remarks || ''
                }

                return `
        <strong>${params.name}</strong><br/>
        Count: ${params.value}<br/>
        Percentage: ${params.percent}%<br/>
        ${extraInfo ? '<br/><u>Remarks:</u><br/>' + extraInfo : ''}
      `
            }
        },
        legend: {
            top: 'bottom'
        },
        series: [
            {
                name: 'Status',
                type: 'pie',
                radius: '50%',
                data: pieSeriesData.value,
                label: {
                    show: true,
                    color: '#808080',
                    fontSize: 14
                }
            }
        ]
    }

    PieChartInstance.setOption(option);
}

onMounted(() => {
    displayOvertimeWeeklyBarGraph()
    displayOvertimeRequestStatus()
})
</script>