<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  employee: Object  // The employee object passed from the backend
})
</script>

<template>
<div class="max-w-7xl mx-auto p-6 space-y-6">

  <!-- Breadcrumb / Page Header -->
  <div class="flex justify-between items-center">
    <div>
      <h1 class="text-3xl font-bold text-gray-800">Employee Details</h1>
      <p class="text-gray-500 mt-1">View detailed information about {{ employee.full_name }}</p>
    </div>
    <div class="flex gap-3">
      <Link
        :href="route('employees.edit', employee.id)"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 shadow"
      >
        Edit Employee
      </Link>
      <Link
        :href="route('employees.index')"
        class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 shadow"
      >
        Back to List
      </Link>
    </div>
  </div>

  <!-- PERSONAL INFORMATION -->
  <div class="bg-white shadow rounded-lg p-6 space-y-4">
    <h2 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-4">Personal Information</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <p class="text-gray-500 font-medium">Full Name</p>
        <p class="text-gray-800">{{ employee.full_name }}</p>
      </div>
      <div>
        <p class="text-gray-500 font-medium">Gender</p>
        <p class="text-gray-800">{{ employee.gender }}</p>
      </div>
      <div>
        <p class="text-gray-500 font-medium">Phone</p>
        <p class="text-gray-800">{{ employee.phone }}</p>
      </div>
      <div>
        <p class="text-gray-500 font-medium">Email</p>
        <p class="text-gray-800">{{ employee.email }}</p>
      </div>
      <div>
        <p class="text-gray-500 font-medium">Date of Birth</p>
        <p class="text-gray-800">{{ employee.date_of_birth }}</p>
      </div>
    </div>
  </div>

  <!-- EMPLOYMENT INFORMATION -->
  <div class="bg-white shadow rounded-lg p-6 space-y-4">
    <h2 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-4">Employment Information</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <p class="text-gray-500 font-medium">Designation</p>
        <p class="text-gray-800">{{ employee.designation }}</p>
      </div>
      <div>
        <p class="text-gray-500 font-medium">Department</p>
        <p class="text-gray-800">{{ employee.department || '—' }}</p>
      </div>
      <div>
        <p class="text-gray-500 font-medium">Joining Date</p>
        <p class="text-gray-800">{{ employee.joining_date }}</p>
      </div>
      <div>
        <p class="text-gray-500 font-medium">End Date</p>
        <p class="text-gray-800">{{ employee.end_date || '—' }}</p>
      </div>
      <div>
        <p class="text-gray-500 font-medium">Salary</p>
        <p class="text-gray-800">{{ employee.salary }}</p>
      </div>
      <div>
        <p class="text-gray-500 font-medium">Experience (years)</p>
        <p class="text-gray-800">{{ employee.experience_years }}</p>
      </div>
    </div>
  </div>

  <!-- ADDRESS INFORMATION -->
  <div class="bg-white shadow rounded-lg p-6 space-y-4">
    <h2 class="text-xl font-semibold text-gray-700 border-b pb-2 mb-4">Address Information</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div>
        <p class="text-gray-500 font-medium">Province</p>
        <p class="text-gray-800">{{ employee.province_name || '—' }}</p>
      </div>
      <div>
        <p class="text-gray-500 font-medium">District</p>
        <p class="text-gray-800">{{ employee.district_name || '—' }}</p>
      </div>
      <div>
        <p class="text-gray-500 font-medium">Municipal</p>
        <p class="text-gray-800">{{ employee.municipal_name || '—' }}</p>
      </div>
      <div>
        <p class="text-gray-500 font-medium">Street / Ward</p>
        <p class="text-gray-800">{{ employee.street || '—' }}</p>
      </div>
      <div>
        <p class="text-gray-500 font-medium">Tole</p>
        <p class="text-gray-800">{{ employee.tole || '—' }}</p>
      </div>
    </div>
  </div>

</div>
</template>

<style>
/* Professional styling for view page */
body {
  background-color: #f9fafb;
}

.card-title {
  font-size: 1.25rem;
  font-weight: 600;
}

.text-gray-500 {
  color: #6b7280;
}

.text-gray-800 {
  color: #111827;
}
</style>