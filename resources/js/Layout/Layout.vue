<template>
    <div class="flex justify-between bottom-2 shadow-md h-full p-4">
         <Link :href="route('main')" class="font-bold text-sm">Overtime Tracker</Link>
         <div class="flex justify-between gap-4 font-semibold">
            <p class="text-sm hover:underline hover:cursor-pointer">Dashboard</p>
            <p class="text-sm hover:underline hover:cursor-pointer">Requests</p>
            <p class="text-sm hover:underline hover:cursor-pointer">Reports</p>
            <p class="text-sm hover:underline hover:cursor-pointer">Profile</p>
         </div>
    </div>
    <main class="m-4 pr-20 pl-20 pt-4 pb-4 f-full max-h-screen">
        <slot></slot>
    </main>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>