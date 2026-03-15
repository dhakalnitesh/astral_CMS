<script setup>
import { useForm, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import { ref, watch, computed } from 'vue'
import {
  UserIcon,
  BuildingOfficeIcon
} from '@heroicons/vue/24/outline'

const emit = defineEmits(['created'])

/* ================= PROPS FROM LARAVEL ================= */

const page = usePage()

const categories = page.props.categories
const products = page.props.products

/* ================= CUSTOMER TYPE ================= */

const customerType = ref('individual')

/* ================= FORM ================= */

const form = useForm({
  name: '',
  email: '',
  company_name: '',
  phone: '',
  address: '',
  notes: '',
  status: 'active',
  type: 'individual',

  /* Service Relation */
  category_id: '',
  product_id: ''
})

watch(customerType, (value) => {
  form.type = value

  if (value === 'individual') {
    form.company_name = ''
  }
})

/* ================= FILTER PRODUCTS ================= */

const filteredProducts = computed(() => {
  if (!form.category_id) return []

  return products.filter(
  product => Number(product.category_id) === Number(form.category_id)
)
})

watch(() => form.category_id, () => {
  form.product_id = ''
})

/* ================= SUBMIT ================= */

const submit = () => {
  form.post(route('customers.store'), {
    onSuccess: () => {
      form.reset()
      customerType.value = 'individual'
      emit('created')
    }
  })
}
</script>

<template>

<form @submit.prevent="submit" class="space-y-6">

<!-- ================= TYPE ================= -->

<div>

<label class="block text-sm font-semibold text-slate-700 mb-3">
Customer Type
</label>

<div class="grid grid-cols-2 gap-3">

<button
type="button"
@click="customerType = 'individual'"
class="flex items-center justify-center gap-2 rounded-lg border py-2.5 text-sm font-medium transition"
:class="customerType === 'individual'
? 'border-blue-600 bg-blue-50 text-blue-700'
: 'border-slate-200 hover:bg-slate-50'"
>

<UserIcon class="w-4 h-4"/>
Individual

</button>

<button
type="button"
@click="customerType = 'business'"
class="flex items-center justify-center gap-2 rounded-lg border py-2.5 text-sm font-medium transition"
:class="customerType === 'business'
? 'border-blue-600 bg-blue-50 text-blue-700'
: 'border-slate-200 hover:bg-slate-50'"
>

<BuildingOfficeIcon class="w-4 h-4"/>
Business

</button>

</div>

</div>

<!-- ================= CONTACT ================= -->

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<!--Individual Name -->
<div v-if="customerType !== 'business'">
<label class="block text-xs font-medium text-slate-600 mb-1">
Full Name
</label>

<input
v-model="form.name"
type="text"
placeholder="John Doe"
class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
/>

<p v-if="form.errors.name" class="text-red-500 text-xs mt-1">
{{ form.errors.name }}
</p>
</div>

<!-- Company Name -->
<div
v-if="customerType === 'business'">
<label class="block text-xs font-medium text-slate-600 mb-1">
Company Name
</label>

<input
v-model="form.company_name"
type="text"
placeholder="Astral Teachsoft"
class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
/>

<p v-if="form.errors.company_name" class="text-red-500 text-xs mt-1">
{{ form.errors.company_name }}
</p>

</div>

<!-- Email -->
<div>
<label class="block text-xs font-medium text-slate-600 mb-1">
Email
</label>

<input
v-model="form.email"
type="email"
placeholder="john@example.com"
class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
/>

<p v-if="form.errors.email" class="text-red-500 text-xs mt-1">
{{ form.errors.email }}
</p>
</div>

<!-- Phone -->
<div>
<label class="block text-xs font-medium text-slate-600 mb-1">
Phone
</label>

<input
v-model="form.phone"
type="text"
placeholder="+977 98XXXXXXXX"
class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
/>
<p v-if="form.errors.phone" class="text-red-500 text-xs mt-1">
{{ form.errors.phone }}
</p>
</div>

<!-- Status -->
<div>
<label class="block text-xs font-medium text-slate-600 mb-1">
Status
</label>

<select
v-model="form.status"
class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
>
<option value="active">Active</option>
<option value="inactive">Inactive</option>
</select>
</div>

</div>

<!-- ================= COMPANY ================= -->
<!-- 
<div
v-if="customerType === 'business'"
class="border-t pt-5"
>

<h4 class="text-sm font-semibold text-slate-700 mb-3">
Company Information
</h4>

<input
v-model="form.company_name"
type="text"
placeholder="Company Name"
class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
/>

<p v-if="form.errors.company_name" class="text-red-500 text-xs mt-1">
{{ form.errors.company_name }}
</p>

</div> -->

<!-- ================= SERVICE ================= -->

<div class="border-t pt-5">

<h4 class="text-sm font-semibold text-slate-700 mb-3">
Service Selection
</h4>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

<!-- CATEGORY -->
<div>

<label class="block text-xs font-medium text-slate-600 mb-1">
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

<!-- PRODUCT -->
<div>

<label class="block text-xs font-medium text-slate-600 mb-1">
Product
</label>

<select
v-model="form.product_id"
class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
>

<option value="">Select Product</option>

<option
v-for="product in filteredProducts"
:key="product.id"
:value="product.id"
>

{{ product.name }}

</option>

</select>

</div>

</div>

</div>

<!-- ================= ADDRESS ================= -->

<div class="border-t pt-5 grid grid-cols-1 md:grid-cols-2 gap-4">

<div>

<label class="block text-xs font-medium text-slate-600 mb-1">
Address
</label>

<textarea
v-model="form.address"
rows="2"
placeholder="Customer address"
class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
/>

</div>

<div>

<label class="block text-xs font-medium text-slate-600 mb-1">
Notes
</label>

<textarea
v-model="form.notes"
rows="2"
placeholder="Additional notes"
class="w-full border border-slate-300 rounded-lg px-3 py-2 text-sm"
/>

</div>

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

{{ form.processing ? 'Saving...' : 'Create Customer' }}

</button>

</div>

</form>

</template>