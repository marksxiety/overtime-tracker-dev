<template>
    <div class="flex flex-col gap-6 p-4 h-screen">
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
            <SelectOption :options="weeks" v-model="selectedWeek" margin='' @change="handleWeekSelection()" />
            <SelectOption :options="years" v-model="selectedYear" margin='' @change="handleWeekSelection()" />
        </div>
    </div>
</template>

<script setup>
import { ref, inject, watch } from 'vue'
import Card from '../Components/Card.vue'
import SelectOption from '../Components/SelectOption.vue'
import { weeks, years } from '../utils/dropdownOptions.js'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    info: Object,
    success: Boolean,
    message: String
})

const card = ref({
    total_requests: props?.info?.totals?.total_requests ?? 0,
    total_approved: props?.info?.totals?.total_approved ?? 0,
    total_pending: props?.info?.totals?.total_pending ?? 0,
    required_hours: props?.info?.totals?.required_hours ?? 0,
    total_filed: props?.info?.totals?.total_filed ?? 0,
    total_hours: props?.info?.totals?.total_hours ?? 0,
})

const selectedWeek = ref(props?.info?.payload?.week)
const selectedYear = ref(props?.info?.payload?.year)


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

</script>