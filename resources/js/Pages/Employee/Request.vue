<template>
    <div class="flex flex-col gap-6 p-4 min-h-screen">
        <!-- Breadcrumbs -->
        <div class="breadcrumbs text-sm">
            <ul>
                <li>
                    <Link :href="route('main')">Home</Link>
                </li>
                <li>
                    <Link :href="route('request')">Overtime Request</Link>
                </li>
            </ul>
        </div>

        <div class="flex justify-center items-center min-h-[50vh]">
            <div class="card bg-base-100 w-full shadow-sm h-[50vh] flex flex-col">
                <div class="card-body flex flex-col h-full">
                    <div class="flex justify-between items-center mb-4">
                        <h1 class="text-2xl font-bold">Overtime Requests</h1>
                    </div>

                    <div class="flex flex-wrap items-center gap-4 mb-4">
                        <input type="text" placeholder="Search by name or job"
                            class="input input-bordered flex-1 min-w-[200px]" />

                        <select class="select select-bordered w-52">
                            <option value="">ALL</option>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                        </select>

                        <select class="select select-bordered w-52">
                            <option value="">ALL</option>
                            <option value="Week 1">Week 1</option>
                            <option value="Week 2">Week 2</option>
                            <option value="Week 3">Week 3</option>
                            <option value="Week 4">Week 4</option>
                        </select>
                    </div>

                    <hr>
                    <div class="overflow-x-auto rounded-box bg-base-100 flex-1">
                        <table class="table w-full">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Job</th>
                                    <th>Status</th>
                                    <th>Week</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>1</th>
                                    <td>Cy Ganderton</td>
                                    <td>Quality Control Specialist</td>
                                    <td>Pending</td>
                                    <td>Week 1</td>
                                </tr>
                                <tr>
                                    <th>2</th>
                                    <td>Hart Hagerty</td>
                                    <td>Desktop Support Technician</td>
                                    <td>Approved</td>
                                    <td>Week 2</td>
                                </tr>
                                <tr>
                                    <th>3</th>
                                    <td>Brice Swyre</td>
                                    <td>Tax Accountant</td>
                                    <td>Rejected</td>
                                    <td>Week 3</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <div class="text-sm">
                            Showing 3 out of 10 entries
                        </div>
                        <div class="join">
                            <button class="join-item btn">1</button>
                            <button class="join-item btn btn-active">2</button>
                            <button class="join-item btn">3</button>
                            <button class="join-item btn">4</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    info: Object,
    payload: Object,
    errors: Object,
    flash: Object,
    success: Boolean,
    message: String,
    auth: Object,
})
</script>