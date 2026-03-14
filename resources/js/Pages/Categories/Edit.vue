<script setup>
import { ref, watch } from 'vue'
import axios from 'axios'
import { route } from 'ziggy-js'

const emit = defineEmits(['updated'])

const props = defineProps({
  category: Object
})

const name = ref(props.category.name || '')
const description = ref(props.category.name ||'')
const errors = ref({})
const loading = ref(false)

/* Load category data when modal opens */
watch(
  () => props.category,
  (category) => {
    if (category) {
      name.value = category.name
      description.value = category.description
    }
  },
  { immediate: true }
)

const submitForm = async () => {

  try {

    loading.value = true
    errors.value = {}

    await axios.put(route('categories.update', props.category.id), {
      name: name.value,
      description: description.value
    })

    emit('updated')

  } catch (e) {

    if (e.response?.status === 422) {
      errors.value = e.response.data.errors
    } else {
      alert('Something went wrong')
    }

  } finally {
    loading.value = false
  }

}
</script>


<template>

<div class="space-y-4">

<h2 class="text-lg font-semibold">
Edit Category
</h2>

<!-- Category Name -->
<div>
<input
v-model="name"
type="text"
placeholder="Category Name"
class="w-full border rounded-lg px-3 py-2"
/>

<p v-if="errors.name" class="text-red-500 text-sm">
{{ errors.name[0] }}
</p>
</div>


<!-- Description -->
<div>
<textarea
v-model="description"
rows="3"
placeholder="Category Description"
class="w-full border rounded-lg px-3 py-2"
/>

<p v-if="errors.description" class="text-red-500 text-sm">
{{ errors.description[0] }}
</p>
</div>


<!-- Submit -->
<button
@click="submitForm"
:disabled="loading"
class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition"
>

{{ loading ? 'Updating...' : 'Save Changes' }}

</button>

</div>

</template>