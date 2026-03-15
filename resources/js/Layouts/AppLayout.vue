<script setup>
import Sidebar from "@/Components/Common/Sidebar.vue";
import Footer from "@/Components/Common/Footer.vue";
import Navbar from "@/Components/Common/Navbar.vue";
</script>

<template>
    <div class="flex flex-col min-h-screen bg-gray-100">
        
        <div class="flex flex-1">
        <Navbar />

      <!-- Sidebar -->
      <Sidebar />

      <!-- Main Content -->
      <div class="flex-1 ml-64 flex flex-col">

        <!-- Page Header -->
        <header class="bg-white shadow p-4">
          <slot name="header"/>
        </header>

        <!-- Page Content -->
        <main class="p-6 flex-1">
          <slot/>
        </main>

        <!-- Footer -->
        <Footer />

      </div>
    </div>
  </div>
</template>