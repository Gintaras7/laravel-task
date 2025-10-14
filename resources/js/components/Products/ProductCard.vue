<script setup>
import { formatDate } from '@/utils/date';
import Tag from '@/components/Tag.vue';
import ProductImage from './ProductImage.vue';

defineProps({
  product: {
    type: Object,
    required: true,
  },
});

defineEmits(['onClick']);
</script>

<template>
  <div
    @click="$emit('onClick', product)"
    :aria-label="`View details for product ${product.sku}`"
    class="cursor-pointer transition-transform transform hover:scale-[1.02] hover:shadow-lg rounded-2xl bg-white"
  >
    <ProductImage :src="product.photo" :alt="`Product image of ${product.sku}`" />

    <div class="p-6 flex flex-col flex-grow">
      <h3 class="font-semibold text-2xl text-gray-900 mb-2 truncate">
        {{ product.sku }}
        <span class="text-gray-500 font-normal">({{ product.size }})</span>
      </h3>
      <p class="text-gray-700 flex-grow mb-4 line-clamp-3">
        {{ product.description }}
      </p>

      <div class="text-sm text-gray-500 mb-3">
        External Updated:
        <time>{{ formatDate(product.external_updated_at) }}</time>
      </div>

      <div class="flex flex-wrap gap-2">
        <Tag v-for="tag in product.tags" :key="tag.id" :tag="tag" />
      </div>
    </div>
  </div>
</template>
