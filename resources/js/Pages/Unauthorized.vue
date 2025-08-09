<template>
    <div class="hero bg-base-300 min-h-screen">
        <div class="hero-content text-center">
            <div class="max-w-md">
                <h1 class="text-5xl font-bold">404 Not Found</h1>
                <p class="py-6">The page cannot be found.</p>
                <Link :href="route('main')" class="btn btn-neutral">Return to Home</Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>