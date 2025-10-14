<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { storeToRefs } from 'pinia';
import { formatDate } from '@/utils/date.js';
import { useProductStore } from '@/stores/product';
import ProductSummary from '@/components/Products/ProductSummary.vue';

const route = useRoute();
const productId = route.params.id;

const productStore = useProductStore();
const { product, loading, error } = storeToRefs(productStore);

onMounted(() => {
  productStore.getProduct(productId);
});
</script>

<template>
  <div class="max-w-4xl mx-auto p-6">
    <button @click="$router.push('/')" class="mb-6 text-blue-600 hover:underline cursor-pointer">
      ← Back to products
    </button>

    <div v-if="loading" class="text-center text-gray-500 py-10">Loading...</div>
    <div v-else-if="error" class="text-red-500 text-center py-10">
      {{ error }}
    </div>

    <ProductSummary v-else-if="product" :product="product" />
  </div>
</template>
