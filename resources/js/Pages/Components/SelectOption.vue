<template>
    <div class="mb-4">
        <label for="role" class="block mb-2">{{ name }}</label>
        <select id="role" v-model="model" class="w-full px-4 py-2 select">
            <option selected value="">Choose a role</option>
            <option value="employee">Employee</option>
            <option value="approver">Approver</option>
            <option value="admin">Admin</option>
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
})

const model = defineModel({
    type: null,
    required: true
})
</script>