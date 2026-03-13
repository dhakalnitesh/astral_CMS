<script setup>
import {head} from '@inertiajs/vue3';
defineProps({
    categories:Array
})
</script>
<template>
    <div v-for="category in categories" :key="category.id">
         {{ category.name }}
         {{ category.id }}
    </div>
</template>