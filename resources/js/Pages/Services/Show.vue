<script setup>
import { computed } from 'vue'

const props = defineProps({
  service: Object
})

// Project Total
const getProjectTotal = (p) => {
  const base = Number(p.final_price || 0)

  const charges =
    Number(p.installation_charge || 0) +
    Number(p.hosting_charge || 0) +
    Number(p.server_charge || 0) +
    Number(p.maintenance_charge || 0)

  return base + charges
}

// Paid per project
const getProjectPaid = (p) => {
  return (p.payments || []).reduce((sum, pay) => {
    return sum + Number(pay.amount || 0)
  }, 0)
}

// Due per project
const getProjectDue = (p) => {
  return getProjectTotal(p) - getProjectPaid(p)
}

// Totals
const totalAmount = computed(() => {
  return props.service.details.reduce((sum, p) => sum + getProjectTotal(p), 0)
})

const totalPaid = computed(() => {
  return props.service.details.reduce((sum, p) => sum + getProjectPaid(p), 0)
})

const totalDue = computed(() => {
  return totalAmount.value - totalPaid.value
})
</script>

<template>
  <div class="max-w-7xl mx-auto p-6 space-y-6">

    <!-- HEADER -->
    <div class="flex justify-between items-center">
      <h1 class="text-2xl font-bold">Service Details</h1>

      <div class="text-right">
        <p class="text-sm text-gray-500">Service ID</p>
        <p class="font-semibold">#{{ service.id }}</p>
      </div>
    </div>

    <!-- SERVICE INFO -->
    <div class="card grid md:grid-cols-3 gap-6">
      <div>
        <p class="label">Customer</p>
        <p class="value">{{ service.customer?.name }}</p>
      </div>

      <div>
        <p class="label">Start Date</p>
        <p class="value">{{ service.start_date }}</p>
      </div>

      <div>
        <p class="label">End Date</p>
        <p class="value">{{ service.end_date }}</p>
      </div>
    </div>

    <!-- SUMMARY CARDS -->
    <div class="grid md:grid-cols-3 gap-4">

      <div class="summary green">
        <p>Total Amount</p>
        <h2>Rs. {{ totalAmount }}</h2>
      </div>

      <div class="summary blue">
        <p>Total Paid</p>
        <h2>Rs. {{ totalPaid }}</h2>
      </div>

      <div class="summary red">
        <p>Total Due</p>
        <h2>Rs. {{ totalDue }}</h2>
      </div>

    </div>

    <!-- PROJECTS -->
    <div v-for="(p, i) in service.details" :key="p.id" class="project-card">

      <!-- HEADER -->
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Project {{ i + 1 }}</h2>

        <span class="status"
          :class="getProjectDue(p) === 0 ? 'paid' : 'due'">
          {{ getProjectDue(p) === 0 ? 'Paid' : 'Due' }}
        </span>
      </div>

      <!-- BASIC INFO -->
      <div class="grid md:grid-cols-4 gap-4">
        <div>
          <p class="label">Category</p>
          <p class="value">{{ p.product?.category?.name }}</p>
        </div>

        <div>
          <p class="label">Product</p>
          <p class="value">{{ p.product?.name }}</p>
        </div>

        <div>
          <p class="label">Base Price</p>
          <p class="value">Rs. {{ p.base_price }}</p>
        </div>

        <div>
          <p class="label">Discount</p>
          <p class="value">Rs. {{ p.discount }}</p>
        </div>
      </div>

      <!-- CHARGES -->
      <div class="mt-4">
        <h3 class="sub-title">Charges</h3>

        <div class="grid md:grid-cols-4 gap-4">
          <div>Installation: Rs. {{ p.installation_charge }}</div>
          <div>Hosting: Rs. {{ p.hosting_charge }}</div>
          <div>Server: Rs. {{ p.server_charge }}</div>
          <div>Maintenance: Rs. {{ p.maintenance_charge }}</div>
        </div>
      </div>

      <!-- TOTAL + PAYMENT -->
      <div class="mt-4 grid md:grid-cols-3 gap-4">

        <div>
          <p class="label">Project Total</p>
          <p class="text-green-600 font-semibold">
            Rs. {{ getProjectTotal(p) }}
          </p>
        </div>

        <div>
          <p class="label">Paid</p>
          <p class="text-blue-600">
            Rs. {{ getProjectPaid(p) }}
          </p>
        </div>

        <div>
          <p class="label">Due</p>
          <p class="text-red-500 font-semibold">
            Rs. {{ getProjectDue(p) }}
          </p>
        </div>

      </div>

      <!-- PAYMENT HISTORY -->
      <div v-if="p.payments?.length" class="mt-4">
        <h3 class="sub-title">Payment History</h3>

        <div class="space-y-1 text-sm">
          <div
            v-for="pay in p.payments"
            :key="pay.id"
            class="flex justify-between border-b pb-1"
          >
            <span>Rs. {{ pay.amount }}</span>
            <span class="text-gray-500">{{ pay.payment_date }}</span>
          </div>
        </div>
      </div>

    </div>

  </div>
</template>

<style>
.card {
  background: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.project-card {
  background: white;
  padding: 20px;
  border-radius: 10px;
  border: 1px solid #eee;
}

.label {
  font-size: 13px;
  color: gray;
}

.value {
  font-weight: 500;
}

.sub-title {
  font-weight: 600;
  margin-bottom: 6px;
}

.summary {
  padding: 20px;
  border-radius: 10px;
  color: white;
}

.summary.green { background: #16a34a; }
.summary.blue { background: #2563eb; }
.summary.red { background: #dc2626; }

.status {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 12px;
}

.status.paid {
  background: #dcfce7;
  color: #166534;
}

.status.due {
  background: #fee2e2;
  color: #991b1b;
}
</style>