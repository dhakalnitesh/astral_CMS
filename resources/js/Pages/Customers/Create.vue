<script setup>
import { ref, computed, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'
import AddressSelect from '@/Components/Common/AddressSelect.vue'

const props = defineProps({
  provinces: { type: Array, default: () => [] },
  districts: { type: Array, default: () => [] },
  municipals: { type: Array, default: () => [] },
  categories: { type: Array, default: () => [] },
  products: { type: Array, default: () => [] }
})

const successMessage = ref('')

const form = useForm({
  type: 'individual',

  name: '',
  email: '',
  phone: '',
  alternate_phone: '',

  date_of_birth: '',
  gender: '',

  company_name: '',
  company_registration_number: '',
  vat_number: '',

  category_id: null,
  product_id: null,

  province_id: '',
  district_id: '',
  municipal_id: '',

  street: '',
  tole: '',
  postal_code: '',

  notes: ''
})

const isBusiness = computed(() => form.type === 'business')

const filteredProducts = computed(() =>
  props.products.filter(p => String(p.category_id) === String(form.category_id))
)

watch(() => form.category_id, () => {
  form.product_id = null
})

const submit = () => {
  form.post(route('customers.store'), {
    onSuccess: () => {
      successMessage.value = 'Customer created successfully!'
      form.reset()
      form.type = 'individual'
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

      <div class="header">
        <div class="form-title">
          <h1 class="title">Create Customer</h1>
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

      <div class="section">
        <h2 class="section-title">
          {{ isBusiness ? 'Business Information' : 'Customer Information' }}
        </h2>

        <div class="grid">
          <div v-if="!isBusiness">
            <label class="label">Full Name</label>
            <input v-model="form.name" class="input" />
            <div v-if="form.errors.name" class="error">{{ form.errors.name }}</div>
          </div>

          <div v-if="isBusiness">
            <label class="label">Company Name</label>
            <input v-model="form.company_name" class="input" />
            <div v-if="form.errors.company_name" class="error">{{ form.errors.company_name }}</div>
          </div>

          <div v-if="isBusiness">
            <label class="label">Registration Number</label>
            <input v-model="form.company_registration_number" class="input" />
            <div v-if="form.errors.company_registration_number" class="error">
              {{ form.errors.company_registration_number }}
            </div>
          </div>

          <div v-if="isBusiness">
            <label class="label">VAT Number</label>
            <input v-model="form.vat_number" class="input" />
            <div v-if="form.errors.vat_number" class="error">{{ form.errors.vat_number }}</div>
          </div>

          <div v-if="!isBusiness">
            <label class="label">Gender</label>
            <select v-model="form.gender" class="input select">
              <option value="">Select Gender</option>
              <option value="male">Male</option>
              <option value="female">Female</option>
              <option value="other">Other</option>
            </select>
            <div v-if="form.errors.gender" class="error">{{ form.errors.gender }}</div>
          </div>

          <div v-if="!isBusiness">
            <label class="label">Date of Birth</label>
            <input type="date" v-model="form.date_of_birth" class="input" />
            <div v-if="form.errors.date_of_birth" class="error">
              {{ form.errors.date_of_birth }}
            </div>
          </div>
        </div>
      </div>

      <div class="section">
        <h2 class="section-title">Contact Information</h2>

        <div class="grid">
          <div>
            <label class="label">Email</label>
            <input v-model="form.email" class="input" />
            <div v-if="form.errors.email" class="error">{{ form.errors.email }}</div>
          </div>
          <div>
            <label class="label">Phone</label>
            <input v-model="form.phone" class="input" />
            <div v-if="form.errors.phone" class="error">{{ form.errors.phone }}</div>
          </div>
          <div>
            <label class="label">Alternate Phone</label>
            <input v-model="form.alternate_phone" class="input" />
            <div v-if="form.errors.alternate_phone" class="error">
              {{ form.errors.alternate_phone }}
            </div>
          </div>
        </div>
      </div>

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
            <div v-if="form.errors.category_id" class="error">
              {{ form.errors.category_id }}
            </div>
          </div>
          <div>
            <label class="label">Product</label>
            <select v-model="form.product_id" class="input select">
              <option :value="null">Select Product</option>
              <option v-for="p in filteredProducts" :key="p.id" :value="p.id">
                {{ p.name }}
              </option>
            </select>
            <div v-if="form.errors.product_id" class="error">
              {{ form.errors.product_id }}
            </div>
          </div>
        </div>
      </div>

      <div class="section">
        <h2 class="section-title">Address Information</h2>
        <AddressSelect
          :provinces="props.provinces || []"
          :districts="props.districts || []"
          :municipals="props.municipals || []"
          :form="form"
        />
      </div>

      <div class="section">
        <label class="label">Notes</label>
        <textarea v-model="form.notes" class="textarea"></textarea>
        <div v-if="form.errors.notes" class="error">{{ form.errors.notes }}</div>
      </div>

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

.error {
  color: #dc2626;
  font-size: 13px;
  margin-top: 4px;
}
</style>