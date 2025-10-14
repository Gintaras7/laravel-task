<script setup>
defineProps({
  stocks: {
    type: Array,
    required: true,
  },
});
</script>

<template>
  <div v-if="stocks.length" class="text-sm text-gray-700 space-y-1 mt-2">
    <div v-for="stock in stocks" :key="stock.id">
      {{ stock.stock }} pcs — {{ stock.city.title }}
    </div>
  </div>
</template>
