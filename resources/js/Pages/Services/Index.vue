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
  services: Object,  // Assuming the services object includes projects
  filters: Object
})

const search = ref(props.filters?.search || '')
const perPage = ref(props.filters?.perPage || 10)

// Watch search and perPage
watch([search, perPage], () => {
  router.get(
    route('services.index'),
    { search: search.value, perPage: perPage.value },
    { preserveState: true, replace: true }
  )
})

// Delete service
const deleteService = async (id) => {
  if (!confirm('Delete this service?')) return

  try {
    await axios.delete(route('services.destroy', id))
    router.reload({ only: ['services'] })
  } catch (error) {
    alert('Failed to delete service')
  }
}
</script>

<template>
<div class="p-6">

  <!-- CARD -->
  <div class="bg-white shadow-md rounded-lg overflow-hidden">

    <!-- TITLE -->
    <div class="px-6 py-4 border-b border-gray-200">
      <h1 class="text-2xl font-bold text-gray-800">Services Information</h1>
    </div>

    <!-- SEARCH + ACTIONS -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between px-6 py-4 gap-4 border-b border-gray-200">

      <!-- Left: Search + Rows -->
      <div class="flex flex-col sm:flex-row gap-4 sm:gap-2 flex-1">

        <div class="relative flex-1">
          <input
            v-model="search"
            type="text"
            placeholder="Search services..."
            class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 shadow-sm focus:ring focus:ring-blue-200"
          />
        </div>

        <select v-model="perPage" class="w-28 border border-gray-300 rounded-lg py-2 px-2">
          <option value="5">5 rows</option>
          <option value="10">10 rows</option>
          <option value="25">25 rows</option>
          <option value="50">50 rows</option>
        </select>

      </div>

      <!-- Right: Add Service -->
      <div class="flex-shrink-0">
        <Link
          :href="route('services.create')"
          class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow"
        >
          <PlusIcon class="w-5 h-5"/> Add Service
        </Link>
      </div>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">

        <thead class="bg-gray-100 uppercase text-gray-600 text-left text-xs">
          <tr>
            <th class="px-6 py-3">SN</th>
            <th class="px-6 py-3">Customer</th>
            <th class="px-6 py-3">Start Date</th>
            <th class="px-6 py-3">End Date</th>
            <th class="px-6 py-3">Projects</th>
            <th class="px-6 py-3 text-right">Actions</th>
          </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">
          <tr
            v-for="(service, index) in services.data"
            :key="service.id"
            class="hover:bg-gray-50 transition-colors"
          >
            <td class="px-6 py-4">{{ services.from + index }}</td>
            <td class="px-6 py-4 font-medium">{{ service.customer.name }}</td>
            <td class="px-6 py-4">{{ service.start_date || '—' }}</td>
            <td class="px-6 py-4">{{ service.end_date || '—' }}</td>

            <!-- Project Names -->
            <td class="px-6 py-4">
              <div v-if="service.projects?.length">
                <ul class="list-disc pl-4">
                  <li v-for="project in service.projects" :key="project.id">
                    {{ project.project_name }}
                  </li>
                </ul>
              </div>
              <div v-else>
                —
              </div>
            </td>

            <td class="px-6 py-4">
              <div class="flex justify-end gap-3">

                <!-- VIEW -->
                <Link
                  :href="route('services.show', service.id)"
                  class="text-gray-600 hover:text-gray-800"
                >
                  <EyeIcon class="w-5 h-5"/>
                </Link>

                <!-- EDIT -->
                <Link
                  :href="route('services.edit', service.id)"
                  class="text-blue-600 hover:text-blue-800"
                >
                  <PencilSquareIcon class="w-5 h-5"/>
                </Link>

                <!-- DELETE -->
                <button
                  @click="deleteService(service.id)"
                  class="text-red-600 hover:text-red-800"
                >
                  <TrashIcon class="w-5 h-5"/>
                </button>

              </div>
            </td>
          </tr>

          <tr v-if="services.data.length === 0">
            <td colspan="6" class="text-center py-6 text-gray-500">
              No services found
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- PAGINATION -->
    <div
      v-if="services.total > services.per_page"
      class="flex justify-center mt-4 space-x-2 px-6 pb-4"
    >
      <Link
        v-for="link in services.links"
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
      Showing {{ services.from }} to {{ services.to }} of {{ services.total }} services
    </div>

  </div>

</div>
</template>
