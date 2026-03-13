<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

const sidebarOpen = ref(true);
const userDropdown = ref(false);
</script>

<template>
<div class="min-h-screen flex flex-col bg-gray-100">

    <!-- ================= NAVBAR ================= -->
    <nav class="bg-white shadow-md">
        <div class="flex items-center justify-between px-6 h-16">

            <!-- Left Side -->
            <div class="flex items-center space-x-4">

                <!-- Sidebar Toggle -->
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="text-gray-600 hover:text-gray-800"
                >
                    ☰
                </button>

                <!-- Logo -->
                <img
                    src="/logo.png"
                    class="h-8 w-8"
                    alt="logo"
                />

                <!-- App Name -->
                <h1 class="text-lg font-bold text-gray-800">
                    My Application
                </h1>
            </div>


            <!-- Search -->
            <div class="hidden md:block w-1/3">
                <input
                    type="text"
                    placeholder="Search..."
                    class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring"
                />
            </div>


            <!-- User Dropdown -->
            <div class="relative">

                <button
                    @click="userDropdown = !userDropdown"
                    class="flex items-center space-x-2"
                >
                    <img
                        class="w-8 h-8 rounded-full"
                        src="https://i.pravatar.cc/40"
                    />

                    <span class="text-gray-700 font-medium">
                        {{ $page.props.auth.user.name }}
                    </span>
                </button>


                <!-- Dropdown -->
                <div
                    v-if="userDropdown"
                    class="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-lg"
                >
                    <Link
                        href="/profile"
                        class="block px-4 py-2 hover:bg-gray-100"
                    >
                        Profile
                    </Link>

                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        class="block w-full text-left px-4 py-2 hover:bg-gray-100"
                    >
                        Logout
                    </Link>
                </div>

            </div>
        </div>
    </nav>


    <!-- ================= MAIN SECTION ================= -->
    <div class="flex flex-1">

        <!-- ============ SIDEBAR ============ -->
        <aside
            v-if="sidebarOpen"
            class="w-64 bg-white shadow-md"
        >
            <div class="p-4 font-bold text-gray-700 border-b">
                Navigation
            </div>

            <nav class="p-2">

                <Link
                    href="/dashboard"
                    class="block px-4 py-2 rounded hover:bg-gray-200"
                >
                    Dashboard
                </Link>

                <Link
                    href="/products"
                    class="block px-4 py-2 rounded hover:bg-gray-200"
                >
                    Products
                </Link>

                <Link
                    href="/users"
                    class="block px-4 py-2 rounded hover:bg-gray-200"
                >
                    Users
                </Link>

            </nav>
        </aside>


        <!-- ============ BODY CONTENT ============ -->
        <div class="flex flex-col flex-1">

            <main class="flex-1 p-6">

                <!-- Page Content -->
                <slot />

            </main>


            <!-- ============ FOOTER ============ -->
            <footer
                class="bg-white border-t text-center py-3 text-gray-600"
            >
                © 2026 My Application. All rights reserved.
            </footer>

        </div>

    </div>

</div>
</template>