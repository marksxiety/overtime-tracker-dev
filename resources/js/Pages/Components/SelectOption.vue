<template>
    <div class="mb-4">
        <label v-if="name" for="role" class="block mb-2">{{ name }}</label>
        <select id="role" v-model="model"
            :class="['w-full px-4 py-2 input', message ? 'border-red-500 focus:ring-red-200' : 'focus:ring-blue-200', 'w-full px-4 py-2 select']">
            <option v-for="option in options" :key="option.value" :value="option.value" class="text-center">
                {{ option.label }}
            </option>
        </select>
        <p v-if="message" class="mt-1 text-sm text-red-600 px-2 py-1 text-center">
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