<script setup>
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AddressSelect from '@/Components/Common/AddressSelect.vue'

const props = defineProps({
  employee: Object,          // The employee to edit
  provinces: Array,
  districts: Array,
  municipals: Array
})

// Success message
const successMessage = ref('')

// Initialize form with employee data
const form = useForm({
  full_name: props.employee.full_name || '',
  email: props.employee.email || '',
  phone: props.employee.phone || '',
  date_of_birth: props.employee.date_of_birth || '',
  gender: props.employee.gender || '',

  designation: props.employee.designation || '',
  department: props.employee.department || '',

  joining_date: props.employee.joining_date || '',
  end_date: props.employee.end_date || '',
  salary: props.employee.salary || '',
  experience_years: props.employee.experience_years || '',

  province_id: props.employee.province_id || '',
  district_id: props.employee.district_id || '',
  municipal_id: props.employee.municipal_id || '',

  street: props.employee.street || '',
  tole: props.employee.tole || ''
})

// Submit update
const submit = () => {
  form.put(route('employees.update', props.employee.id), {
    onSuccess: () => {
      successMessage.value = 'Employee updated successfully!'
      window.scrollTo(0, 0)
      setTimeout(() => successMessage.value = '', 5000)
    }
  })
}
</script>

<template>
<div class="max-w-6xl mx-auto p-6">

  <!-- Success Message -->
  <div v-if="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
    {{ successMessage }}
  </div>

  <form @submit.prevent="submit">
    <div class="card space-y-8">

      <!-- CARD TITLE -->
      <div class="mb-6">
        <h1 class="text-2xl font-bold text-center">Edit Employee</h1>
      </div>

      <!-- PERSONAL INFORMATION -->
      <div>
        <h2 class="section-title">Personal Information</h2>
        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Full Name <span class="text-red-600">*</span></label>
            <input v-model="form.full_name" class="input" />
            <p v-if="form.errors.full_name" class="text-red-600 text-sm mt-1">{{ form.errors.full_name }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Gender <span class="text-red-600">*</span></label>
            <select v-model="form.gender" class="input">
              <option value="">Select Gender</option>
              <option>Male</option>
              <option>Female</option>
              <option>Other</option>
            </select>
            <p v-if="form.errors.gender" class="text-red-600 text-sm mt-1">{{ form.errors.gender }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Phone Number <span class="text-red-600">*</span></label>
            <input v-model="form.phone" class="input" />
            <p v-if="form.errors.phone" class="text-red-600 text-sm mt-1">{{ form.errors.phone }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Email <span class="text-red-600">*</span></label>
            <input v-model="form.email" class="input" />
            <p v-if="form.errors.email" class="text-red-600 text-sm mt-1">{{ form.errors.email }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Date of Birth <span class="text-red-600">*</span></label>
            <input type="date" v-model="form.date_of_birth" class="input" />
            <p v-if="form.errors.date_of_birth" class="text-red-600 text-sm mt-1">{{ form.errors.date_of_birth }}</p>
          </div>
        </div>
      </div>

      <!-- EMPLOYMENT INFORMATION -->
      <div>
        <h2 class="section-title">Employment Information</h2>
        <div class="grid grid-cols-3 gap-4">
          <div>
            <label class="block text-sm font-medium mb-1">Designation <span class="text-red-600">*</span></label>
            <input v-model="form.designation" class="input" />
            <p v-if="form.errors.designation" class="text-red-600 text-sm mt-1">{{ form.errors.designation }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Department</label>
            <input v-model="form.department" class="input" />
            <p v-if="form.errors.department" class="text-red-600 text-sm mt-1">{{ form.errors.department }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Joining Date <span class="text-red-600">*</span></label>
            <input type="date" v-model="form.joining_date" class="input" />
            <p v-if="form.errors.joining_date" class="text-red-600 text-sm mt-1">{{ form.errors.joining_date }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">End Date</label>
            <input type="date" v-model="form.end_date" class="input" />
            <p v-if="form.errors.end_date" class="text-red-600 text-sm mt-1">{{ form.errors.end_date }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Salary <span class="text-red-600">*</span></label>
            <input v-model="form.salary" class="input" />
            <p v-if="form.errors.salary" class="text-red-600 text-sm mt-1">{{ form.errors.salary }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Experience (years) <span class="text-red-600">*</span></label>
            <input v-model="form.experience_years" class="input" />
            <p v-if="form.errors.experience_years" class="text-red-600 text-sm mt-1">{{ form.errors.experience_years }}</p>
          </div>
        </div>
      </div>

      <!-- ADDRESS INFORMATION -->
      <div>
        <h2 class="section-title">Address Information</h2>

        <AddressSelect
          :provinces="provinces"
          :districts="districts"
          :municipals="municipals"
          :form="form"
        />

        <div class="grid grid-cols-3 gap-4 mt-4">
          <div>
            <label class="block text-sm font-medium mb-1">Street / Ward</label>
            <input v-model="form.street" class="input" />
            <p v-if="form.errors.street" class="text-red-600 text-sm mt-1">{{ form.errors.street }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Tole</label>
            <input v-model="form.tole" class="input" />
            <p v-if="form.errors.tole" class="text-red-600 text-sm mt-1">{{ form.errors.tole }}</p>
          </div>
        </div>
      </div>

      <!-- SUBMIT BUTTON -->
      <div class="flex justify-end pt-4">
        <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
          Update Employee
        </button>
      </div>
    </div>
  </form>
</div>
</template>

<style>
.input{
  border:1px solid #ddd;
  padding:10px;
  border-radius:6px;
  width:100%;
}

.card{
  background:white;
  padding:25px;
  border-radius:10px;
  box-shadow:0 2px 10px rgba(0,0,0,0.05);
}

.section-title{
  font-weight:600;
  margin-bottom:15px;
  font-size:16px;
}

.text-red-600 {
  color: #dc2626;
}
</style>