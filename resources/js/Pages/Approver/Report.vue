<template>
    <Head title="Report Generator" />
    <div class="flex flex-col items-center justify-center min-h-screen bg-base-300 px-6 py-10">
        <div class="card bg-base-100 shadow-2xl rounded-md w-full max-w-5xl overflow-visible border border-base-200">
            <div class="grid md:grid-cols-2 gap-8">
                <figure class="flex items-center justify-center bg-base-200 p-8 rounded-l-3xl">
                    <img :src="reportImage" alt="report"
                        class="object-contain w-full h-full max-h-[15rem] drop-shadow-md" />
                </figure>
                <div class="card-body flex flex-col justify-center">
                    <h2 class="text-3xl font-extrabold text-primary tracking-tight">Generate Report</h2>
                    <p class="text-base text-gray-500 mt-2">Choose a date range to generate your report.</p>

                    <div v-if="isLoading" class="flex items-center gap-3 mt-4 text-primary">
                        <span class="loading loading-spinner loading-md"></span>
                        <span class="font-medium">{{ loadingMessage }}</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                        <TextInput name="Start Date:" type="date" v-model="selectedDateRange.start_date"
                            :message="selectedDateRange.errors?.start_date" />
                        <TextInput name="End Date:" type="date" v-model="selectedDateRange.end_date"
                            :message="selectedDateRange.errors?.end_date" />
                    </div>

                    <div class="card-actions justify-end mt-8 gap-3 flex-wrap">
                        <button class="btn btn-neutral w-full md:w-auto shadow-sm" @click="handleClearState"
                            :disabled="isLoading">
                            RESET
                        </button>
                        <button class="btn btn-primary w-full md:w-auto shadow-md" @click="handleGenerateReport"
                            :disabled="isLoading">
                            GENERATE
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import reportImage from '../../images/generate-report.svg'
import TextInput from '../Components/TextInput.vue'

const isLoading = ref(false)
const loadingMessage = ref('Processing request...')

const selectedDateRange = useForm({
    start_date: null,
    end_date: null
})

const props = defineProps({
    requests: Object,
    errors: Object,
    flash: Object,
    auth: Object,
})

const handleClearState = () => {
    selectedDateRange.start_date = ''
    selectedDateRange.end_date = ''
}

const handleGenerateReport = () => {
    isLoading.value = true

    setTimeout(() => {
        loadingMessage.value = 'This may take a while depending on date range. Kindly wait...'
    }, 5000);

    selectedDateRange.get(route('approver.generate.report.daterange'), {
        preserveState: true,
        preserveScroll: true,  
        onError: (errors) => {
            console.log("Validation errors:", errors) 
        },
        onFinish: () => {
            console.log("Reactive errors:", selectedDateRange.errors)
            isLoading.value = false
        }
    })
}
</script>