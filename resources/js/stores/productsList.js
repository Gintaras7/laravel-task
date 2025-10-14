import { ref } from 'vue';
import { defineStore } from 'pinia';
import { getProductsListRequest } from '@/api/products';

export const useProductsListStore = defineStore('products-list', () => {
  // state
  const products = ref([]);
  const meta = ref(null);
  const loading = ref(false);
  const error = ref(null);
  const currentPage = ref(1);

  // methods
  const getProducts = async (page = 1) => {
    loading.value = true;
    error.value = null;
    currentPage.value = page;

    try {
      const { data } = await getProductsListRequest(page);

      products.value = data.data;
      meta.value = data.meta;
    } catch (e) {
      error.value = e.response?.data?.message || 'Error fetching products';
    } finally {
      loading.value = false;
    }
  };

  return {
    products,
    meta,
    loading,
    error,
    currentPage,
    getProducts,
  };
});
