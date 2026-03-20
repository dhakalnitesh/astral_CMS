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
  services: Object,
  filters: Object,
  details:Object,
})
// console.log('details data:',props.details);
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

// Payment Status Badge
const getStatus = (service) => {
  if (service.due_amount <= 0) return 'Paid'
  if (service.paid_amount > 0) return 'Partial'
  return 'Due'
}
</script>

<template>
<div class="p-6">

  <div class="bg-white shadow-md rounded-lg overflow-hidden">

    <!-- TITLE -->
    <div class="px-6 py-4 border-b border-gray-200">
      <h1 class="text-2xl font-bold text-gray-800">Services Information</h1>
    </div>

    <!-- SEARCH -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between px-6 py-4 gap-4 border-b border-gray-200">

      <div class="flex flex-col sm:flex-row gap-4 sm:gap-2 flex-1">
        <input
          v-model="search"
          type="text"
          placeholder="Search services..."
          class="w-full border border-gray-300 rounded-lg pl-4 pr-4 py-2 shadow-sm focus:ring focus:ring-blue-200"
        />

        <select v-model="perPage" class="w-28 border border-gray-300 rounded-lg py-2 px-2">
          <option value="5">5 rows</option>
          <option value="10">10 rows</option>
          <option value="25">25 rows</option>
          <option value="50">50 rows</option>
        </select>
      </div>

      <Link
        :href="route('services.create')"
        class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow"
      >
        <PlusIcon class="w-5 h-5"/> Add Service
      </Link>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">

        <thead class="bg-gray-100 uppercase text-gray-600 text-xs">
          <tr>
            <th class="px-6 py-3">SN</th>
            <th class="px-6 py-3">Customer</th>
            <th class="px-6 py-3">Projects</th>
            <th class="px-6 py-3">Total</th>
            <th class="px-6 py-3">Paid</th>
            <th class="px-6 py-3">Due</th>
            <th class="px-6 py-3">Status</th>
            <th class="px-6 py-3">Start</th>
            <th class="px-6 py-3">End</th>
            <th class="px-6 py-3 text-right">Actions</th>
          </tr>
        </thead>

        <tbody class="bg-white divide-y divide-gray-200">

          <tr
            v-for="(service, index) in services.data"
            :key="service.id"
            class="hover:bg-gray-50 transition"
          >
            <!-- SN -->
            <td class="px-6 py-4">
              {{ services.from + index }}
            </td>

            <!-- Customer -->
            <td class="px-6 py-4 font-medium">
              {{ service.customer?.name }}
            </td>

            <!-- Projects -->
            <td class="px-6 py-4">
              <div v-if="service.details?.length">
                <ul class="space-y-1">
                  <li
                    v-for="d in service.details"
                    :key="d.id"
                    class="flex justify-between"
                  >
                    <span>{{ d.product.name }}</span>
                    <!-- <span class="text-gray-500 text-xs">
                      Rs. {{ Number(d.final_price || 0) +
                        Number(d.installation_charge || 0) +
                        Number(d.hosting_charge || 0) +
                        Number(d.server_charge || 0) +
                        Number(d.maintenance_charge || 0)
                      }}
                    </span> -->
                  </li>
                </ul>

                <div class="text-xs text-gray-400 mt-1">
                  {{ service.details.length }} project(s)
                </div>
              </div>
              <div v-else>—</div>
            </td>

            <!-- Total -->
            <td class="px-6 py-4 font-semibold text-green-600">
              Rs. {{ service.total_amount }}
            </td>

            <!-- Paid -->
            <td class="px-6 py-4 text-blue-600">
              Rs. {{ service.paid_amount }}
            </td>

            <!-- Due -->
            <td class="px-6 py-4 text-red-600">
              Rs. {{ service.due_amount }}
            </td>

            <!-- Status -->
            <td class="px-6 py-4">
              <span
                class="px-2 py-1 text-xs rounded-full"
                :class="{
                  'bg-green-100 text-green-700': getStatus(service) === 'Paid',
                  'bg-yellow-100 text-yellow-700': getStatus(service) === 'Partial',
                  'bg-red-100 text-red-700': getStatus(service) === 'Due'
                }"
              >
                {{ getStatus(service) }}
              </span>
            </td>

            <!-- Dates -->
            <td class="px-6 py-4">
              {{ service.date || '—' }}
            </td>

            <td class="px-6 py-4">
              {{ service.date || '—' }}
            </td>

            <!-- ACTIONS -->
            <td class="px-6 py-4">
              <div class="flex justify-end gap-3">

                <Link
                  :href="route('services.show', service.id)"
                  class="text-gray-600 hover:text-gray-800"
                >
                  <EyeIcon class="w-5 h-5"/>
                </Link>

                <Link
                  :href="route('services.edit', service.id)"
                  class="text-blue-600 hover:text-blue-800"
                >
                  <PencilSquareIcon class="w-5 h-5"/>
                </Link>

                <button
                  @click="deleteService(service.id)"
                  class="text-red-600 hover:text-red-800"
                >
                  <TrashIcon class="w-5 h-5"/>
                </button>

              </div>
            </td>

          </tr>

          <!-- EMPTY -->
          <tr v-if="services.data.length === 0">
            <td colspan="10" class="text-center py-6 text-gray-500">
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
        class="px-3 py-1 border rounded text-sm hover:bg-gray-100"
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