<template>
    <div class="stat bg-base-100 shadow">
        <div class="stat-title">{{ title }}</div>
        <div class="stat-value">{{ value }}</div>
    </div>
</template>

<script setup>
defineProps({
    title: String | Number,
    value: String | Number
})
</script>