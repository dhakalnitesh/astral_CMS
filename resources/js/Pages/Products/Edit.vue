<script setup>
import { ref, watch, onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

const emit = defineEmits(['updated'])

const props = defineProps({
  product: Object, // The product to edit
  categories: Array,
  employees: Array
})

/* ================= FORM ================= */
const form = useForm({
  name: '',
  price: '',
  code: '',
  description: '',
  project_lead: '',
  total_developers: '',
  start_date: '',
  end_date: '',
  category_id: '',
  new_category: '',
  status: 'pending', // ✅ added
})

const categoryType = ref('existing')

/* ================= WATCH ================= */
watch(categoryType, (val) => {
  if (val === 'existing') {
    form.new_category = ''
  } else {
    form.category_id = ''
  }
})

/* ================= ON MOUNT (For Editing) ================= */
onMounted(() => {
  // Populate the form with the existing product data
  if (props.product) {
    form.name = props.product.name
    form.price = props.product.price
    form.code = props.product.code
    form.description = props.product.description
    form.project_lead = props.product.project_lead
    form.total_developers = props.product.total_developers
    form.start_date = props.product.start_date
    form.end_date = props.product.end_date
    form.category_id = props.product.category_id
    form.status = props.product.status
  }
})

/* ================= SUBMIT ================= */
const submit = () => {
  form.clearErrors()
  let hasError = false

  if (categoryType.value === 'existing' && !form.category_id) {
    form.errors.category_id = 'Please select a category'
    hasError = true
  }

  if (categoryType.value === 'new' && !form.new_category.trim()) {
    form.errors.new_category = 'Please enter a new category'
    hasError = true
  }

  if (!form.name) {
    form.errors.name = 'Product name is required'
    hasError = true
  }

  if (!form.price) {
    form.errors.price = 'Price is required'
    hasError = true
  }

  if (!form.project_lead) {
    form.errors.project_lead = 'Please select a project lead'
    hasError = true
  }

  if (!form.status) {
    form.errors.status = 'Please select status'
    hasError = true
  }

  if (hasError) return

  form.put(route('products.update', props.product.id), {
    onSuccess: () => {
      emit('updated')
    }
  })
}
</script>

<template>
<form @submit.prevent="submit" class="space-y-6">

  <div class="bg-white shadow rounded-lg p-6">

    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
      Edit Product
    </h2>

    <!-- ================= GRID 3/3 ================= -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

      <!-- CATEGORY TOGGLE -->
      <div class="md:col-span-3">
        <label class="block text-xs font-medium text-slate-400 mb-1">
          Category Type
        </label>
        <div class="flex items-center gap-4">
          <label class="flex items-center gap-2">
            <input type="radio" value="existing" v-model="categoryType" />
            Existing Category
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" value="new" v-model="categoryType" />
            New Category
          </label>
        </div>
      </div>

      <!-- EXISTING CATEGORY -->
      <div v-if="categoryType === 'existing'">
        <label class="block text-xs font-medium text-slate-400 mb-1">
          Select Category
        </label>
        <select v-model="form.category_id"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
          <option value="">Select Category</option>
          <option v-for="category in props.categories"
            :key="category.id"
            :value="category.id">
            {{ category.name }}
          </option>
        </select>
        <p v-if="form.errors.category_id" class="text-red-500 text-xs mt-1">
          {{ form.errors.category_id }}
        </p>
      </div>

      <!-- NEW CATEGORY -->
      <div v-if="categoryType === 'new'">
        <label class="block text-xs font-medium text-slate-400 mb-1">
          Add New Category
        </label>
        <input v-model="form.new_category"
          type="text"
          placeholder="Enter new category"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"/>
        <p v-if="form.errors.new_category" class="text-red-500 text-xs mt-1">
          {{ form.errors.new_category }}
        </p>
      </div>

      <!-- PRODUCT NAME -->
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">
          Product Name
        </label>
        <input v-model="form.name" type="text"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"/>
        <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">
          {{ form.errors.name }}
        </p>
      </div>

      <!-- PRODUCT CODE -->
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">
          Product Code
        </label>
        <input v-model="form.code" type="text"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"/>
      </div>

      <!-- PRICE -->
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">
          Price
        </label>
        <input v-model="form.price" type="number"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"/>
        <p v-if="form.errors.price" class="text-red-500 text-xs mt-1">
          {{ form.errors.price }}
        </p>
      </div>

      <!-- STATUS -->
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">
          Status
        </label>
        <select v-model="form.status"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
          <option value="pending">Pending</option>
          <option value="completed">Completed</option>
        </select>
        <p v-if="form.errors.status" class="text-red-500 text-xs mt-1">
          {{ form.errors.status }}
        </p>
      </div>

      <!-- PROJECT LEAD -->
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">
          Project Lead
        </label>
        <select v-model="form.project_lead"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm">
          <option value="">Select Project Lead</option>
          <option v-for="employee in props.employees"
            :key="employee.id"
            :value="employee.id">
            {{ employee.full_name }}
          </option>
        </select>
        <p v-if="form.errors.project_lead" class="text-red-500 text-xs mt-1">
          {{ form.errors.project_lead }}
        </p>
      </div>

      <!-- TOTAL DEVELOPERS -->
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">
          Total Developers
        </label>
        <input v-model="form.total_developers" type="number"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"/>
      </div>

      <!-- START DATE -->
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">
          Start Date
        </label>
        <input v-model="form.start_date" type="date"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"/>
      </div>

      <!-- END DATE -->
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">
          End Date
        </label>
        <input v-model="form.end_date" type="date"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"/>
      </div>
  
    </div>
    <div>

  <!-- DESCRIPTION -->
  
    <h4 class="text-sm font-semibold text-slate-700 mb-2">
      Description
    </h4>
    <textarea v-model="form.description" rows="3"
      class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
      placeholder="Enter product description"></textarea>
    </div>
  </div>



  <!-- FOOTER -->
  <div class="flex justify-end gap-3">
    <button type="button"
      @click="$emit('updated')"
      class="px-4 py-2 text-sm rounded-lg border border-slate-300 hover:bg-slate-100">
      Cancel
    </button>

    <button type="submit"
      :disabled="form.processing"
      class="px-5 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700">
      {{ form.processing ? 'Saving...' : 'Update Product' }}
    </button>
  </div>

</form>
</template>