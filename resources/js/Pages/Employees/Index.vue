<script setup>
import { Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import axios from 'axios'
import { ref, watch } from 'vue'

import {
  PencilSquareIcon,
  TrashIcon,
  PlusIcon,
  EyeIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  employees: Object,
  filters: Object
})

const search = ref(props.filters?.search || '')
const perPage = ref(props.filters?.perPage || 10)

// Watch search and perPage
watch([search, perPage], () => {
  router.get(
    route('employees.index'),
    { search: search.value, perPage: perPage.value },
    { preserveState: true, replace: true }
  )
})

// Delete employee
const deleteEmployee = async (id) => {
  if (!confirm('Delete this employee?')) return

  try {
    await axios.delete(route('employees.destroy', id))
    router.reload({ only: ['employees'] })
  } catch (error) {
    alert('Failed to delete employee')
  }
}
</script>

<template>
<div class="p-6">

  <!-- CARD -->
  <div class="bg-white shadow-md rounded-lg overflow-hidden">

    <!-- TITLE -->
    <div class="px-6 py-4 border-b border-gray-200">
      <h1 class="text-2xl font-bold text-gray-800">Employees Information</h1>
    </div>

    <!-- SEARCH + ACTIONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between px-6 py-4 gap-4 border-b border-gray-200">

      <!-- Left: Search + Rows -->
      <div class="flex flex-col sm:flex-row gap-4 sm:gap-2 flex-1">

        <div class="relative flex-1">
          <input
            v-model="search"
            type="text"
            placeholder="Search employee..."
            class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 shadow-sm focus:ring focus:ring-blue-200"
          />
          <MagnifyingGlassIcon class="w-5 h-5 text-gray-400 absolute left-3 top-2.5"/>
        </div>

        <select v-model="perPage" class="w-28 border border-gray-300 rounded-lg py-2 px-2">
          <option value="5">5 rows</option>
          <option value="10">10 rows</option>
          <option value="25">25 rows</option>
          <option value="50">50 rows</option>
        </select>

      </div>

      <!-- Right: Add Employee -->
      <div class="flex-shrink-0">
        <Link
          :href="route('employees.create')"
          class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow"
        >
          <PlusIcon class="w-5 h-5"/> Add Employee
        </Link>
      </div>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">

        <thead class="bg-gray-100 uppercase text-gray-600 text-left text-xs">
          <tr>
            <th class="px-6 py-3">SN</th>
            <th class="px-6 py-3">Full Name</th>
            <th class="px-6 py-3">Email</th>
            <th class="px-6 py-3">Phone</th>
            <th class="px-6 py-3">Designation</th>
            <th class="px-6 py-3 text-right">Actions</th>
          </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
          <tr
            v-for="(emp, index) in employees.data"
            :key="emp.id"
            class="hover:bg-gray-50 transition-colors"
          >
            <td class="px-6 py-4">{{ employees.from + index }}</td>
            <td class="px-6 py-4 font-medium">{{ emp.full_name }}</td>
            <td class="px-6 py-4">{{ emp.email || '—' }}</td>
            <td class="px-6 py-4">{{ emp.phone || '—' }}</td>
            <td class="px-6 py-4">{{ emp.designation || '—' }}</td>
            <td class="px-6 py-4">
              <div class="flex justify-end gap-3">

                <!-- VIEW -->
                <Link
                  :href="route('employees.show', emp.id)"
                  class="text-gray-600 hover:text-gray-800"
                >
                  <EyeIcon class="w-5 h-5"/>
                </Link>

                <!-- EDIT -->
                <Link
                  :href="route('employees.edit', emp.id)"
                  class="text-blue-600 hover:text-blue-800"
                >
                  <PencilSquareIcon class="w-5 h-5"/>
                </Link>

                <!-- DELETE -->
                <button
                  @click="deleteEmployee(emp.id)"
                  class="text-red-600 hover:text-red-800"
                >
                  <TrashIcon class="w-5 h-5"/>
                </button>

              </div>
            </td>
          </tr>

          <tr v-if="employees.data.length === 0">
            <td colspan="6" class="text-center py-6 text-gray-500">
              No employees found
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- PAGINATION -->
    <div
      v-if="employees.total > employees.per_page"
      class="flex justify-center mt-4 space-x-2 px-6 pb-4"
    >
      <Link
        v-for="link in employees.links"
        :key="link.label"
        :href="link.url || ''"
        v-html="link.label"
        class="px-3 py-1 border rounded text-sm hover:bg-gray-100 transition"
        :class="{
          'bg-blue-600 text-white': link.active,
          'text-gray-400 pointer-events-none': !link.url
        }"
      />
    </div>

    <div class="text-center text-sm text-gray-500 px-6 pb-4">
      Showing {{ employees.from }} to {{ employees.to }} of {{ employees.total }} employees
    </div>

  </div>

</div>
</template>