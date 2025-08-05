<template>
    <dialog class="modal" ref="dialog">
        <div class="modal-box  h-auto max-h-[85vh] overflow-auto w-2/5 max-w-5xl">
            <slot></slot>
        </div>
    </dialog>
</template>

<script setup>
import { ref, defineExpose } from 'vue'

const dialog = ref(null)

const open = () => dialog.value?.showModal()
const close = () => dialog.value?.close()

defineExpose({ open, close })
</script>