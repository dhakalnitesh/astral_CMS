<script setup>

import { ref } from 'vue'
import axios from 'axios';

const name = ref('')
const description = ref('')
const errors = ref({})

const submitForm = async () => {
    try {

        await axios.post(route('categories.store'), {
            name: name.value,
            description: description.value,
        });

        name.value = ''
        description.value = ''

        alert('Category created successfully')

    } catch (error) {

        if (error.response?.data?.errors) {
            errors.value = error.response.data.errors
        } else {
            alert('Something went wrong')
        }
    }
}
</script>

<template>

<!-- Page Content -->
<div class="py-8">

<div class="max-w-9xl mx-auto sm:px-6 lg:px-8">

<div class="bg-white shadow-sm rounded-lg p-6">

<form @submit.prevent="submitForm" class="space-y-6">

<!-- Category Name -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Category Name
</label>

<input
v-model="name"
type="text"
placeholder="Enter category name"
class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
/>

<p v-if="errors.name" class="text-red-500 text-sm mt-1">
{{ errors.name[0] }}
</p>
</div>


<!-- Description -->
<div>
<label class="block text-sm font-medium text-gray-700 mb-1">
Category Description
</label>

<textarea
v-model="description"
rows="4"
placeholder="Enter category description"
class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
></textarea>

<p v-if="errors.description" class="text-red-500 text-sm mt-1">
{{ errors.description[0] }}
</p>
</div>


<!-- Button -->
<div>
<button
type="submit"
class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
>
Create Category
</button>
</div>

</form>

</div>

</div>

</div>

<!-- </AuthenticatedLayout> -->

</template>