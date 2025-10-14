<script setup>
import { formatDate } from '@/utils/date';
import Tag from '@/components/Tag.vue';
import ProductImage from './ProductImage.vue';
import ProductStocks from './ProductStocks.vue';

defineProps({
  product: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <ProductImage :src="product.photo" :alt="`Product image of ${product.sku}`" />

  <div class="p-6 flex flex-col flex-grow space-y-4 text-gray-800">
    <!-- Title -->
    <div>
      <h3 class="font-semibold text-2xl text-gray-900 truncate">
        {{ product.sku }}
        <span class="text-gray-500 font-normal">({{ product.size }})</span>
      </h3>
      <p class="text-gray-700 line-clamp-3 mt-2">
        {{ product.description }}
      </p>
    </div>

    <!-- External Update Time -->
    <div class="text-sm text-gray-500">
      <span class="font-medium text-gray-600">External Updated: </span>
      <time>{{ formatDate(product.external_updated_at) }}</time>
    </div>

    <!-- Tags -->
    <div>
      <h4 class="text-sm font-semibold text-gray-600 uppercase mb-2">Tags</h4>
      <div class="flex flex-wrap gap-2">
        <template v-if="product.tags.length === 0">
          <span class="text-xs text-gray-400 italic">No tags</span>
        </template>
        <Tag v-for="tag in product.tags" :key="tag.id" :tag="tag" />
      </div>
    </div>

    <!-- Stocks -->
    <div>
      <h4 class="text-sm font-semibold text-gray-600 uppercase mb-2">Stocks</h4>
      <template v-if="product.stocks.length === 0">
        <span class="text-xs text-gray-400 italic">No stocks</span>
      </template>
      <ProductStocks :stocks="product.stocks" />
    </div>
  </div>
</template>
