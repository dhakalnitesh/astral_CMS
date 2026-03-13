<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
    product: Object,
    categories: Array,
})

const form = useForm({
    name: props.product.name || '',
    price: props.product.price || '',
    description: props.product.description || '',
    category_id: props.product.category_id || '',
})

const submitForm = () => {
    form.put(route('products.update', props.product.id))
}
</script>

<template>
  <div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Product</h2>
    
    <form @submit.prevent="submitForm" class="space-y-4">

      <!-- Category -->
      <div>
        <label class="block text-gray-700 font-semibold mb-1">Category</label>
        <select 
          v-model="form.category_id"
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
        >
          <option value="" disabled>Select a category</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>

        <div v-if="form.errors.category_id" class="text-red-500 text-sm mt-1">
          {{ form.errors.category_id }}
        </div>
      </div>

      <!-- Product Name -->
      <div>
        <label class="block text-gray-700 font-semibold mb-1">Product Name</label>
        <input 
          type="text"
          v-model="form.name"
          placeholder="Enter product name"
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
        />

        <div v-if="form.errors.name" class="text-red-500 text-sm mt-1">
          {{ form.errors.name }}
        </div>
      </div>

      <!-- Price -->
      <div>
        <label class="block text-gray-700 font-semibold mb-1">Price (Rs)</label>
        <input 
          type="number"
          v-model="form.price"
          placeholder="Enter product price"
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
        />

        <div v-if="form.errors.price" class="text-red-500 text-sm mt-1">
          {{ form.errors.price }}
        </div>
      </div>

      <!-- Description -->
      <div>
        <label class="block text-gray-700 font-semibold mb-1">Description</label>
        <textarea 
          v-model="form.description"
          placeholder="Enter product description"
          rows="4"
          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
        ></textarea>

        <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">
          {{ form.errors.description }}
        </div>
      </div>

      <!-- Submit -->
      <div>
        <button
          type="submit"
          :disabled="form.processing"
          class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition disabled:opacity-50"
        >
          <span v-if="form.processing">Updating...</span>
          <span v-else>Save Changes</span>
        </button>
      </div>

    </form>
  </div>
</template>