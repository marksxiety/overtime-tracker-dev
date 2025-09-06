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

        <div v-if="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="user in users" :key="user.id" class="card bg-base-100 shadow-xl">
                <!-- Avatar + Name -->
                <div class="card-body">
                    <div class="flex items-center gap-4">
                        <!-- Avatar -->
                        <div class="avatar">
                            <div class="w-16 h-16 rounded-full overflow-hidden bg-base-200">
                                <!-- If avatar URL exists, show the image -->
                                <img v-if="user.avatar_url" :src="user.avatar_url" alt="Avatar"
                                    class="w-full h-full object-cover" />

                                <!-- If no avatar, wrap the icon in a flex container -->
                                <div v-else-if="!user.avatar_url && user.name"
                                    class="w-full h-full flex items-center justify-center">
                                    <Icon icon="iconamoon:profile-circle-fill" class="w-10 h-10" />
                                </div>

                                <!-- If even name is missing, fallback -->
                                <div v-else
                                    class="w-full h-full flex items-center justify-center bg-neutral text-neutral-content">
                                    <span class="text-xl font-bold">?</span>
                                </div>
                            </div>
                        </div>


                        <!-- Name + Email -->
                        <div>
                            <h2 class="card-title text-lg">{{ user.name }}</h2>
                            <p class="text-sm">{{ user.email }}</p>
                        </div>
                    </div>

                    <!-- User Details -->
                    <div class="mt-4 space-y-1 text-sm">
                        <p><span class="font-medium">Employee ID:</span> {{ user.employeeid }}</p>
                        <p><span class="font-medium">Role:</span> {{ user.role }}</p>
                        <p>
                            <span class="font-medium">Active: </span>
                            <span :class="user.active ? 'text-success font-medium' : 'text-error font-medium'">
                                {{ user.active ? 'Yes' : 'No' }}
                            </span>
                        </p>
                        <p><span class="font-medium">Created:</span> {{ new Date(user.created_at).toLocaleDateString()
                        }}</p>
                    </div>
                    <div class="flex justify-end flex-row w-full gap-2">
                        <button type="submit" class="btn btn-xs btn-success btn-outline">
                            <Icon icon="mdi:pencil" class="w-4 h-4 mr-1" /> EDIT
                        </button>
                        <button type="submit" class="btn btn-xs btn-error btn-outline">
                            <Icon icon="mdi:trash-can" class="w-4 h-4 mr-1" /> DELETE
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="flex flex-col gap-4">
            <div v-for="user in users" :key="user.id" class="card bg-base-100 shadow-md">
                <div class="card-body p-4">
                    <!-- Accordion -->
                    <div class="collapse collapse-arrow border border-base-300 bg-base-100 rounded-lg">
                        <input type="checkbox" />

                        <!-- Summary -->
                        <div class="collapse-title text-lg font-medium flex items-center gap-3">
                            <!-- Avatar -->
                            <div class="avatar">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-base-200">
                                    <img v-if="user.avatar_url" :src="user.avatar_url" alt="Avatar"
                                        class="w-full h-full object-cover" />
                                    <div v-else-if="!user.avatar_url && user.name"
                                        class="w-full h-full flex items-center justify-center">
                                        <Icon icon="iconamoon:profile-circle-fill" class="w-6 h-6" />
                                    </div>
                                    <div v-else
                                        class="w-full h-full flex items-center justify-center bg-neutral text-neutral-content">
                                        <span class="text-sm font-bold">?</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Name + Email -->
                            <div class="flex flex-col">
                                <span>{{ user.name }}</span>
                                <span class="text-sm text-gray-500">{{ user.email }}</span>
                            </div>
                        </div>

                        <!-- Details -->
                        <div class="collapse-content text-sm space-y-2">
                            <p><span class="font-medium">Employee ID:</span> {{ user.employeeid }}</p>
                            <p><span class="font-medium">Role:</span> {{ user.role }}</p>
                            <p>
                                <span class="font-medium">Active:</span>
                                <span :class="user.active ? 'text-success font-medium' : 'text-error font-medium'">
                                    {{ user.active ? 'Yes' : 'No' }}
                                </span>
                            </p>
                            <p><span class="font-medium">Created:</span> {{ new
                                Date(user.created_at).toLocaleDateString() }}</p>

                            <!-- Actions -->
                            <div class="flex justify-end gap-2 pt-3">
                                <button type="button" class="btn btn-xs btn-outline btn-success">
                                    <Icon icon="mdi:pencil" class="w-4 h-4 mr-1" /> Edit
                                </button>
                                <button type="button" class="btn btn-xs btn-outline btn-error">
                                    <Icon icon="mdi:trash-can" class="w-4 h-4 mr-1" /> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { Icon } from "@iconify/vue"
import { ref } from "vue"
const props = defineProps({
    user: Object,
    avatar_url: String,
    errors: Object,
    flash: Object,
    auth: Object,
    users: Object,
})

// default view mode
const viewMode = ref("grid")
</script>
