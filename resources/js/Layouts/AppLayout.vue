<script setup>
import Sidebar from "@/Components/Common/Sidebar.vue";
</script>

<template>

<div class="flex min-h-screen bg-gray-100">

<!-- Sidebar -->
<Sidebar />

<!-- Main Content -->
<div class="flex-1 ml-64">

<!-- Page Header -->
<header class="bg-white shadow p-4">
<slot name="header"/>
</header>

<!-- Page Content -->
<main class="p-6">
<slot/>
</main>

</div>

</div>

</template>