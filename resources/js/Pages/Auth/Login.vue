<template>
    <div class="max-w-md mx-auto bg-white p-8 rounded-xl shadow mt-10">
        <h2 class="text-2xl font-bold mb-6 text-blue-600">Login User</h2>
        <form @submit.prevent="submitForm">
            <TextInput name="Email:" type="email" :message="form.errors.email" v-model="form.email" />
            <TextInput name="Password:" type="password" :message="form.errors.password" v-model="form.password" />
            <div class="flex items-end mb-4">
                <input type="checkbox" id="remember" v-model="form.remember"
                    class="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="remember" class="text-gray-700">Remember me</label>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition"
                :disabled="form.processing">
                <span v-if="form.processing">Processing...</span>
                <span v-else>Login</span>
            </button>
        </form>
        <div class="mt-6 text-center"> Dont have an account?
            <Link :href="route('register')" class="text-blue-600 hover:underline">
            Register here
            </Link>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import TextInput from '../Components/TextInput.vue'

const form = useForm({
    email: null,
    password: null,
    remember: null
})

const submitForm = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password', 'remember')
        },
        onError: (errors) => {
            console.error('Registration failed:', errors)
        }
    })
}
</script>