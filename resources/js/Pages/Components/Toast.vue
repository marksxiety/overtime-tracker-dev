<template>
  <div class="toast toast-top toast-end fixed z-50 mt-6 mr-4">
    <div
      v-for="(toast, i) in toasts"
      :key="i"
      class="alert border-2 rounded"
      :class="[
        toast.type === 'success' ? 'alert-success border-success' :
        toast.type === 'error' ? 'alert-error border-error' :
        toast.type === 'info' ? 'alert-info border-info' :
        toast.type === 'warning' ? 'alert-warning border-warning' :
        ''
      ]"
    >
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
    }, 5000)
}

defineExpose({ showToast })
</script>