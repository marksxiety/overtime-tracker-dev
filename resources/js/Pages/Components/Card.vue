<template>
    <div class="card rounded bg-base-100 shadow-sm p-4 flex flex-row items-center justify-between">
        <span class="text-sm font-semibold opacity-80">{{ label }}</span>
        <span class="text-2xl font-extrabold">{{ value }} hrs</span>
    </div>
</template>

<script setup>

defineProps({
    label: {
        type: String,
        required: true
    },
    value: {
        type: String,
        required: true
    }
})

</script>