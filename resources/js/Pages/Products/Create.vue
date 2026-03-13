<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { route } from 'ziggy-js'

const name = ref('')
const price = ref('')
const description = ref('')
const category_id = ref('')
const errors = ref({})

// Control modal visibility
const isModalOpen = ref(false)

const props = defineProps({
  categories: Array,
})

const openModal = () => {
  isModalOpen.value = true
}

const closeModal = () => {
  isModalOpen.value = false
}

const submitForm = async () => {
  try {
    errors.value = {}

    const data = {
      name: name.value,
      price: price.value,
      category_id: category_id.value,
      description: description.value,
    }

    await axios.post(route('products.store'), data)

    alert('Product created successfully')

    // Clear form
    name.value = ''
    price.value = ''
    description.value = ''
    category_id.value = ''

    closeModal() // close modal after success

  } catch (e) {
    if (e.response && e.response.status === 422) {
      errors.value = e.response.data.errors
    } else {
      alert("Something went wrong")
    }
  }
}
</script>

<template>
  <div>

    <!-- Button to open modal -->
    <button
      @click="openModal"
      class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition"
    >
      Add New Product
    </button>

    <!-- Modal Overlay -->
    <div
      v-if="isModalOpen"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    >
      <!-- Modal Container -->
      <div class="bg-white w-full max-w-xl rounded-xl shadow-lg p-8 relative">

        <!-- Close Button -->
        <button
          @click="closeModal"
          class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
        >
          ✕
        </button>

        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
          Add New Product
        </h2>

        <form @submit.prevent="submitForm" class="space-y-5">

          <!-- Product Name -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
            <input
              type="text"
              v-model="name"
              placeholder="Enter product name"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            />
            <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name[0] }}</p>
          </div>

          <!-- Product Price -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Product Price</label>
            <input
              type="number"
              v-model="price"
              placeholder="Enter product price"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            />
            <p v-if="errors.price" class="text-red-500 text-sm mt-1">{{ errors.price[0] }}</p>
          </div>

          <!-- Category -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select
              v-model="category_id"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            >
              <option value="" disabled>Select category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.name }}
              </option>
            </select>
            <p v-if="errors.category_id" class="text-red-500 text-sm mt-1">{{ errors.category_id[0] }}</p>
          </div>

          <!-- Description -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Product Description</label>
            <textarea
              v-model="description"
              rows="4"
              placeholder="Enter product description"
              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            ></textarea>
            <p v-if="errors.description" class="text-red-500 text-sm mt-1">{{ errors.description[0] }}</p>
          </div>

          <!-- Submit Button -->
          <div>
            <button
              type="submit"
              class="w-full bg-blue-600 text-white font-semibold py-2.5 rounded-lg hover:bg-blue-700 transition duration-200"
            >
              Create Product
            </button>
          </div>

        </form>

      </div>
    </div>

  </div>
</template>