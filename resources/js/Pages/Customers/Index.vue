<script setup>
import { Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import axios from 'axios'
import { ref, watch } from 'vue'
import Modal from '@/Components/Common/Modal.vue'
import CreateCustomer from './Create.vue'

import {
  PencilSquareIcon,
  TrashIcon,
  PlusIcon,
  MagnifyingGlassIcon
} from '@heroicons/vue/24/outline'

// Props
const props = defineProps({
  customers: Object,
  filters: Object
})

// Local State
const showCreateModal = ref(false)
const search = ref(props.filters?.search || '')

// After create
const customerCreated = () => {
  showCreateModal.value = false
  router.reload({ only: ['customers'] })
}

// Search
watch(search, (value) => {
  router.get(
    route('customers.index'),
    { search: value },
    { preserveState: true, replace: true }
  )
})

// Delete
const deleteCustomer = async (id) => {
  if (!confirm('Delete this customer?')) return

  try {
    await axios.delete(route('customers.destroy', id))
    router.reload({ only: ['customers'] })
  } catch (error) {
    alert('Failed to delete customer')
  }
}
</script>

<template>
<div class="p-6">

  <!-- TITLE -->
  <h1 class="text-2xl font-bold mb-6 text-gray-800">
    Customers
  </h1>

  <!-- SEARCH + ADD -->
  <div class="flex flex-col sm:flex-row sm:items-center mb-6 gap-4 sm:gap-6">

    <!-- SEARCH -->
    <div class="relative flex-1">
      <input
        v-model="search"
        type="text"
        placeholder="Search customer..."
        class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 shadow-sm focus:ring focus:ring-blue-200"
      />
      <MagnifyingGlassIcon class="w-5 h-5 text-gray-400 absolute left-3 top-2.5"/>
    </div>

    <!-- ADD BUTTON -->
    <button
      @click="showCreateModal = true"
      class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow"
    >
      <PlusIcon class="w-5 h-5"/> Add Customer
    </button>

  </div>

  <!-- TABLE -->
  <div class="bg-white shadow-md rounded-lg overflow-hidden">

    <table class="w-full text-sm text-gray-700">

      <!-- HEAD -->
      <thead class="bg-gray-100 uppercase text-gray-600 text-left text-xs">
        <tr>
          <th class="px-6 py-3">SN</th>
          <th class="px-6 py-3">Name</th>
          <th class="px-6 py-3">Email</th>
          <th class="px-6 py-3">Phone</th>
          <th class="px-6 py-3">Company</th>
          <th class="px-6 py-3">Status</th>
          <th class="px-6 py-3 text-right">Actions</th>
        </tr>
      </thead>

      <!-- BODY -->
      <tbody>

        <tr
          v-for="(customer, index) in customers.data"
          :key="customer.id"
          class="border-b hover:bg-gray-50 transition-colors"
        >
          <td class="px-6 py-4">
            {{ customers.from + index }}
          </td>

          <td class="px-6 py-4 font-medium">
            {{ customer.name }}
          </td>

          <td class="px-6 py-4">
            {{ customer.email || '—' }}
          </td>

          <td class="px-6 py-4">
            {{ customer.phone || '—' }}
          </td>

          <td class="px-6 py-4">
            {{ customer.company_name || '—' }}
          </td>

          <td class="px-6 py-4">
            <span
              class="px-2 py-1 text-xs rounded"
              :class="customer.status === 'active'
                ? 'bg-green-100 text-green-700'
                : 'bg-red-100 text-red-700'"
            >
              {{ customer.status }}
            </span>
          </td>

          <td class="px-6 py-4">
            <div class="flex justify-end gap-3">

              <Link
                :href="route('customers.edit', customer.id)"
                class="text-blue-600 hover:text-blue-800"
              >
                <PencilSquareIcon class="w-5 h-5"/>
              </Link>

              <button
                @click="deleteCustomer(customer.id)"
                class="text-red-600 hover:text-red-800"
              >
                <TrashIcon class="w-5 h-5"/>
              </button>

            </div>
          </td>
        </tr>

        <!-- EMPTY -->
        <tr v-if="customers.data.length === 0">
          <td colspan="7" class="text-center py-6 text-gray-500">
            No customers found
          </td>
        </tr>

      </tbody>

    </table>

    <!-- PAGINATION -->
    <div
      v-if="customers.total > customers.per_page"
      class="flex justify-center mt-4 space-x-2"
    >
      <Link
        v-for="link in customers.links"
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

    <!-- INFO -->
    <div class="text-center text-sm text-gray-500 mt-2">
      Showing {{ customers.from }} to {{ customers.to }} of {{ customers.total }} customers
    </div>

    <!-- CREATE MODAL -->
    <Modal
      :show="showCreateModal"
      @close="showCreateModal = false"
    >
      <CreateCustomer
        @created="customerCreated"
      />
    </Modal>

  </div>

</div>
</template>