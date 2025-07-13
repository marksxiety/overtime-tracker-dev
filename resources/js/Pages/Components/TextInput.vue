<template>
    <div class="mb-4">
        <label class="block text-gray-700 mb-2" for="name">{{ name }}</label>
        <input :id="name" :type="type" v-model="model" :class="[
            'w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2',
            message
                ? 'border-red-500 focus:ring-red-200'
                : 'border-gray-300 focus:ring-blue-200'
        ]" :placeholder="placeholder" />
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
    type: {
        type: String,
        default: 'text'
    },
    message: String,
    placeholder: {
        type: String,
        default: 'Enter your input'
    }
})

const model = defineModel({
    type: null,
    required: true
})

</script>