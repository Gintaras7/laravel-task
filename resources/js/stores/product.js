import { ref } from 'vue';
import { defineStore } from 'pinia';
import { getProductRequest } from '@/api/products';

export const useProductStore = defineStore('product', () => {
  // state
  const product = ref(null);
  const loading = ref(false);
  const error = ref(null);

  // methods
  const getProduct = async (id = 1) => {
    loading.value = true;
    error.value = null;

    try {
      const data = await getProductRequest(id);

      product.value = data.data;
    } catch (e) {
      error.value = e.response?.data?.message || 'Error while fetching a product';
    } finally {
      loading.value = false;
    }
  };

  return {
    product,
    loading,
    error,
    getProduct,
  };
});
