<script setup>
import { ref, computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import AddressSelect from '@/Components/Common/AddressSelect.vue'

const props = defineProps({
  customer: { type: Object, required: true },
  provinces: { type: Array, default: () => [] },
  districts: { type: Array, default: () => [] },
  municipals: { type: Array, default: () => [] },
  categories: { type: Array, default: () => [] },
  products: { type: Array, default: () => [] }
})

const successMessage = ref('')

const form = useForm({
  id: props.customer.id,
  type: props.customer.type,
  name: props.customer.name,
  email: props.customer.email,
  phone: props.customer.phone,
  alternate_phone: props.customer.alternate_phone,
  date_of_birth: props.customer.date_of_birth,
  gender: props.customer.gender,
  company_name: props.customer.company_name,
  company_registration_number: props.customer.company_registration_number,
  vat_number: props.customer.vat_number,
  category_id: props.customer.category_id,
  product_id: props.customer.product_id,
  province_id: props.customer.province_id,
  district_id: props.customer.district_id,
  municipal_id: props.customer.municipal_id,
  street: props.customer.street,
  tole: props.customer.tole,
  postal_code: props.customer.postal_code,
  notes: props.customer.notes
})

const isBusiness = computed(() => form.type === 'business')

/* -------------------------
   DYNAMIC DROPDOWNS
--------------------------*/
const filteredProducts = computed(() =>
  props.products.filter(p => String(p.category_id) === String(form.category_id))
)

watch(() => form.category_id, () => {
  form.product_id = null
})

const submit = () => {
  form.put(route('customers.update', form.id), {
    onSuccess: () => {
      successMessage.value = 'Customer updated successfully!'
      window.scrollTo({ top: 0, behavior: 'smooth' })
      setTimeout(() => successMessage.value = '', 4000)
    }
  })
}
</script>

<template>
<div class="container">

  <div v-if="successMessage" class="success-box">
    {{ successMessage }}
  </div>

  <form @submit.prevent="submit">
    <div class="card">

      <!-- HEADER -->
      <div class="header">
        <div class="form-title">
          <h1 class="title">Edit Customer</h1>
        </div>

        <div class="switch">
          <button type="button"
            @click="form.type='individual'"
            :class="['switch-btn', form.type==='individual' && 'active']">
            Individual
          </button>
          <button type="button"
            @click="form.type='business'"
            :class="['switch-btn', form.type==='business' && 'active']">
            Business
          </button>
        </div>
      </div>

      <!-- CUSTOMER INFO -->
      <div class="section">
        <h2 class="section-title">
          {{ isBusiness ? 'Business Information' : 'Customer Information' }}
        </h2>

        <div class="grid">
          <!-- Individual Fields -->
          <div v-if="!isBusiness">
            <label class="label">Full Name</label>
            <input v-model="form.name" class="input" />
          </div>

          <!-- Business Fields -->
          <div v-if="isBusiness">
            <label class="label">Company Name</label>
            <input v-model="form.company_name" class="input" />
          </div>
          <div v-if="isBusiness">
            <label class="label">Registration Number</label>
            <input v-model="form.company_registration_number" class="input" />
          </div>
          <div v-if="isBusiness">
            <label class="label">VAT Number</label>
            <input v-model="form.vat_number" class="input" />
          </div>

          <!-- Gender -->
          <div v-if="!isBusiness">
            <label class="label">Gender</label>
            <select v-model="form.gender" class="input select">
              <option value="">Select Gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
          </div>

          <!-- Date of Birth -->
          <div v-if="!isBusiness">
            <label class="label">Date of Birth</label>
            <input type="date" v-model="form.date_of_birth" class="input" />
          </div>
        </div>
      </div>

      <!-- CONTACT -->
      <div class="section">
        <h2 class="section-title">Contact Information</h2>

        <div class="grid">
          <div>
            <label class="label">Email</label>
            <input v-model="form.email" class="input" />
          </div>
          <div>
            <label class="label">Phone</label>
            <input v-model="form.phone" class="input" />
          </div>
          <div>
            <label class="label">Alternate Phone</label>
            <input v-model="form.alternate_phone" class="input" />
          </div>
        </div>
      </div>

      <!-- SERVICES -->
      <div class="section">
        <h2 class="section-title">Services</h2>

        <div class="grid">
          <div>
            <label class="label">Category</label>
            <select v-model="form.category_id" class="input select">
              <option :value="null">Select Category</option>
              <option v-for="c in props.categories" :key="c.id" :value="c.id">
                {{ c.name }}
              </option>
            </select>
          </div>
          <div>
            <label class="label">Product</label>
            <select v-model="form.product_id" class="input select">
              <option :value="null">Select Product</option>
              <option v-for="p in filteredProducts" :key="p.id" :value="p.id">
                {{ p.name }}
              </option>
            </select>
          </div>
        </div>
      </div>

      <!-- ADDRESS -->
      <div class="section">
        <h2 class="section-title">Address Information</h2>
        <AddressSelect
          :provinces="props.provinces || []"
          :districts="props.districts || []"
          :municipals="props.municipals || []"
          :form="form"
        />
      </div>

      <!-- NOTES -->
      <div class="section">
        <label class="label">Notes</label>
        <textarea v-model="form.notes" class="textarea"></textarea>
      </div>

      <!-- SUBMIT -->
      <div class="footer">
        <button type="submit" class="btn-primary">
          Save Customer
        </button>
      </div>

    </div>
  </form>
</div>
</template>

<style>
.container {
  max-width:1100px;
  margin:auto;
  padding:20px;
}

.card {
  background:white;
  padding:30px;
  border-radius:14px;
  box-shadow:0 15px 50px rgba(0,0,0,0.08);
}

.header {
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin-bottom:25px;
}

.switch {
  background:#f3f4f6;
  padding:4px;
  border-radius:10px;
}
.switch-btn {
  padding:6px 14px;
  border-radius:8px;
}
.switch-btn.active {
  background:#2563eb;
  color:white;
}

.section {
  margin-top:25px;
}

.grid {
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:14px;
}

.label {
  display: block;
  margin-bottom: 6px;
  font-weight: 500;
  color: #374151;
}

.input, .textarea {
  border:1px solid #e5e7eb;
  padding:10px;
  border-radius:8px;
  background:#f9fafb;
  width:100%;
}

.select {
  appearance:none;
  background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7' /%3E%3C/svg%3E");
  background-repeat:no-repeat;
  background-position:right 10px center;
  background-size:16px;
  padding-right: 30px;
}

.textarea {
  grid-column: span 3;
  min-height:100px;
}

.footer {
  margin-top:25px;
  text-align:right;
}

.btn-primary {
  background:#2563eb;
  color:white;
  padding:10px 20px;
  border-radius:8px;
}

.success-box {
  background:#ecfdf5;
  border:1px solid #22c55e;
  padding:10px;
  border-radius:8px;
}
</style>