<template>
    <div class="h-24 bg-blue-200">
        <div class="flex gap-4 items-center text-center">
            <Link :href="route('main')">Home</Link>
            <Link :href="route('about')">About</Link>
        </div>
    </div>
    <main>
        <slot></slot>
    </main>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>