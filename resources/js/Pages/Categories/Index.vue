<script setup>
import { Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import axios from 'axios'
import { ref, watch } from 'vue'

import {
  PencilSquareIcon,
  TrashIcon,
  PlusIcon,
  MagnifyingGlassIcon
} from '@heroicons/vue/24/outline'

const props = defineProps({
  categories: Array,
  filters: Object
})

const search = ref(props.filters?.search || '')

/* SEARCH */
watch(search, (value) => {
  router.get(
    route('categories.index'),
    { search: value },
    { preserveState: true, replace: true }
  )
})

/* DELETE */
const deleteCategory = async (id) => {
  if (!confirm('Delete this category?')) return

  try {
    await axios.delete(route('categories.destroy', id))
    router.reload({ only: ['categories'] })
  } catch (error) {
    alert('Failed to delete category')
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
<div class="flex items-center mb-6">

<!-- SEARCH FIELD -->
<div class="relative flex-1 mr-4">

<input
v-model="search"
type="text"
placeholder="Search category..."
class="w-full border border-gray-300 rounded-lg pl-10 pr-4 py-2 shadow-sm focus:ring focus:ring-blue-200"
/>

<MagnifyingGlassIcon
class="w-5 h-5 text-gray-400 absolute left-3 top-2.5"
/>

</div>


<!-- ADD PRODUCT BUTTON -->
<Link
:href="route('categories.create')"
class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
>

<PlusIcon class="w-5 h-5"/>

Add Category

</Link>

</div>



<!-- TABLE -->
<div class="bg-white shadow rounded-lg overflow-hidden">

<table class="w-full">

<thead class="bg-gray-50 text-left text-sm text-gray-600">

<tr>
<th class="px-6 py-3">Name</th>

<th class="px-6 py-3">Description</th>
<th class="px-6 py-3 text-right">Actions</th>
</tr>

</thead>


<tbody>

<tr
v-for="product in products"
:key="product.id"
class="border-t hover:bg-gray-50"
>

<td class="px-6 py-3">
{{ product.name }}
</td>

<td class="px-6 py-3">
Rs. {{ product.price }}
</td>

<td class="px-6 py-3 text-gray-600">
{{ product.description }}
</td>


<td class="px-6 py-3">

<div class="flex justify-end gap-4">

<!-- EDIT -->
<Link
:href="route('products.edit', product.id)"
class="text-blue-600 hover:text-blue-800"
>

<PencilSquareIcon class="w-5 h-5"/>

</Link>


<!-- DELETE -->
<button
@click="deleteProduct(product.id)"
class="text-red-600 hover:text-red-800"
>

<TrashIcon class="w-5 h-5"/>

</button>

</div>

</td>

</tr>


<tr v-if="products.length === 0">
<td colspan="4" class="text-center py-6 text-gray-500">
No products found
</td>
</tr>

</tbody>

</table>

</div>

</div>

</template>