<template>
    <div class="flex flex-col gap-6 h-full">
        <h3 class="font-semibold">Required Hours Registration</h3>
        <div class="grid grid-cols-2 gap-6 h-96">
            <div class="col-span-1">
                <div class="max-w-md mx-auto bg-base-300 p-8 rounded-xl shadow h-full">
                    <form @submit.prevent="" class="card">
                        <SelectOption name="Year:" :options="years"/>
                        <SelectOption name="Week:" :options="weeks"/>
                        <TextInput name="Required Hours:" type="number" placeholder="Enter the required hours per week..."/>
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
                                <th>Year</th>
                                <th>Week</th>
                                <th>Required Hours</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="required in registerd_required_hours" :key="required.required_code">
                                <td>{{ required.year }}</td>
                                <td>{{ required.week }}</td>
                                <td>{{ required.hours }}</td>
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
import { ref } from 'vue'

const weeks = Array.from({ length: 52 }, (_, i) => {
  const weekNum = String(i + 1).padStart(2, '0')
  return {
    label: `Week: ${weekNum}`,
    value: `${weekNum}`
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

const registerd_required_hours = ref([
  { year: '2024', week: 1, hours: 120 },
  { year: '2024', week: 2, hours: 120 },
  { year: '2024', week: 3, hours: 120 },
  { year: '2024', week: 4, hours: 120 },
  { year: '2024', week: 5, hours: 120 },
  { year: '2024', week: 6, hours: 120 },
  { year: '2024', week: 7, hours: 120 },
  { year: '2024', week: 8, hours: 120 },
  { year: '2024', week: 9, hours: 120 },
  { year: '2024', week: 10, hours: 120 },
  { year: '2024', week: 11, hours: 120 },
  { year: '2024', week: 12, hours: 120 },
  { year: '2024', week: 13, hours: 120 },
  { year: '2024', week: 14, hours: 120 },
  { year: '2024', week: 15, hours: 120 },
  { year: '2024', week: 16, hours: 120 },
  { year: '2024S', week: 17, hours: 120 },
  { year: '2024', week: 18, hours: 120 }
])

</script>