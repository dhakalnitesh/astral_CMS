<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

// Destructure props directly — this is key for template rendering
const { customer } = defineProps({
  customer: Object
})

// Computed property to check if customer is business
const isBusiness = computed(() => customer.type === 'business')

console.log('Customer data:', customer)
</script>

<template>
<div class="container">
  <div class="card">

    <!-- Header -->
    <div class="header">
      <h1 class="title">Customer Details</h1>
      <Link :href="route('customers.index')" class="btn-back">&larr; Back to List</Link>
    </div>

    <!-- Customer Information -->
    <div class="section">
      <h2 class="section-title">Customer Information</h2>
      <div class="info-grid">
        <div><span class="label">Type:</span> <span class="value">{{ customer.type || '' }}</span></div>
        <div v-if="!isBusiness"><span class="label">Full Name:</span> <span class="value">{{ customer.name || '' }}</span></div>
        <div v-if="isBusiness"><span class="label">Company Name:</span> <span class="value">{{ customer.company_name || '' }}</span></div>
        <div v-if="isBusiness"><span class="label">Registration Number:</span> <span class="value">{{ customer.company_registration_number || '' }}</span></div>
        <div v-if="isBusiness"><span class="label">VAT Number:</span> <span class="value">{{ customer.vat_number || '' }}</span></div>
        <div v-if="!isBusiness"><span class="label">Gender:</span> <span class="value">{{ customer.gender || '' }}</span></div>
        <div v-if="!isBusiness"><span class="label">Date of Birth:</span> <span class="value">{{ customer.date_of_birth || '' }}</span></div>
        <div><span class="label">Status:</span> <span class="value">{{ customer.status || '' }}</span></div>
      </div>
    </div>

    <!-- Contact Information -->
    <div class="section">
      <h2 class="section-title">Contact Information</h2>
      <div class="info-grid">
        <div><span class="label">Email:</span> <span class="value">{{ customer.email || ''}}</span></div>
        <div><span class="label">Phone:</span> <span class="value">{{ customer.phone || '' }}</span></div>
        <div><span class="label">Alternate Phone:</span> <span class="value">{{ customer.alternate_phone || '' }}</span></div>
      </div>
    </div>

    <!-- Services Information -->
    <div class="section">
      <h2 class="section-title">Services Information</h2>
      <div class="info-grid">
        <div><span class="label">Category:</span> <span class="value">{{ customer.category?.name || '' }}</span></div>
        <div><span class="label">Product:</span> <span class="value">{{ customer.product?.name || '' }}</span></div>
      </div>
    </div>

    <!-- Address Information -->
    <div class="section">
      <h2 class="section-title">Address Information</h2>
      <div class="info-grid">
        <div><span class="label">Province:</span> <span class="value">{{ customer.province?.province_name || '' }}</span></div>
        <div><span class="label">District:</span> <span class="value">{{ customer.district?.district_name || '' }}</span></div>
        <div><span class="label">Municipal:</span> <span class="value">{{ customer.municipal?.municipal_name || '' }}</span></div>
        <div><span class="label">Street:</span> <span class="value">{{ customer.street || '' }}</span></div>
        <div><span class="label">Tole:</span> <span class="value">{{ customer.tole || '' }}</span></div>
        <div><span class="label">Postal Code:</span> <span class="value">{{ customer.postal_code || '' }}</span></div>
      </div>
    </div>

    <!-- Notes -->
    <div class="section">
      <h2 class="section-title">Notes</h2>
      <p class="notes">{{ customer.notes || '' }}</p>
    </div>

  </div>
</div>
</template>

<style>
.container {
  max-width: 900px;
  margin: auto;
  padding: 20px;
}

.card {
  background: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.title {
  font-size: 1.75rem;
  font-weight: 600;
  color: #111827;
}

.btn-back {
  background: #2563eb;
  color: white;
  padding: 6px 14px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 500;
}

.btn-back:hover {
  background: #1d4ed8;
}

.section {
  margin-top: 25px;
}

.section-title {
  font-size: 1.25rem;
  font-weight: 500;
  margin-bottom: 12px;
  border-bottom: 1px solid #e5e7eb;
  padding-bottom: 6px;
  color: #374151;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 12px 20px;
}

.info-grid div {
  background: #f9fafb;
  padding: 10px 14px;
  border-radius: 8px;
  display: flex;
  justify-content: space-between;
  font-weight: 400;
  color: #111827;
}

.label {
  color: #6b7280;
  font-weight: 500;
  margin-right: 6px;
}

.value {
  color: #111827;
  font-weight: 400;
}

.notes {
  background: #f9fafb;
  padding: 14px;
  border-radius: 8px;
  min-height: 60px;
  color: #111827;
  font-weight: 400;
}
</style>