<template>
    <div class="stat bg-base-100 shadow-sm p-4 items-center justify-between">
        <div class="stat-title">{{ title }}</div>
        <div class="stat-value">{{ value }}</div>
    </div>
</template>

<script setup>

defineProps({
    title: {
        type: String,
        required: true
    },
    value: {
        type: String,
        required: true
    }
})

</script>