<script setup>
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

const emit = defineEmits(['created'])

// ================= FORM =================
const form = useForm({
  name: '',
  description: ''
})

// ================= SUBMIT =================
const submit = () => {
  form.post(route('categories.store'), {
    onSuccess: () => {
      form.reset()
      emit('created')
    }
  })
}
</script>

<template>

<!-- Center form and limit width -->
<div class="flex justify-center p-4">
  <form @submit.prevent="submit" class="space-y-6 w-full max-w-md p-6 border rounded-xl shadow-lg">

    <!-- ================= TITLE ================= -->
    <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
      Add Category
    </h2>

    <!-- ================= CATEGORY NAME ================= -->
    <div>
      <label class="block text-xs font-medium text-slate-600 mb-1">
        Category Name
      </label>
      <input
        v-model="form.name"
        type="text"
        placeholder="Enter category name"
        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
      />
      <p v-if="form.errors.name" class="text-red-500 text-xs mt-1">
        {{ form.errors.name }}
      </p>
    </div>

    <!-- ================= DESCRIPTION ================= -->
    <div>
      <label class="block text-xs font-medium text-slate-600 mb-1">
        Description
      </label>
      <textarea
        v-model="form.description"
        rows="3"
        placeholder="Category description"
        class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
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
        {{ form.processing ? 'Saving...' : 'Create Category' }}
      </button>
    </div>

  </form>
</div>

</template>