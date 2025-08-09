<template>
    <div class="flex flex-col gap-6 h-full">
        <div class="breadcrumbs text-sm">
            <ul>
                <li>
                    <Link :href="route('main')">Dashboard</Link>
                </li>
                <li>
                    <Link :href="route('profile.employee')">
                    Update Profile
                    </Link>
                </li>
            </ul>
        </div>

        <!-- Page Heading -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-extrabold text-base-content">Profile Information</h1>
        </div>
        <div class="flex justify-center">
            <div class="bg-base-100 rounded-2xl p-4 w-3/4">
                <form @submit.prevent="submitForm" class="grid gap-4 grid-cols-1 md:grid-cols-2">
                    <div class="mb-4 col-span-1 h-full">
                        <div class="flex flex-col items-center justify-center space-y-3 h-full">
                            <div v-if="avatarPreview"
                                class="w-64 h-64 rounded-full overflow-hidden border-4 border-primary bg-base-100 flex items-center justify-center shadow">
                                <img :src="avatarPreview" alt="Avatar Preview" class="object-cover w-full h-full" />
                            </div>
                            <div v-else
                                class="w-64 h-64 rounded-full overflow-hidden border-4 border-primary bg-base-100 flex items-center justify-center shadow">
                                <Icon icon="streamline:user-profile-focus" width="90" height="90" />
                            </div>
                            <button type="button" @click="triggerFileInput" class="btn btn-primary rounded-full">
                                Update Avatar
                            </button>
                        </div>
                        <input ref="fileInput" type="file" id="avatar" name="avatar" accept="image/*" @change="change"
                            class="hidden" />
                        <p v-if="form.errors.avatar"
                            class="mt-1 text-sm text-red-600 bg-red-50 px-2 py-1 rounded text-center">
                            {{ form.errors.avatar }}
                        </p>
                    </div>
                    <div class="col-span-1 p-2">
                        <TextInput name="Name:" :message="form.errors.name" v-model="form.name" placeholder=""/>
                        <TextInput name="Email:" type="email" :message="form.errors.email" v-model="form.email" placeholder=""/>
                        <div class="flex justify-between gap-4">
                            <TextInput name="Employee ID:" type="text" :message="form.errors.email" v-model="form.email"
                                :readonly="true" placeholder="" class="w-full" />
                            <TextInput name="Role:" type="text" :message="form.errors.email" v-model="form.email"
                                :readonly="true" placeholder="" class="w-full" />
                        </div>
                        <TextInput name="Old Password:" type="password" :message="form.errors.old_password"
                            v-model="form.old_password" placeholder=""/>
                        <TextInput name="Confrim New Password" type="password" v-model="form.new_password"
                            :message="form.errors.new_password" placeholder=""/>
                        <TextInput name="New Password" type="password" v-model="form.new_password_confirmation"
                            :message="form.errors.new_password_confirmation" placeholder=""/>
                        <button type="submit" class="btn btn-primary w-full" :disabled="form.processing">
                            <span v-if="form.processing">Processing...</span>
                            <span v-else>Update Profile</span>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from '@inertiajs/vue3'
import TextInput from './Components/TextInput.vue'
import { ref } from 'vue'
import { Icon } from "@iconify/vue";

const props = defineProps({
    user: Object,
    avatar_url: Object,
    errors: Object,
    flash: Object,
    auth: Object,
})

const form = useForm({
    name: props.user?.name ?? '',
    email: props.user?.email ?? '',
    employee_id: '',
    role: '',
    avatar: null,
    old_password: '',
    new_password: '',
    new_password_confirmation: ''
})

const fileInput = ref(null)
// Use avatarPreview for previewing the image
const avatarPreview = ref(props.avatar_url ?? null)

const triggerFileInput = () => {
    fileInput.value.click()
}

const change = (event) => {
    const file = event.target.files[0]
    form.avatar = file // This is now a File object
    if (file) {
        const reader = new FileReader()
        reader.onload = (e) => {
            avatarPreview.value = e.target.result
        }
        reader.readAsDataURL(file)
    } else {
        avatarPreview.value = props.avatar_url ?? null
    }
}


const submitForm = () => {
    form.post(route('update.profile.employee', props.user.id), {
        onFinish: () => {
            form.reset('title', 'email', 'old_password', 'new_password')
        },
        onError: (errors) => {
            console.error('Updating task failed:', errors)
        }
    })

    console.log('Form submitted:', form)
}
</script>