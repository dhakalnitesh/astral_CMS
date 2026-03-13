<script setup>
const props = defineProps({
  show: Boolean
})

const emit = defineEmits(['close'])
</script>

<template>

<div
v-if="show"
class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
>

<div class="bg-white rounded-xl shadow-lg w-full max-w-2xl p-6 relative">

<button
@click="$emit('close')"
class="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
>
✕
</button>

<slot />

</div>

</div>

</template>