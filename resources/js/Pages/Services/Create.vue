<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  customers: Array,
  products: Array
})

const form = ref({
  customer_id: '',
  start_date: '',
  end_date: '',

  projects: [
    {
      product_id: '',
      project_name: '',
      base_price: 0,
      discount: 0,
      final_price: 0
    }
  ],

  charges: {
    installation_charge: 0,
    hosting_charge: 0,
    server_charge: 0,
    maintenance_charge: 0
  }
})

// Project functions
const addProject = () => {
  form.value.projects.push({
    product_id: '',
    project_name: '',
    base_price: 0,
    discount: 0,
    final_price: 0
  })
}

const removeProject = (index) => {
  if (form.value.projects.length > 1) {
    form.value.projects.splice(index, 1)
  }
}

const calculateFinal = (p) => {
  p.final_price = (p.base_price || 0) - (p.discount || 0)
}

// Total
const totalAmount = computed(() => {
  let projectTotal = form.value.projects.reduce((sum, p) => {
    return sum + Number(p.final_price || 0)
  }, 0)

  let c = form.value.charges

  let chargeTotal =
    Number(c.installation_charge) +
    Number(c.hosting_charge) +
    Number(c.server_charge) +
    Number(c.maintenance_charge)

  return projectTotal + chargeTotal
})

// Submit
const submit = () => {
  router.post('/services', {
    ...form.value,
    total_amount: totalAmount.value
  })
}
</script>

<template>
<div class="max-w-6xl mx-auto p-6 space-y-6">

  <form @submit.prevent="submit">
    <div class="card space-y-8">

      <!-- TITLE -->
      <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-gray-800">Create Service</h1>
      </div>

      <!-- SERVICE INFO -->
      <div>
        <h2 class="section-title">Service Information</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

          <div>
            <label class="label">Customer</label>
            <select v-model="form.customer_id" class="input">
              <option value="">Select Customer</option>
              <option v-for="c in customers" :key="c.id" :value="c.id">
                {{ c.name }}
              </option>
            </select>
          </div>

          <div>
            <label class="label">Start Date</label>
            <input type="date" v-model="form.start_date" class="input" />
          </div>

          <div>
            <label class="label">End Date</label>
            <input type="date" v-model="form.end_date" class="input" />
          </div>

        </div>
      </div>

      <!-- PROJECTS -->
      <div>
        <div class="flex justify-between items-center mb-3">
          <h2 class="section-title">Projects</h2>

          <button type="button"
                  @click="addProject"
                  class="btn-primary text-sm">
            + Add Project
          </button>
        </div>

        <div v-for="(p, i) in form.projects" :key="i" class="box">

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div>
              <label class="label">Product</label>
              <select v-model="p.product_id" class="input">
                <option value="">Select Product</option>
                <option v-for="prod in products" :key="prod.id" :value="prod.id">
                  {{ prod.name }}
                </option>
              </select>
            </div>

            <div>
              <label class="label">Project Name</label>
              <input v-model="p.project_name" class="input" />
            </div>

            <div>
              <label class="label">Base Price</label>
              <input type="number" v-model="p.base_price"
                     @input="calculateFinal(p)"
                     class="input" />
            </div>

            <div>
              <label class="label">Discount</label>
              <input type="number" v-model="p.discount"
                     @input="calculateFinal(p)"
                     class="input" />
            </div>

            <div>
              <label class="label">Final Price</label>
              <input type="number" v-model="p.final_price"
                     readonly
                     class="input bg-gray-100" />
            </div>

          </div>

          <div class="text-right mt-2">
            <button type="button"
                    @click="removeProject(i)"
                    class="text-red-600 text-sm">
              Remove
            </button>
          </div>

        </div>

      </div>

      <!-- CHARGES -->
      <div>
        <h2 class="section-title">Charges</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

          <div>
            <label class="label">Installation</label>
            <input type="number" v-model="form.charges.installation_charge" class="input" />
          </div>

          <div>
            <label class="label">Hosting</label>
            <input type="number" v-model="form.charges.hosting_charge" class="input" />
          </div>

          <div>
            <label class="label">Server</label>
            <input type="number" v-model="form.charges.server_charge" class="input" />
          </div>

          <div>
            <label class="label">Maintenance</label>
            <input type="number" v-model="form.charges.maintenance_charge" class="input" />
          </div>

        </div>
      </div>

      <!-- TOTAL -->
      <div>
        <h2 class="section-title">Summary</h2>

        <div class="text-lg font-semibold">
          Total: <span class="text-green-600">Rs. {{ totalAmount }}</span>
        </div>
      </div>

      <!-- SUBMIT -->
      <div class="flex justify-end pt-4">
        <button type="submit" class="btn-primary">
          Save Service
        </button>
      </div>

    </div>
  </form>

</div>
</template>

<style>
.card {
  background: white;
  padding: 25px;
  border-radius: 10px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.section-title {
  font-weight: 600;
  margin-bottom: 15px;
  font-size: 16px;
}

.input {
  border: 1px solid #ddd;
  padding: 10px;
  border-radius: 6px;
  width: 100%;
}

.label {
  display: block;
  font-size: 14px;
  margin-bottom: 4px;
}

.box {
  border: 1px solid #eee;
  padding: 15px;
  border-radius: 8px;
  margin-bottom: 10px;
}

.btn-primary {
  background: #2563eb;
  color: white;
  padding: 8px 16px;
  border-radius: 6px;
}

.btn-primary:hover {
  background: #1d4ed8;
}
</style>