<template>
    <div class="flex flex-col">
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 flex flex-row border-2 justify-between rounded p-4 bg-base-200">
                <span class="text-md font-semibold">Total Overtime Hours</span>
                <span class="text-2xl font-extrabold">45</span>
            </div>
            <div class="col-span-1 flex flex-row border-2 justify-between rounded p-4 bg-base-200">
                <span class="text-md font-semibold">Pending Requests</span>
                <span class="text-2xl font-extrabold">2</span>
            </div>
        </div>
        <div class="flex mt-4 justify-end gap-2">
            <Link :href="route('request')" class="btn btn-neutral">
            File New Request
            </Link>
            <Link :href="route('schedule')" class="btn btn-neutral">
            Manage Schedules
            </Link>
        </div>

        <div class="h-screen mt-4">
            <div class="flex flex-col gap-4 border rounded-md p-4">
                <!-- Header -->
                <header class="flex items-center justify-between">
                    <button class="btn btn-sm btn-neutral">&lt;</button>
                    <p class="current-date font-semibold text-md">September 2025</p>
                    <button class="btn btn-sm btn-neutral">&gt;</button>
                </header>

                <hr>
                <div class="calendar">
                    <ul class="grid grid-cols-7 gap-6 text-center font-semibold text-md">
                        <li>Sun</li>
                        <li>Mon</li>
                        <li>Tue</li>
                        <li>Wed</li>
                        <li>Thu</li>
                        <li>Fri</li>
                        <li>Sat</li>
                    </ul>

                    <ul class="grid grid-cols-7 gap-4 text-center mt-2 text-md">
                        <li class="p-2 rounded text-gray-400 hover:bg-gray-100">28</li>
                        <li class="p-2 rounded text-gray-400 hover:bg-gray-100">29</li>
                        <li class="p-2 rounded text-gray-400 hover:bg-gray-100">30</li>
                        <li class="p-2 rounded hover:bg-gray-100">1</li>
                        <li class="p-2 rounded hover:bg-gray-100">2</li>
                        <li class="p-2 rounded hover:bg-gray-100">3</li>
                        <li class="p-2 rounded hover:bg-gray-100">4</li>
                        <li class="p-2 rounded hover:bg-gray-100">5</li>
                        <li class="p-2 rounded hover:bg-gray-100">6</li>
                        <li class="p-2 rounded hover:bg-gray-100">7</li>
                        <li class="p-2 rounded hover:bg-gray-100">8</li>
                        <li class="p-2 rounded hover:bg-gray-100">9</li>
                        <li class="p-2 rounded hover:bg-gray-100">10</li>
                        <li class="p-2 rounded hover:bg-gray-100">11</li>
                        <li class="p-2 rounded hover:bg-gray-100">12</li>
                        <li class="p-2 rounded hover:bg-gray-100">13</li>
                        <li class="p-2 rounded hover:bg-gray-100">14</li>
                        <li class="p-2 rounded hover:bg-gray-100">15</li>
                        <li class="p-2 rounded hover:bg-gray-100">16</li>
                        <li class="p-2 rounded hover:bg-gray-100">17</li>
                        <li class="p-2 rounded hover:bg-gray-100">18</li>
                        <li class="p-2 rounded hover:bg-gray-100">19</li>
                        <li class="p-2 rounded hover:bg-gray-100">20</li>
                        <li class="p-2 rounded hover:bg-gray-100">21</li>
                        <li class="p-2 rounded hover:bg-gray-100">22</li>
                        <li class="p-2 rounded hover:bg-gray-100">23</li>
                        <li class="p-2 rounded hover:bg-gray-100">24</li>
                        <li class="p-2 rounded hover:bg-gray-100">25</li>
                        <li class="p-2 rounded hover:bg-gray-100">26</li>
                        <li class="p-2 rounded hover:bg-gray-100">27</li>
                        <li class="p-2 rounded hover:bg-gray-100">28</li>
                        <li class="p-2 rounded hover:bg-gray-100">29</li>
                        <li class="p-2 rounded hover:bg-gray-100">30</li>
                        <li class="p-2 rounded text-gray-400 hover:bg-gray-100">1</li>
                        <li class="p-2 rounded text-gray-400 hover:bg-gray-100">2</li>
                        <li class="p-2 rounded text-gray-400 hover:bg-gray-100">3</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3' 
</script>