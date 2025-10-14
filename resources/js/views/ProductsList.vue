<script setup>
import { watchEffect, onBeforeMount } from 'vue';
import { useProductsListStore } from '@/stores/productsList';
import { useTagsListStore } from '@/stores/tagsList';
import ProductCard from '@/components/Products/ProductCard.vue';
import Pagination from '../components/Pagination.vue';
import { storeToRefs } from 'pinia';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();
const productsListStore = useProductsListStore();
const tagsListStore = useTagsListStore();
const { products, meta, loading, error, currentPage } = storeToRefs(productsListStore);

const { tags, loading: loadingTags } = storeToRefs(tagsListStore);

watchEffect(() => {
  const page = parseInt(route.query.page || '1', 10);
  productsListStore.getProducts(page);
});

onBeforeMount(tagsListStore.getPopularTags);

const onPageChange = (page) => {
  router.push({ query: { ...route.query, page } });
};
</script>

<template>
  <div class="m-8">
    <h3 class="font-semibold text-2xl text-gray-900 mb-6">Products</h3>

    <div class="flex flex-col lg:flex-row gap-12">
      <!-- Left side: Products grid -->
      <div class="flex-1">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-3 gap-12">
          <ProductCard
            v-for="product in productsListStore.products"
            :key="product.id"
            :product="product"
            @onClick="($event) => $router.push(`products/${product.id}`)"
          />
        </div>

        <Pagination v-if="meta" :meta="meta" @onPageChange="onPageChange" class="mt-8" />
      </div>

      <!-- Right side: Tags sidebar -->
      <aside class="w-full lg:w-64 shrink-0">
        <h4 class="text-lg font-semibold text-gray-800 mb-3">Popular Tags</h4>

        <div class="flex flex-col gap-2">
          <template v-if="tags.length > 0">
            <div v-for="tag in tags" :key="tag.id" class="text-sm text-left py-2 rounded-lg">
              {{ tag.title }}
              <span class="text-gray-500">({{ tag.count }})</span>
            </div>
          </template>
          <template v-else>
            <p class="text-sm text-gray-400 italic">No tags found</p>
          </template>
        </div>
      </aside>
    </div>
  </div>
</template>
