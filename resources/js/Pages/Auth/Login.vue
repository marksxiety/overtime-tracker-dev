<template>
    <div class="max-w-md mx-auto bg-base-100 p-8 rounded-xl shadow mt-20">
        <h2 class="text-2xl font-bold mb-6 text-primary">Login User</h2>
        <form @submit.prevent="submitForm" class="card">
            <TextInput name="Email:" type="email" :message="form.errors.email" v-model="form.email" />
            <TextInput name="Password:" type="password" :message="form.errors.password" v-model="form.password" />
            <div class="flex items-end mb-4">
                <label class="label">
                    <input type="checkbox" checked="checked" class="checkbox checkbox-primary"
                        v-model="form.remember" />
                    Remember me
                </label>
            </div>
            <button type="submit" class="btn btn-primary w-full" :disabled="form.processing">
                <span v-if="form.processing">Processing...</span>
                <span v-else>Login</span>
            </button>
        </form>
        <div class="mt-6 text-center"> Dont have an account?
            <Link :href="route('register')" class="link">
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