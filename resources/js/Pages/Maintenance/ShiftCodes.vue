<template>
    <div class="flex flex-col gap-6 h-full">
        <h3 class="font-semibold">Shift Code Registration</h3>
        <div class="grid grid-cols-2 gap-6 h-96">
            <div class="col-span-1">
                <div class="max-w-md mx-auto bg-base-300 p-8 rounded-xl shadow h-full">
                    <form @submit.prevent="submitForm()" class="card">
                        <TextInput name="Shift Code:" type="text" :message="form.errors?.code"
                            placeholder="Enter Shift Code..." v-model="form.code" />
                        <TextInput name="Start Time:" type="time" :message="form.errors?.start_time"
                            v-model="form.start_time" />
                        <TextInput name="End Time:" type="time" :message="form.errors?.end_time"
                            v-model="form.end_time" />
                        <button type="submit" class="btn btn-primary w-full" :disabled="form.processing">
                            <span v-if="form.processing">Submitting...</span>
                            <span v-else>Submit</span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-span-1">
                <div class="bg-base-300 rounded-box px-4 h-96 overflow-auto">
                    <table class="table">
                        <thead class="sticky top-0 bg-base-300 z-10">
                            <tr class="text-center">
                                <th>Shift Code</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="shift in shiftcodes" :key="shift.shift_code" class="text-center">
                                <td class="font-semibold">{{ shift.code }}</td>
                                <td>{{ shift.start_time }}</td>
                                <td>{{ shift.end_time }}</td>
                                <td class="flex flex-row gap-2 justify-center">
                                    <button @click="handleHypyerLink(shift)"
                                        class="btn btn-success btn-xs">EDIT</button>
                                    <button class="btn btn-error btn-xs">DELETE</button>
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
import { ref, watch } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import { inject } from 'vue'

const toast = inject('toast')
const mode = ref('insert')
const id = ref(null)

const form = useForm({
    code: '',
    start_time: '',
    end_time: ''
})


const submitForm = () => {
    if (mode.value == 'insert') {
        form.post(route('shift.register'), {
            onSuccess: () => {
                form.reset()
                toast('Shift code Registered Successfully.', 'success')
            },
            onError: (errors) => {
                console.log('shift code registration failed:', errors)
            }
        })
    } else {
        if (id) {
            console.log(form)
            form.put(route('shift.update', id.value), {
                onSuccess: () => {
                    form.reset()
                    toast('Shift code Updated Successfully.', 'success')
                },
                onError: (errors) => {
                    toast('Shift Code updating failed. Please try again.', 'danger')
                }
            })
        } else {
            toast('Invalid action. Please try again.', 'danger')
        }

    }
}

const handleHypyerLink = (data) => {
    form.code = data.code
    form.start_time = data.start_time
    form.end_time = data.end_time
    mode.value = 'update'
    id.value = data.id
}

const props = defineProps({
    shiftcodes: Array,
    error: String
})

const shiftcodes = ref([...props.shiftcodes ?? []])

watch(
    () => props.shiftcodes,
    (newShiftcodes) => {
        shiftcodes.value = [...newShiftcodes]
    }
)

</script>