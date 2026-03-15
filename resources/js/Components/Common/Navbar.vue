<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { Bars3Icon, BellIcon, UserCircleIcon } from "@heroicons/vue/24/outline";

const isSidebarOpen = ref(true);

function toggleSidebar() {
  isSidebarOpen.value = !isSidebarOpen.value;
}
</script>

<template>
  <!-- TOP NAVBAR -->
  <header class="fixed top-0 left-0 right-0 z-40 bg-white font-bold shadow-md flex items-center justify-between px-6 py-4">
    
    <!-- Sidebar Toggle + Brand -->
    <div class="flex items-center gap-4">
      <button @click="toggleSidebar" class="p-2 rounded-md hover:bg-gray-100">
        <Bars3Icon class="w-6 h-6 text-gray-700"/>
      </button>
      <div class="flex flex-col">
        <h1 class="text-lg text-gray-800">Astral Tech Soft</h1>
        <p class="text-xs text-gray-500">Customer Management System</p>
      </div>
    </div>

    <!-- Navbar Actions -->
    <div class="flex items-center gap-4">
      <button class="p-2 rounded-md hover:bg-gray-100">
        <BellIcon class="w-6 h-6 text-gray-700"/>
      </button>
      <Link href="#" class="flex items-center gap-2 hover:bg-gray-100 p-2 rounded-md">
        <UserCircleIcon class="w-6 h-6 text-gray-700"/>
        <span>Admin</span>
      </Link>
    </div>
    
  </header>

  <!-- SPACER for sticky navbar -->
  <div class="h-20"></div> <!-- matches header height -->
</template>