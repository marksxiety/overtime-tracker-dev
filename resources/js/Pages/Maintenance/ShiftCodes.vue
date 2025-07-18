<template>
    <Modal ref="modalRef">
        <h2 class="text-lg font-bold mb-4">Are you sure you want to delete?</h2>
        <div class="flex justify-end gap-4">
            <button class="btn btn-sm btn-primary mt-4" @click="handleDeletion()" :disabled="deleteform.processing">
                <span v-if="deleteform.processing" class="loading loading-spinner"></span>
                <span>Confirm</span>
            </button>
            <button class="btn btn-sm btn-secondary mt-4" @click="closeModal"
                :disabled="deleteform.processing">Cancel</button>
        </div>
    </Modal>
    <div class="flex flex-col gap-6 h-full">
        <!-- Breadcrumbs -->
        <div class="breadcrumbs text-sm">
            <ul>
                <li>
                    <Link :href="route('main')">Home</Link>
                </li>
                <li>
                    <Link :href="route('shifts')">Shift Code Registration</Link>
                </li>
            </ul>
        </div>

        <!-- Page Heading -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Manage Shift Codes</h1>
        </div>

        <!-- Grid Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-[32rem]">
            <!-- Form Card -->
            <div class="col-span-1">
                <div class="bg-base-300 p-6 rounded-2xl shadow-xl h-full flex flex-col justify-center">
                    <form @submit.prevent="submitForm()" class="flex flex-col gap-4">
                        <TextInput name="Shift Code:" type="text" :message="form.errors?.code"
                            placeholder="Enter Shift Code..." v-model="form.code" textCase="uppercase" />
                        <TextInput name="Start Time:" type="time" :message="form.errors?.start_time"
                            v-model="form.start_time" />
                        <TextInput name="End Time:" type="time" :message="form.errors?.end_time"
                            v-model="form.end_time" />
                        <button type="submit" class="btn btn-primary w-full mt-4" :disabled="form.processing">
                            <span v-if="form.processing" class="loading loading-spinner"></span>
                            <span>Submit</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Table Section -->
            <div class="col-span-1">
                <div class="bg-base-300 rounded-2xl p-4 h-full overflow-auto shadow-xl">
                    <h2 class="text-lg font-semibold mb-4">Registered Shifts</h2>
                    <table class="table table-zebra w-full">
                        <thead class="sticky top-0 bg-base-300 z-10 text-sm">
                            <tr class="text-center">
                                <th>Shift Code</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="shift in shiftcodes" :key="shift.shift_code" class="text-center hover">
                                <td class="font-semibold">{{ shift.code }}</td>
                                <td>{{ shift.start_time }}</td>
                                <td>{{ shift.end_time }}</td>
                                <td class="flex flex-row gap-2 justify-center">
                                    <button @click="handleHypyerLink(shift)" class="btn btn-success btn-xs">
                                        EDIT
                                    </button>
                                    <button class="btn btn-error btn-xs" @click="initiateDeletion(shift.id)"
                                        :disabled="deleteform.processing">
                                        <span>DELETE</span></button>
                                </td>
                            </tr>
                            <tr v-if="shiftcodes.length === 0">
                                <td colspan="4" class="text-center italic text-gray-400 py-4">
                                    No shift codes available.
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
import { useForm, router, Link } from '@inertiajs/vue3'
import { inject } from 'vue'
import Modal from '../Components/Modal.vue'

const modalRef = ref(null)

const showModal = () => {
    modalRef.value?.open()
}

const closeModal = () => {
    modalRef.value?.close()
}

const toast = inject('toast')
const mode = ref('insert')
const id = ref(null)

const form = useForm({
    code: '',
    start_time: '',
    end_time: ''
})

const deleteform = useForm()


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

const initiateDeletion = (shift_id) => {
    showModal()
    id.value = shift_id
}

const handleDeletion = () => {
    if (id) {
        mode.value = 'delete'
        deleteform.delete(route('shift.delete', id.value), {
            onSuccess: () => {
                toast('Shift code delete successfully.', 'warning')
                mode.value = 'insert'
                closeModal()
            },
            onError: () => {
                toast('Shift Code deletion failed. Please try again.', 'danger')
            }
        })
    } else {
        toast('Invalid action. Please try again.', 'danger')
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