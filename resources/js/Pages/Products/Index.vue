<script setup>
import { Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import axios from 'axios'
import { ref, watch } from 'vue'
import Modal from '@/Components/Common/Modal.vue'
import CreateProduct from './Create.vue'

import {
  PencilSquareIcon,
  TrashIcon,
  PlusIcon,
  MagnifyingGlassIcon
} from '@heroicons/vue/24/outline'

// Close create modal and reload products
const productCreated = () => {
  showCreateModal.value = false
  router.reload({ only: ['products'] })
}

// Props from controller
const props = defineProps({
  products: Object, // pagination object
  filters: Object,
  categories: Array
})
// check the product which is not showing the category 
  // console.log(props.products);


// Local state
const showCreateModal = ref(false)
const search = ref(props.filters?.search || '')

// SEARCH
watch(search, (value) => {
  router.get(
    route('products.index'),
    { search: value },
    { preserveState: true, replace: true }
  )
})

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

  <!-- TITLE -->
  <h1 class="text-2xl font-bold mb-6 text-gray-800">
    Products
  </h1>

  <!-- SEARCH + ADD -->
  <div class="flex flex-col sm:flex-row sm:items-center mb-6 gap-4 sm:gap-6">

    <!-- SEARCH FIELD -->
    <div class="relative flex-1">
      <input
        v-model="search"
        type="text"
        placeholder="Search product..."
        class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 shadow-sm focus:ring focus:ring-blue-200"
      />
      <MagnifyingGlassIcon class="w-5 h-5 text-gray-400 absolute left-3 top-2.5"/>
    </div>

    <!-- ADD PRODUCT BUTTON -->
    <button
      @click="showCreateModal = true"
      class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow"
    >
      <PlusIcon class="w-5 h-5"/> Add Product
    </button>

  </div>

  <!-- DATA TABLE -->
  <div class="bg-white shadow-md rounded-lg overflow-hidden">

    <table class="w-full text-sm text-gray-700">
      <!-- HEADERS -->
      <thead class="bg-gray-100 uppercase text-gray-600 text-left text-xs">
        <tr>
          <th class="px-6 py-3">SN</th>
          <th class="px-6 py-3">Name</th>
          <th class="px-6 py-3">Category</th>
          <th class="px-6 py-3">Price</th>
          <!-- <th class="px-6 py-3">Stock</th> -->
          <th class="px-6 py-3">Created At</th>
          <th class="px-6 py-3 text-right">Actions</th>
        </tr>
      </thead>

      <!-- BODY -->
      <tbody>
        <tr
          v-for="(product, index) in products.data"
          :key="product.id"
          class="border-b hover:bg-gray-50 transition-colors"
        >
          <td class="px-6 py-4">{{ products.from + index }}</td>
          <td class="px-6 py-4 font-medium">{{ product.name }}</td>
          <td class="px-6 py-4">{{ product.category?.name|| '—' }}</td>
          <td class="px-6 py-4">Rs. {{ product.price }}</td>
          <td class="px-6 py-4">{{ new Date(product.created_at).toLocaleDateString() }}</td>
          <td class="px-6 py-4">
            <div class="flex justify-end gap-3">
              <Link
                :href="route('products.edit', product.id)"
                class="text-blue-600 hover:text-blue-800"
              >
                <PencilSquareIcon class="w-5 h-5"/>
              </Link>
              <button
                @click="deleteProduct(product.id)"
                class="text-red-600 hover:text-red-800"
              >
                <TrashIcon class="w-5 h-5"/>
              </button>
            </div>
          </td>
        </tr>

        <!-- EMPTY STATE -->
        <tr v-if="products.data.length === 0">
          <td colspan="7" class="text-center py-6 text-gray-500">
            No products found
          </td>
        </tr>
      </tbody>
    </table>

    <!-- PAGINATION LINKS: only if total > per_page -->
    <div v-if="products.total > products.per_page" class="flex justify-center mt-4 space-x-2">
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
    <div class="text-center text-sm text-gray-500 mt-2">
      Showing {{ products.from }} to {{ products.to }} of {{ products.total }} products
    </div>

    <!-- CREATE MODAL -->
    <Modal
      :show="showCreateModal"
      @close="showCreateModal = false"
    >
      <CreateProduct
        :categories="categories"
        @created="productCreated"
      />
    </Modal>

  </div>

</div>
</template>