<template>
    <div class="flex flex-col gap-6 h-full">
        <div class="breadcrumbs text-sm">
            <ul>
                <li>
                    <Link :href="route('main')">Home</Link>
                </li>
                <li>
                    <Link :href="route('hours')">Required Hours Registration</Link>
                </li>
            </ul>
        </div>
        <!-- Page Heading -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Manage Required Hours</h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-[32rem]">
            <div class="col-span-1">
                <div class="bg-base-300 p-6 rounded-2xl shadow-xl h-full flex flex-col justify-center">
                    <form @submit.prevent="submitForm()" class="card">
                        <SelectOption name="Year:" :options="years" v-model="form.year" :message="form.errors.year" />
                        <SelectOption name="Week:" :options="weeks" v-model="form.week" :message="form.errors.week" />
                        <TextInput name="Required Hours:" type="number" v-model="form.required_hours"
                            :message="form.errors.required_hours" placeholder="Enter the required hours per week..." />
                        <button type="submit" class="btn btn-primary w-full">
                            <span>Submit</span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-span-1">
                <div class="bg-base-300 rounded-2xl p-4 h-full overflow-auto shadow-xl">
                    <h2 class="text-lg font-semibold mb-4">Registered Required Hours</h2>
                    <table class="table table-zebra w-full">
                        <thead class="sticky top-0 bg-base-300 z-10">
                            <tr class="text-center">
                                <th>Year</th>
                                <th>Week</th>
                                <th>Required Hours</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="required in registerd_required_hours" :key="required.required_code"
                                class="text-center">
                                <td>{{ required.year }}</td>
                                <td>{{ required.week }}</td>
                                <td>{{ `${required.required_hours} hrs` }}</td>
                                <td class="flex flex-row gap-2 justify-center">
                                    <button class="btn btn-success btn-xs">
                                        EDIT
                                    </button>
                                    <button class="btn btn-error btn-xs">
                                        <span>DELETE</span></button>
                                </td>
                            </tr>
                            <tr v-if="registerd_required_hours.length === 0">
                                <td colspan="4" class="text-center italic text-gray-400 py-4">
                                    No required hours available.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import TextInput from '../Components/TextInput.vue'
import SelectOption from '../Components/SelectOption.vue'
import { ref, inject, watch } from 'vue'
import { useForm, usePage, Link } from '@inertiajs/vue3'

const toast = inject('toast')
const page = usePage()

const props = defineProps({
    requiredhours: Array,
    error: String
})

const registerd_required_hours = ref([...props.requiredhours ?? []])

const weeks = Array.from({ length: 52 }, (_, i) => {
    const weekNum = String(i + 1).padStart(2, '0')
    return {
        label: `Week: ${weekNum}`,
        value: `${parseInt(weekNum)}`
    }
})

const years = [
    { label: "Select Year", value: "", selected: true },
    { label: "2024", value: "2024" },
    { label: "2025", value: "2025" },
    { label: "2026", value: "2026" },
    { label: "2027", value: "2027" },
    { label: "2028", value: "2028" },
    { label: "2029", value: "2029" },
    { label: "2030", value: "2030" }
]


const form = useForm({
    year: '',
    week: '',
    required_hours: ''
})

const submitForm = () => {
    form.post(route('hours.register'), {
        onSuccess: () => {
            toast(page.props?.flash?.message, 'success')
        },
        onError: () => {
            toast('Registration failed. Please try again', 'error')
        }
    })
}


watch(
    () => props.requiredhours,
    (newrequiredhours) => {
        registerd_required_hours.value = [...newrequiredhours]
    }
)

</script>