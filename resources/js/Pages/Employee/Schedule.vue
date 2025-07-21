<template>
    <div class="flex flex-col gap-6 h-full">
        <div class="breadcrumbs text-sm">
            <ul>
                <li>
                    <Link :href="route('main')">Home</Link>
                </li>
                <li>
                    <Link :href="route('schedule')">Manage Schedule</Link>
                </li>
            </ul>
        </div>
        <!-- Page Heading -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">Manage Schedule</h1>
            <div class="grid grid-cols-2 gap-4 w-1/3">
                <div class="col-span-1 w-full">
                <SelectOption name="Year" :options="years"/>
                </div>
                <div class="col-span-1 w-full">
                <SelectOption name="Week" :options="weeks"/>
                </div>
            </div>
        </div>
        <hr>
    </div>
</template>

<script setup>
import SelectOption from '../Components/SelectOption.vue'

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
</script>