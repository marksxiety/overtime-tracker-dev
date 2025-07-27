<template>
    <div class="flex flex-col gap-6 h-full">
        <div class="breadcrumbs text-sm">
            <ul>
                <li>
                    <Link :href="route('main')">Home</Link>
                </li>
                <li>
                    <Link :href="route('request')">Overtime Request</Link>
                </li>
            </ul>
        </div>
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Overtime Request</h1>
        </div>
        <hr>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>