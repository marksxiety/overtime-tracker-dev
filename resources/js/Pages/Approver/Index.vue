<template>
    <div class="flex flex-col gap-6 p-4">
        <!-- Stat Cards -->
        <div class="stats stats-horizontal shadow flex-wrap">
            <Card title="Total Approved" value="12" />
            <Card title="Pending Approvals" value="3" />
            <Card title="Required Hours Left" value="16 hrs" />
            <Card title="Total Filed Requests" value="18" />
        </div>

        <!-- Weekly Overview -->
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">Week 32 Overview</h2>
                    <p>You're halfway through the week. 6 out of 9 employees have submitted their overtime requests. The
                        team has logged 24 out of 40 required hours.</p>
                </div>
            </div>

            <!-- Weekly Hours Progress -->
            <div class="col-span-1 card bg-base-100 shadow">
                <div class="card-body">
                    <h2 class="card-title">Weekly Hours Progress</h2>
                    <progress class="progress progress-primary w-full" value="24" max="40"></progress>
                    <p class="text-sm text-right mt-1">24 / 40 hrs consumed</p>
                </div>
            </div>
        </div>

        <!-- Pending Approvals Table -->
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <div class="flex justify-between mb-4">
                    <h2 class="card-title">Pending Approvals</h2>
                    <div>
                        <!-- Filter Controls -->
                        <div class="flex flex-wrap gap-4 items-center">
                            <div>
                                <select class="select select-bordered w-32">
                                    <option value="">All</option>
                                    <option value="">Week 1</option>
                                    <option value="">Week 2</option>
                                </select>
                            </div>

                            <div>
                                <input type="text" class="input input-bordered w-64" placeholder="e.g. Jane Doe" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto max-h-[50vh]">
                    <table class="table table-zebra w-full">
                        <thead class="sticky top-0 bg-base-300 z-10 rounded">
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Week</th>
                                <th>Hours</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(req, index) in pendingRequests" :key="index">
                                <td>{{ req.employee }}</td>
                                <td>{{ req.date }}</td>
                                <td>{{ req.week }}</td>
                                <td>{{ req.hours }}</td>
                                <td>{{ req.reason }}</td>
                                <td class="flex gap-2">
                                    <button class="btn btn-xs text-sm btn-primary">Manage</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Card from '../Components/Card.vue'

const pendingRequests = [
    {
        employee: 'Jane Doe',
        date: '2025-08-01',
        week: 31,
        hours: '2.5 hrs',
        reason: 'System upgrade testing',
    },
    {
        employee: 'John Smith',
        date: '2025-08-02',
        week: 31,
        hours: '3 hrs',
        reason: 'Machine downtime recovery',
    },
    {
        employee: 'Emily Cruz',
        date: '2025-08-02',
        week: 31,
        hours: '1.5 hrs',
        reason: 'Backlog report catching up',
    },
    {
        employee: 'Michael Tan',
        date: '2025-08-03',
        week: 31,
        hours: '2 hrs',
        reason: 'Manual inspections',
    },
    {
        employee: 'Sarah Lim',
        date: '2025-08-03',
        week: 31,
        hours: '4 hrs',
        reason: 'Inventory cleanup',
    },
    {
        employee: 'Ryan Gomez',
        date: '2025-08-03',
        week: 31,
        hours: '3.5 hrs',
        reason: 'Urgent labeling fix',
    },
    {
        employee: 'Alice Reyes',
        date: '2025-08-04',
        week: 32,
        hours: '2 hrs',
        reason: 'Missed scheduled task',
    },
    {
        employee: 'Brandon Lee',
        date: '2025-08-04',
        week: 32,
        hours: '1.5 hrs',
        reason: 'Tool calibration delay',
    },
    {
        employee: 'Jessica Yu',
        date: '2025-08-05',
        week: 32,
        hours: '2.25 hrs',
        reason: 'Documentation update',
    },
    {
        employee: 'Henry Villanueva',
        date: '2025-08-05',
        week: 32,
        hours: '3 hrs',
        reason: 'Unplanned quality recheck',
    },
    {
        employee: 'Diana Santos',
        date: '2025-08-05',
        week: 32,
        hours: '2 hrs',
        reason: 'Missed outgoing shipment',
    },
    {
        employee: 'Joshua Chan',
        date: '2025-08-06',
        week: 32,
        hours: '2.75 hrs',
        reason: 'Extended batch process',
    },
    {
        employee: 'Camille Dizon',
        date: '2025-08-06',
        week: 32,
        hours: '1 hr',
        reason: 'Late quality inspection',
    },
    {
        employee: 'Victor Ong',
        date: '2025-08-07',
        week: 32,
        hours: '3 hrs',
        reason: 'Missed tool maintenance',
    },
    {
        employee: 'Alyssa Navarro',
        date: '2025-08-07',
        week: 32,
        hours: '1.25 hrs',
        reason: 'Extra shift handover',
    },
]

</script>
