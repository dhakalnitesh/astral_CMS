<script setup>

import { ref, computed, watch } from 'vue'

const props = defineProps({
  provinces: Array,
  districts: Array,
  municipals: Array,
  form: Object
})

const filteredDistricts = computed(() =>
  props.districts.filter(d => d.province_id == props.form.province_id)
)

const filteredMunicipals = computed(() =>
  props.municipals.filter(m => m.district_id == props.form.district_id)
)

watch(() => props.form.province_id, () => {
  props.form.district_id = ''
  props.form.municipal_id = ''
})

watch(() => props.form.district_id, () => {
  props.form.municipal_id = ''
})

</script>

<template>

<div class="grid grid-cols-3 gap-4">

<select v-model="form.province_id" class="input">
<option value="">Province</option>

<option
v-for="p in provinces"
:key="p.id"
:value="p.id"
>
{{ p.province_name }}
</option>

</select>

<select v-model="form.district_id" class="input">

<option value="">District</option>

<option
v-for="d in filteredDistricts"
:key="d.id"
:value="d.id"
>
{{ d.district_name }}
</option>

</select>

<select v-model="form.municipal_id" class="input">

<option value="">Municipality</option>

<option
v-for="m in filteredMunicipals"
:key="m.id"
:value="m.id"
>
{{ m.municipal_name }}
</option>

</select>

</div>

</template>