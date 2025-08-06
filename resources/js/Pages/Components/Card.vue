<template>
    <component :is="routename ? Link : 'div'" :href="routename || undefined" :class="[
        'stat bg-base-100 shadow',
        routename ? 'hover:bg-primary hover:cursor-pointer transition-colors duration-200' : ''
    ]">
        <div class="stat-title">{{ title }}</div>
        <div class="stat-value">{{ value }}</div>
    </component>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    title: String | Number,
    value: String | Number,
    routename: String
})
</script>