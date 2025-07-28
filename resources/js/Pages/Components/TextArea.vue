<template>
    <div class="mb-4">
        <label class="block mb-2" for="name">{{ name }}</label>
        <textarea class="textarea" :id="name" :type="type" v-model="model"
            :class="['w-full px-4 py-2 input', message ? 'border-red-500 focus:ring-red-200' : 'border-gray-300 focus:ring-blue-200', textCase]"
            :placeholder="placeholder"></textarea>
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
    type: {
        type: String,
        default: 'text'
    },
    message: String,
    placeholder: {
        type: String,
        default: 'Enter your input'
    },
    textCase: {
        type: String,
        default: ''
    }
})

const model = defineModel({
    type: null,
    required: true
})

</script>