<template>
    <div class="max-w-md mx-auto bg-white p-8 rounded-xl shadow mt-10">
        <h2 class="text-2xl font-bold mb-6 text-blue-600">Register User</h2>
        <form @submit.prevent="submitForm">
            <TextInput name="Name:" :message="form.errors.name" v-model="form.name" />
            <TextInput name="Email:" type="email" :message="form.errors.email" v-model="form.email" />
            <TextInput name="Employee ID:" type="text" :message="form.errors.employeeid" v-model="form.employeeid" />
            <SelectOption name="Choose a role:" :message="form.errors.role" v-model="form.role" />
            <TextInput name="Password:" type="password" :message="form.errors.password" v-model="form.password" />
            <TextInput name="Confirm Password" type="password" v-model="form.password_confirmation" />
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition"
                :disabled="form.processing">
                <span v-if="form.processing">Processing...</span>
                <span v-else>Register</span>
            </button>
        </form>
        <div class="mt-6 text-center"> Already have an account?
            <Link :href="route('login')" class="text-blue-600 hover:underline">
            Login here
            </Link>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import TextInput from '../Components/TextInput.vue'
import SelectOption from '../Components/SelectOption.vue'

const form = useForm({
    name: '',
    email: '',
    employeeid: '',
    role: '',
    password: '',
    password_confirmation: '',
})

const submitForm = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation')
            avatarPreview.value = null
        },
        onError: (errors) => {
            console.error('Registration failed:', errors)
        }
    })
}
</script>