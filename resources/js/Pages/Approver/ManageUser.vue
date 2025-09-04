<template>
    <div class="flex flex-col gap-6 h-full pb-12">
        <!-- Breadcrumbs -->
        <div class="breadcrumbs text-sm">
            <ul>
                <li>
                    <Link :href="route('main')">Home</Link>
                </li>
                <li>
                    <Link :href="route('schedule.manage')">Manage Users</Link>
                </li>
            </ul>
        </div>

        <!-- Page Heading -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold">List of Users</h1>

            <div class="flex gap-4">
                <button class="btn btn-primary" :class="{ 'btn-outline': viewMode !== 'grid' }"
                    @click="viewMode = 'grid'">
                    <Icon icon="mingcute:grid-line" width="24" height="24" />
                    Grid View
                </button>

                <button class="btn btn-primary" :class="{ 'btn-outline': viewMode !== 'list' }"
                    @click="viewMode = 'list'">
                    <Icon icon="ion:list" width="24" height="24" />
                    List View
                </button>
            </div>
        </div>

        <div v-if="viewMode === 'grid'" class="grid grid-cols-3 gap-4">
            <div v-for="user in users" :key="user.id" class="p-4 rounded-lg border">
                <h2 class="font-semibold">{{ user.name }}</h2>
                <p class="text-sm">{{ user.email }}</p>
            </div>
        </div>

        <div v-else class="flex flex-col gap-2">
            <div v-for="user in users" :key="user.id" class="p-3 border rounded-md flex justify-between">
                <span>{{ user.name }}</span>
                <span class="text-sm text-gray-500">{{ user.email }}</span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { Icon } from "@iconify/vue"
import { ref } from "vue"

// mock user data (replace with props or API call)
const users = ref([
    { id: 1, name: "Alice Johnson", email: "alice@example.com" },
    { id: 2, name: "Bob Smith", email: "bob@example.com" },
    { id: 3, name: "Charlie Brown", email: "charlie@example.com" },
])

// default view mode
const viewMode = ref("grid")
</script>
