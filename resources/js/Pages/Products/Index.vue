<script setup>
import { Link } from '@inertiajs/vue3';
defineProps({
  products: Array
})

</script>

<template>
  <h1>Astral</h1>
  <h2>Customer Management System</h2>

  <div>
  
     <Link href="/products/create" as="button">
    Add Product
  </Link>
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Price</th>
          <th>Description</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="product in products" :key="product.id">
          <td>{{ product.name }}</td>
          <td>{{ product.price }}</td>
          <td>{{ product.description }}</td>
          <td><button v-if="product.id">Edit</button></td>
          
        </tr>
      </tbody>
    </table>
  </div>
</template>