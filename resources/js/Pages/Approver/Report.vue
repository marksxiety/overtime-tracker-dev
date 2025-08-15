<template>
    <div class="flex flex-col items-center justify-center min-h-screen bg-base-300">
        <div class="card card-side bg-base-100 shadow-xl rounded-2xl w-full max-w-5xl px-6 py-6">
            <figure class="flex items-center justify-center w-full h-full">
                <img :src="reportImage" alt="report" class="object-cover w-full h-full rounded-l-2xl" />
            </figure>
            <div class="card-body justify-center">
                <div class="flex items-start gap-3">
                    <div>
                        <h2 class="card-title text-2xl font-bold">Generate Report</h2>
                        <p class="text-sm leading-relaxed text-gray-700">
                            Generate a tailored report for your selected period using the available filters.
                            <br><br>
                            <strong>Instructions:</strong>
                        <ul class="list-disc list-inside mt-2">
                            <li>Choose a <span class="font-semibold">Mode</span> to determine how data is grouped
                                (e.g., <em>Weekly</em> for short-term trends, <em>Yearly</em> for annual summaries).
                            </li>
                            <li>Select one or multiple <span class="font-semibold">Year(s)</span> to include in the
                                report.</li>
                            <li>Combine multiple modes and years to generate side-by-side comparisons for better
                                analysis.</li>
                        </ul>
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-4 justify-center p-4">
                    <div class="filter">
                        <input class="btn filter-reset" type="radio" name="metaframeworks" aria-label="All" />
                        <input v-for="(mode, index) in modes" :key="index" class="btn" type="radio"
                            name="metaframeworks" :aria-label="mode" :value="mode" />
                    </div>
                </div>
                <div class="card-actions justify-end p-4">
                    <button class="btn btn-primary">Generate</button>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>
import { Icon } from "@iconify/vue"
import reportImage from '../../images/generate-report.svg'

const modes = ["Weekly", "Monthly", "Quarterly", "Yearly"]
</script>
