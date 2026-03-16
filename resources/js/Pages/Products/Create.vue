<script setup>
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

const emit = defineEmits(['created'])

const props = defineProps({
  categories: Array,   // existing categories
  employees: Array     // employees for project lead dropdown
})

/* ================= FORM ================= */
const form = useForm({
  name: '',
  price: '',
  code: '',
  description: '',
  project_lead: '',        // stores employee id
  total_developers: '',
  start_date: '',
  end_date: '',
  category_id: '',
  new_category: ''
})

// Radio toggle: "existing" or "new"
const categoryType = ref('existing')

/* ================= WATCH ================= */
// Clear opposing field when switching category type
watch(categoryType, (val) => {
  if (val === 'existing') {
    form.new_category = ''
    delete form.errors.new_category
  } else {
    form.category_id = ''
    delete form.errors.category_id
  }
})

/* ================= SUBMIT ================= */
const submit = () => {
  // Validation before submit
  if (categoryType.value === 'existing' && !form.category_id) {
    form.errors.category_id = 'Please select a category'
    return
  }

  if (categoryType.value === 'new' && !form.new_category.trim()) {
    form.errors.new_category = 'Please enter a new category'
    return
  }

  if (!form.project_lead) {
    form.errors.project_lead = 'Please select a project lead'
    return
  }

  form.post(route('products.store'), {
    onSuccess: () => {
      form.reset()
      categoryType.value = 'existing'
      emit('created')
    }
  })
}
</script>

<template>
<form @submit.prevent="submit" class="space-y-6">

  <!-- ================= PRODUCT INFO ================= -->
  <div>
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
      Add New Product
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">

      <!-- CATEGORY TOGGLE -->
      <div class="mb-2 col-span-2">
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

      <!-- EXISTING CATEGORY SELECT -->
      <div v-if="categoryType === 'existing'">
        <label class="block text-xs font-medium text-slate-400 mb-1">
          Select Category
        </label>
        <select
          v-model="form.category_id"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
        >
          <option value="">Select Category</option>
          <option
            v-for="category in props.categories"
            :key="category.id"
            :value="category.id"
          >
            {{ category.name }}
          </option>
        </select>
        <p v-if="form.errors.category_id" class="text-red-500 text-xs mt-1">
          {{ form.errors.category_id }}
        </p>
      </div>

      <!-- NEW CATEGORY INPUT -->
      <div v-if="categoryType === 'new'">
        <label class="block text-xs font-medium text-slate-400 mb-1">
          Add New Category
        </label>
        <input
          v-model="form.new_category"
          type="text"
          placeholder="Enter new category"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
        />
        <p v-if="form.errors.new_category" class="text-red-500 text-xs mt-1">
          {{ form.errors.new_category }}
        </p>
      </div>

      <!-- PRODUCT NAME -->
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">
          Product Name
        </label>
        <input
          v-model="form.name"
          type="text"
          placeholder="Enter product name"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
        />
        <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">
          {{ form.errors.name }}
        </p>
      </div>

      <!-- PRODUCT CODE -->
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">
          Product Code
        </label>
        <input
          v-model="form.code"
          type="text"
          placeholder="Enter product code"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
        />
        <p v-if="form.errors.code" class="text-red-500 text-xs mt-1">
          {{ form.errors.code }}
        </p>
      </div>

      <!-- PRICE -->
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">
          Price
        </label>
        <input
          v-model="form.price"
          type="number"
          placeholder="Enter product price"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
        />
        <p v-if="form.errors.price" class="text-red-500 text-xs mt-1">
          {{ form.errors.price }}
        </p>
      </div>

      <!-- PROJECT LEAD DROPDOWN -->
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">
          Project Lead
        </label>
        <select
          v-model="form.project_lead"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
        >
          <option value="">Select Project Lead</option>
          <option
            v-for="employee in props.employees"
            :key="employee.id"
            :value="employee.id"
          >
            {{ employee.name }}
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
        <input
          v-model="form.total_developers"
          type="number"
          placeholder="Enter total developers"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
        />
        <p v-if="form.errors.total_developers" class="text-red-500 text-xs mt-1">
          {{ form.errors.total_developers }}
        </p>
      </div>

      <!-- START DATE -->
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">
          Start Date
        </label>
        <input
          v-model="form.start_date"
          type="date"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
        />
        <p v-if="form.errors.start_date" class="text-red-500 text-xs mt-1">
          {{ form.errors.start_date }}
        </p>
      </div>

      <!-- END DATE -->
      <div>
        <label class="block text-xs font-medium text-slate-600 mb-1">
          End Date
        </label>
        <input
          v-model="form.end_date"
          type="date"
          class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
        />
        <p v-if="form.errors.end_date" class="text-red-500 text-xs mt-1">
          {{ form.errors.end_date }}
        </p>
      </div>

    </div>
  </div>

  <!-- ================= DESCRIPTION ================= -->
  <div class="border-t pt-5">
    <h4 class="text-sm font-semibold text-slate-700 mb-3">
      Description
    </h4>
    <textarea
      v-model="form.description"
      rows="2"
      placeholder="Product description"
      class="w-full border border-slate-300 rounded-lg px-3 py-1 text-sm"
    ></textarea>
    <p v-if="form.errors.description" class="text-red-500 text-xs mt-1">
      {{ form.errors.description }}
    </p>
  </div>

  <!-- ================= FOOTER ================= -->
  <div class="flex justify-end gap-3 pt-4 border-t">
    <button
      type="button"
      @click="$emit('created')"
      class="px-4 py-2 text-sm rounded-lg border border-slate-300 hover:bg-slate-100"
    >
      Cancel
    </button>

    <button
      type="submit"
      :disabled="form.processing"
      class="px-5 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700"
    >
      {{ form.processing ? 'Saving...' : 'Create Product' }}
    </button>
  </div>

</form>
</template>