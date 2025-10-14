import { ref } from 'vue';
import { defineStore } from 'pinia';
import { getPopularTagsRequest } from '@/api/tags';

export const useTagsListStore = defineStore('tags-list', () => {
  // state
  const tags = ref([]);
  const loading = ref(false);

  // methods
  const getPopularTags = async (reload = false) => {
    if (tags.value.length > 0 && !reload) {
      return;
    }

    loading.value = true;

    try {
      const { data } = await getPopularTagsRequest();

      tags.value = data;
    } catch {
    } finally {
      loading.value = false;
    }
  };

  return {
    tags,
    loading,
    getPopularTags,
  };
});
