<template>
    <div class="flex flex-col">
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 flex flex-row border-2 justify-between rounded p-4 bg-base-200">
                <span class="text-md font-semibold">Total Overtime Hours</span>
                <span class="text-2xl font-extrabold">45</span>
            </div>
            <div class="col-span-1 flex flex-row border-2 justify-between rounded p-4 bg-base-200">
                <span class="text-md font-semibold">Pending Requests</span>
                <span class="text-2xl font-extrabold">2</span>
            </div>
        </div>
        <div class="flex mt-4 justify-end gap-2">
            <Link :href="route('request')" class="btn btn-neutral">
            File New Request
            </Link>
            <Link :href="route('request')" class="btn btn-neutral">
            Manage Schedules
            </Link>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3' 
</script>