<template>
    <div class="toast toast-top toast-end fixed z-50">
        <div v-for="(toast, i) in toasts" :key="i" class="alert" :class="{
            'alert-success': toast.type === 'success',
            'alert-error': toast.type === 'error',
            'alert-info': toast.type === 'info',
            'alert-warning': toast.type === 'warning'
        }">
            {{ toast.message }}
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const toasts = ref([])

function showToast(message, type = 'info') {
    toasts.value.push({ message, type })
    setTimeout(() => {
        toasts.value.shift()
    }, 3000)
}

defineExpose({ showToast })
</script>