<script setup>
import { ref, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import { watch ,onMounted} from 'vue'

const props = defineProps({
  customers: Array,
  products: Array,
  categories: Array,
  service:Object,
})

const form = useForm({
  customer_id: '',
  start_date: '',
  end_date: '',
  paid_amount: '',
  due_amount: '',
  total_amount: '',
  initial_payment: 0,

  projects: []
})
//preload data as per the previous data of service
onMounted(() => {

  const s = props.service

  form.customer_id = s.customer_id
  form.start_date = s.start_date
  form.end_date = s.end_date

  form.initial_payment = s.paid_amount // preload payment

  // map projects
  form.projects = s.details.map(d => ({
    category_id: d.product?.category_id || '',
    product_id: d.product_id,
    project_name: d.project_name,
    base_price: d.base_price,
    discount: d.discount,
    final_price: d.final_price,

    charges: {
      installation_charge: d.installation_charge || 0,
      hosting_charge: d.hosting_charge || 0,
      server_charge: d.server_charge || 0,
      maintenance_charge: d.maintenance_charge || 0
    }
  }))

})

// Add Project
const addProject = () => {
  form.projects.push({
    category_id: '',
    product_id: '',
    project_name: '',
    base_price: 0,
    discount: 0,
    final_price: 0,
    

    charges: {
      installation_charge: 0,
      hosting_charge: 0,
      server_charge: 0,
      maintenance_charge: 0
    }
  })
}

// Filter Products
const getFilteredProducts = (categoryId) => {
  return props.products.filter(p => p.category_id === categoryId)
}

// Remove Project
const removeProject = (index) => {
  if (form.projects.length > 1) {
    form.projects.splice(index, 1)
  }
}
//Set base price
const applyProductData = (p) => {
  const product = props.products.find(prod => prod.id === p.product_id)

  if (product) {
    // Auto fill base price from product
    p.base_price = product.price || 0
  } else {
    // when no product selected
    p.base_price = 0
  }
  // Recalculate final price
  calculateFinal(p)
}
// it wi;l update the base price if the product is selected next(  or if we switch the product name then)
watch(
  () => form.projects.map(p => p.product_id),
  (newIds) => {
    form.projects.forEach(p => {
      const product = props.products.find(prod => prod.id === p.product_id)

      if (product) {
        p.base_price = product.price || 0
        calculateFinal(p)
      }
    })
  },
  { deep: true }
)
// It will watch for the categories change
watch(
  () => form.projects.map(p => p.category_id),
  (newCategories, oldCategories) => {
    form.projects.forEach((p, index) => {
      const oldCategory = oldCategories?.[index]
      const newCategory = p.category_id

      // If category is cleared
      if (!newCategory) {
        p.product_id = ''
        p.base_price = 0
        p.final_price = 0
        return
      }

      // If category CHANGED (switching category)
      if (oldCategory !== newCategory) {
        p.product_id = ''
        p.base_price = 0
        p.final_price = 0
      }
    })
  }
)
//One project total 
const getProjectTotal = (p) => {
  const base = Number(p.final_price || 0)

  const charges =
    Number(p.charges.installation_charge || 0) +
    Number(p.charges.hosting_charge || 0) +
    Number(p.charges.server_charge || 0) +
    Number(p.charges.maintenance_charge || 0)

  return base + charges
}

//paid and due calculation
const paidAmount = computed(() => {
  return Number(form.initial_payment || 0)
})

const dueAmount = computed(() => {
  return totalAmount.value - paidAmount.value
})
watch(() => form.initial_payment, (val) => {
  if (val > totalAmount.value) {
    form.initial_payment = totalAmount.value
  }
})

// Calculate Final
const calculateFinal = (p) => {
  const base = Number(p.base_price) || 0
  const discount = Number(p.discount) || 0

  p.final_price = base - discount
}

// TOTAL (FIXED)
const totalAmount = computed(() => {
  return form.projects.reduce((sum, p) => {
    return sum + getProjectTotal(p)
  }, 0)
})

// Submit
const submit = () => {
  form.total_amount = totalAmount.value
  form.paid_amount = paidAmount.value
  form.due_amount = dueAmount.value

  form.put(route('services.update',props.service.id), {
    onSuccess: () => {
      form.reset()
      router.reload({ only: ['services'] })
    }
  })
}
</script>

<template>
  <div class="max-w-6xl mx-auto p-5 space-y-4">

    <form @submit.prevent="submit">
      <div class="card space-y-8">

        <!-- TITLE -->
        <div class="mb-6 text-center">
          <h1 class="text-2xl font-bold text-gray-800">Edit Service</h1>
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
            <h1 class="section-title">Projects Information</h1>

            <button type="button" @click="addProject" class="btn-primary text-sm">
              + Add Project
            </button>
          </div>

          <div v-for="(p, i) in form.projects" :key="i" class="box">
            <!-- PROJECT HEADING -->
            <div class="mb-3">
              <h3 class="text-lg font-semibold">
                Project {{ i + 1 }}
              </h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

              <!-- Category -->
              <div>
                <label class="label">Category</label>
                <select v-model="p.category_id" class="input">
                  <option value="">Select Category</option>
                  <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                    {{ cat.name }}
                  </option>
                </select>
              </div>

              <!-- Product (FIXED) -->
              <div>
                <label class="label">Product</label>
                <select v-model="p.product_id" class="input" @change="applyProductData(p)">

                  <option value="">Select Product</option>

                  <option v-for="prod in getFilteredProducts(p.category_id)" :key="prod.id" :value="prod.id">
                    {{ prod.name }}
                  </option>

                </select>
              </div>

              <!-- Base Price -->
              <div>
                <label class="label">Base Price</label>
                <input type="number" v-model="p.base_price" @input="calculateFinal(p)" class="input" />
              </div>

              <!-- Discount -->
              <div>
                <label class="label">Discount</label>
                <input type="number" v-model="p.discount" @input="calculateFinal(p)" class="input" />
              </div>

              <!-- Final Price -->
              <!-- <div>
                <label class="label">Final Price</label>
                <input type="number" v-model="p.final_price" readonly class="input bg-gray-100" />
              </div> -->

            </div>

            <!-- CHARGES (FIXED: inside loop) -->
            <div class="mt-4">
              <h2 class="section-title">Service Charges</h2>

              <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>
                  <label class="label">Installation</label>
                  <input type="number" v-model="p.charges.installation_charge" class="input" />
                </div>

                <div>
                  <label class="label">Hosting</label>
                  <input type="number" v-model="p.charges.hosting_charge" class="input" />
                </div>

                <div>
                  <label class="label">Server</label>
                  <input type="number" v-model="p.charges.server_charge" class="input" />
                </div>

                <div>
                  <label class="label">Maintenance</label>
                  <input type="number" v-model="p.charges.maintenance_charge" class="input" />
                </div>

              </div>
            </div>

             <!-- Remove Project Button -->
            <div v-show="form.projects.length > 1" class="text-right mt-2">
              <button type="button" @click="removeProject(i)" class="btn-remove text-red-600 text-sm">
                Remove
              </button>
            </div>
            <div class="mt-4 text-lg font-semibold">
              Project {{ i + 1 }} <br> Total: <span class="text-green-600">Rs. {{ getProjectTotal(p) }}</span>
            </div>
            
            <!-- PAYMENT SECTION -->
            <div class="mt-6">
              <h2 class="section-title">Payment Information</h2>

              <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <div>
                  <label class="label">Initial Payment</label>
                  <input type="number" v-model="form.initial_payment" class="input" />
                </div>

                <div>
                  <label class="label">Paid Amount</label>
                  <input type="text" :value="paidAmount" readonly class="input bg-gray-100" />
                </div>

                <div>
                  <label class="label">Due Amount</label>
                  <input type="text" :value="dueAmount" readonly class="input bg-gray-100" />
                </div>

              </div>
            </div>

          </div>

        </div>

        <!-- Summary Total -->
        <div class="flex justify-end">
          <div>
            <h2 class="section-title">Total Project Amount</h2>

            <div class="text-lg font-semibold">
              Total: <span class="text-green-600">Rs. {{ totalAmount }}</span>
            </div>
          </div>
        </div>

        <!-- SUBMIT -->
        <div class="flex justify-end pt-2">
          <button type="submit" class="btn-primary">
            Update Service
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
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
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

.btn-remove {
  background: transparent;
  border: 1px solid #f87171;
  color: #f87171;
  padding: 4px 10px;
  border-radius: 6px;
  cursor: pointer;
}

.btn-remove:hover {
  background: #ef1c1c;
  color: white;
}
</style>