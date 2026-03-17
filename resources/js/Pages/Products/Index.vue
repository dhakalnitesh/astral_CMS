<script setup>
import { Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import axios from 'axios'
import { ref, watch } from 'vue'

import Modal from '@/Components/Common/Modal.vue'
import CreateProduct from './Create.vue'
import EditProduct from './Edit.vue'

import {
  PencilSquareIcon,
  TrashIcon,
  PlusIcon,
  MagnifyingGlassIcon
} from '@heroicons/vue/24/outline'

// Props from controller
const props = defineProps({
  products: Object,
  filters: Object,
  categories: Array,
  employees: Array,
})

// STATE
const showCreateModal = ref(false)
const showEditModal = ref(false)
const editingProduct = ref(null)
const search = ref(props.filters?.search || '')
const perPage = ref(props.filters?.perPage || 10)

// SEARCH + PER PAGE WATCH
watch([search, perPage], () => {
  router.get(
    route('products.index'),
    { search: search.value, perPage: perPage.value },
    { preserveState: true, replace: true }
  )
})
console.log('category:',props.categories);
// PRODUCT CREATED
const productCreated = () => {
  showCreateModal.value = false
  router.reload({ only: ['products'] })
}

// OPEN EDIT MODAL
const openEditModal = (product) => {
  editingProduct.value = product
  showEditModal.value = true
}

// PRODUCT UPDATED
const productUpdated = () => {
  showEditModal.value = false
  router.reload({ only: ['products'] })
}

// DELETE
const deleteProduct = async (id) => {
  if (!confirm('Delete this product?')) return

  try {
    await axios.delete(route('products.destroy', id))
    router.reload({ only: ['products'] })
  } catch (error) {
    alert('Failed to delete product')
  }
}
</script>

<template>
<div class="p-6">

  <!-- CARD -->
  <div class="bg-white shadow-md rounded-lg overflow-hidden">

    <!-- TITLE -->
    <div class="px-6 py-4 border-b border-gray-200">
      <h1 class="text-2xl font-bold text-gray-800">Product Information</h1>
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

      <!-- Right: Add Product -->
      <div class="flex-shrink-0">
       
      <button
        @click="showCreateModal = true"
        class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow"
      >
        <PlusIcon class="w-5 h-5"/> Add Product
      </button>
      </div>

    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200 text-sm text-gray-700">

        <!-- HEAD -->
        <thead class="bg-gray-100 uppercase text-gray-600 text-left text-xs">
          <tr>
            <th class="px-6 py-3">SN</th>
            <th class="px-6 py-3">Code</th>
            <th class="px-6 py-3">Name</th>
            <th class="px-6 py-3">Category</th>
            <th class="px-6 py-3">Price</th>
            <th class="px-6 py-3">Created At</th>
            <th class="px-6 py-3 text-right">Actions</th>
          </tr>
        </thead>

        <!-- BODY -->
        <tbody class="bg-white divide-y divide-gray-200">
          <tr
            v-for="(product, index) in products.data"
            :key="product.id"
            class="hover:bg-gray-50 transition-colors"
          >
            <td class="px-6 py-4">{{ products.from + index }}</td>
            <td class="px-6 py-4 font-medium">{{ product.code }}</td>
            <td class="px-6 py-4 font-medium">{{ product.name }}</td>
            <td class="px-6 py-4">{{ product.category?.name || '—' }}</td>
            <td class="px-6 py-4">Rs. {{ product.price }}</td>
            <td class="px-6 py-4">{{ new Date(product.created_at).toLocaleDateString() }}</td>
            <td class="px-6 py-4">
              <div class="flex justify-end gap-3">
                <button @click="openEditModal(product)" class="text-blue-600 hover:text-blue-800">
                  <PencilSquareIcon class="w-5 h-5"/>
                </button>
                <button @click="deleteProduct(product.id)" class="text-red-600 hover:text-red-800">
                  <TrashIcon class="w-5 h-5"/>
                </button>
              </div>
            </td>
          </tr>

          <tr v-if="products.data.length === 0">
            <td colspan="7" class="text-center py-6 text-gray-500">No products found</td>
          </tr>
        </tbody>

      </table>

      <!-- PAGINATION -->
      <div v-if="products.total > perPage" class="flex justify-center mt-4 space-x-2 px-6 pb-4">
        <Link
          v-for="link in products.links"
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

      <!-- PAGE INFO -->
      <div class="text-center text-sm text-gray-500 px-6 pb-4">
        Showing {{ products.from }} to {{ products.to }} of {{ products.total }} products
      </div>

    </div>

  </div>

  <!-- CREATE MODAL -->
  <Modal :show="showCreateModal" @close="showCreateModal = false">
    <CreateProduct
      :categories="categories"
      :employees="employees"
      @created="productCreated"
    />
  </Modal>

  <!-- EDIT MODAL -->
  <Modal :show="showEditModal" @close="showEditModal = false">
    <EditProduct
      v-if="editingProduct"
      :product="editingProduct"
      :categories="categories"
      :employees="employees"
      @updated="productUpdated"
    />
  </Modal>

</div>
</template>