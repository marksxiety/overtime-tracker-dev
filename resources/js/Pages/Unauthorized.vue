<template>
    <div class="flex flex-col items-center justify-center min-h-screen bg-gray-100">
        <h1 class="text-4xl font-bold text-red-600 mb-4">403 - Unauthorized</h1>
        <p class="text-gray-700 text-lg mb-6">
            You do not have permission to access this page.
        </p>
        <Link href="/" class="text-blue-600 hover:underline">
        ← Go back to Home
        </Link>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
