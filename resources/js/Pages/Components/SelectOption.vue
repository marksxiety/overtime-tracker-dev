<template>
    <div class="mb-4">
        <label for="role" class="block mb-2">{{ name }}</label>
        <select id="role" v-model="model" class="w-full px-4 py-2 select">
            <option v-for="option in options" :key="option.value" :value="option.value">
                {{ option.label }}
            </option>
        </select>
        <p v-if="message" class="mt-1 text-sm text-red-600 bg-red-50 px-2 py-1 rounded text-center">
            {{ message }}
        </p>
    </div>
</template>

<script setup>
defineProps({
    name: {
        type: String
    },
    message: String,
    options: Array
})

const model = defineModel({
    type: null,
    required: true
})
</script>