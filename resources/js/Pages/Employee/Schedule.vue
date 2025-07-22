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
                <SelectOption name="Year" :options="years" v-model="selectedYear"/>
                </div>
                <div class="col-span-1 w-full">
                <SelectOption name="Week" :options="weeks" v-model="selectedWeek"/>
                </div>
            </div>
        </div>
        <hr>
    </div>
</template>

<script setup>
import SelectOption from '../Components/SelectOption.vue'
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3' 
import { years, weeks, currentWeek } from '../utils/dropdownOptions.js'

const selectedYear = ref(new Date().getFullYear())
const selectedWeek = ref(currentWeek())
</script>