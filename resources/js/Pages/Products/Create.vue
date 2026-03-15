<script setup>
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

const emit = defineEmits(['created'])

const props = defineProps({
  categories: Array
})

/* ================= FORM ================= */

const form = useForm({
  name: '',
  price: '',
  code: '',
  description: '',
  category_id: ''
})

/* ================= SUBMIT ================= */

const submit = () => {

  form.post(route('products.store'), {

    onSuccess: () => {

      form.reset()

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

<!-- CATEGORY -->
<div>

<label class="block text-xs font-medium text-slate-400 mb-1">
Category
</label>

<select
v-model="form.category_id"
class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
>

<option value="">Select Category</option>

<option
v-for="category in categories"
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
/>

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