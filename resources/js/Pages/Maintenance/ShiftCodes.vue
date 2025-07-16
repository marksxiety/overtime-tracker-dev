<template>
    <div class="flex flex-col gap-6 h-full">
        <h3 class="font-semibold">Shift Code Registration</h3>
        <div class="grid grid-cols-2 gap-6 h-96">
            <div class="col-span-1">
                <div class="max-w-md mx-auto bg-base-300 p-8 rounded-xl shadow h-full">
                    <form @submit.prevent="" class="card">
                        <TextInput name="Shift Code:" type="text" placeholder="Enter Shift Code..." />
                        <TextInput name="Start Time:" type="time" />
                        <TextInput name="End Time:" type="time" />
                        <button type="submit" class="btn btn-primary w-full">
                            <span>Submit</span>
                        </button>
                    </form>
                </div>
            </div>
            <div class="col-span-1">
                <div class="bg-base-300 rounded-box px-4 h-96 overflow-auto">
                    <table class="table">
                        <thead class="sticky top-0 bg-base-300 z-10">
                            <tr>
                                <th>Shift Code</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="shift in shiftcodes" :key="shift.shift_code">
                                <td>{{ shift.shift_code }}</td>
                                <td>{{ shift.start_time }}</td>
                                <td>{{ shift.end_time }}</td>
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
import { ref } from 'vue'

const shiftcodes = ref([
    { shift_code: 'C1', start_time: '07:00', end_time: '19:00' },
    { shift_code: 'S1', start_time: '09:00', end_time: '17:00' },
    { shift_code: 'C2', start_time: '19:00', end_time: '07:00' },
    { shift_code: 'S2', start_time: '06:00', end_time: '14:00' },

    { shift_code: 'C4', start_time: '09:00', end_time: '19:00' },
    { shift_code: 'S3', start_time: '14:00', end_time: '22:00' },
    { shift_code: 'C5', start_time: '07:00', end_time: '17:00' },
    { shift_code: 'S4', start_time: '22:00', end_time: '06:00' },

    { shift_code: 'C7', start_time: '06:00', end_time: '18:00' },
    { shift_code: 'S7', start_time: '08:00', end_time: '18:00' },
    { shift_code: 'C8', start_time: '18:00', end_time: '06:00' },
    { shift_code: 'S8', start_time: '20:00', end_time: '06:00' },

    { shift_code: 'C9', start_time: '08:00', end_time: '16:00' },
    { shift_code: 'R1', start_time: '08:00', end_time: '20:00' },
    { shift_code: 'R2', start_time: '20:00', end_time: '08:00' },

    { shift_code: 'SY', start_time: 'N/A', end_time: 'N/A' },
    { shift_code: 'NWS', start_time: 'N/A', end_time: 'N/A' },
    { shift_code: 'SX', start_time: 'N/A', end_time: 'N/A' }]
)

</script>