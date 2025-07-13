<template>
    <div class="h-24 bg-blue-200">
        <div class="flex gap-4 items-center text-center">
            <Link href="/">Home</Link>
            <Link href="/">About
            </Link>
        </div>
    </div>
    <main>
        <slot></slot>
    </main>
</template>

<script setup>
import { Link, Head } from '@inertiajs/vue3';
</script>