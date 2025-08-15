<template>
    <div class="flex flex-col items-center justify-center min-h-screen bg-base-300">
        <div class="card bg-base-100 shadow-xl rounded-2xl w-full max-w-2xl p-6">
            <div class="flex items-start gap-3 border-b pb-4 mb-4">
                <Icon icon="mingcute:schedule-line" width="45" height="45" class="text-primary mt-1" />
                <div>
                    <h2 class="text-lg font-bold">Generate Report</h2>
                    <p class="text-sm leading-relaxed font-light">
                        Use the filters below to generate a customized report for your selected time frame.
                        <br>
                        <strong>Tip:</strong>
                        - Choose a <span class="font-semibold">Mode</span> to define how data is grouped
                        (e.g., <em>Weekly</em> for short-term trends, <em>Yearly</em> for annual summaries).
                        <br>
                        - Select one or multiple <span class="font-semibold">Year(s)</span> to include in the report.
                        <br>
                        - You can combine multiple modes and years to create side-by-side comparisons.
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap gap-4 justify-center">
                <div class="filter">
                    <input class="btn filter-reset" type="radio" name="metaframeworks" aria-label="All" />
                    <input v-for="(mode, index) in modes" :key="index" class="btn" type="radio" name="metaframeworks"
                        :aria-label="mode" :value="mode" />
                </div>
            </div>

            <div class="card-actions justify-end">
                <button class="btn btn-primary">Generate</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Icon } from "@iconify/vue"

const modes = ["Weekly", "Monthly", "Quarterly", "Yearly"]
const years = [2024, 2025, 2026]
</script>
