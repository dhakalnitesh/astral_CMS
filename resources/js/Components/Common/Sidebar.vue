<script setup>
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

import {
  HomeIcon,
  CubeIcon,
  UsersIcon,
  ArrowLeftOnRectangleIcon,
  Bars3Icon,
} from "@heroicons/vue/24/outline";

const isOpen = ref(true);

function toggleSidebar() {
  isOpen.value = !isOpen.value;
}

const navGroups = [
  {
    title: "Main",
    links: [
      { label: "Dashboard", to: route("dashboard"), icon: HomeIcon },
    ],
  },
  {
    title: "Management",
    links: [
      { label: "Categories", to: route("categories.index"), icon: UsersIcon },
      { label: "Products", to: route("products.index"), icon: CubeIcon },
      { label: "Customers", to: route("customers.index"), icon: UsersIcon },

    ],

  },
];
</script>

<template>
<aside
:class="[
'fixed top-0 left-0 z-50 h-screen flex flex-col transition-all duration-300 border-r border-gray-200 bg-white',
isOpen ? 'w-64' : 'w-20'
]"
>

<!-- HEADER -->
<div class="flex items-center justify-between px-4 py-4 border-b border-gray-200">

<div v-if="isOpen">
<h1 class="text-xl font-bold text-gray-800">⭐ Astral</h1>
<p class="text-xs text-gray-500">Customer Management</p>
</div>

<button
@click="toggleSidebar"
class="p-2 rounded-md hover:bg-gray-100 text-gray-700"
>
<Bars3Icon class="w-6 h-6"/>
</button>

</div>


<!-- NAVIGATION -->
<nav class="flex-1 px-3 py-3 overflow-y-auto">

<div v-for="group in navGroups" :key="group.title" class="mb-6">

<p
v-if="isOpen"
class="text-gray-400 text-xs font-semibold uppercase tracking-widest mb-2 px-3"
>
{{ group.title }}
</p>

<div v-for="link in group.links" :key="link.label">

<Link
:href="link.to"
class="flex items-center gap-3 px-3 py-2.5 rounded-lg
text-gray-600 hover:text-gray-900 hover:bg-gray-100
transition text-sm font-medium mb-1"
>

<component :is="link.icon" class="w-5 h-5"/>
<span v-if="isOpen">{{ link.label }}</span>

</Link>

</div>
</div>
</nav>


<!-- LOGOUT -->
<div class="px-3 pb-4 border-t border-gray-200">

<Link
:href="route('logout')"
method="post"
as="button"
class="flex items-center gap-3 w-full px-3 py-2.5 rounded-lg
text-gray-600 hover:text-white hover:bg-red-500
transition text-sm font-medium"
>

<ArrowLeftOnRectangleIcon class="w-5 h-5"/>

<span v-if="isOpen">Logout</span>

</Link>

</div>

</aside>
</template>