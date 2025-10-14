<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  meta: {
    type: Object,
    required: true,
  },
});

const emit = defineEmits(['onPageChange']);

const pagesToShow = computed(() => {
  const current = props.meta.current_page;
  const last = Math.ceil(props.meta.total / props.meta.per_page);
  const range = [];
  const start = Math.max(1, current - 2);
  const end = Math.min(last, current + 2);

  for (let i = start; i <= end; i++) range.push(i);

  return range;
});

const changePage = (page) => {
  const lastPage = Math.ceil(props.meta.total / props.meta.per_page);

  if (page >= 1 && page <= lastPage) {
    emit('onPageChange', page);
  }
};
</script>

<template>
  <div class="flex justify-center mt-12 space-x-3">
    <button
      @click="changePage(meta.current_page - 1)"
      :disabled="meta.current_page === 1"
      class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-100 transition"
      aria-label="Previous page"
    >
      Prev
    </button>

    <button
      v-for="page in pagesToShow"
      :key="page"
      @click="changePage(page)"
      :class="[
        'px-4 py-2 rounded-md border text-sm transition-colors duration-150 cursor-pointer',
        page === meta.current_page
          ? 'bg-blue-600 text-white border-blue-600'
          : 'bg-white text-gray-800 border-gray-300 hover:bg-blue-50',
      ]"
      :aria-current="page === meta.current_page ? 'page' : false"
      :aria-label="`Go to page ${page}`"
    >
      {{ page }}
    </button>

    <button
      @click="changePage(meta.current_page + 1)"
      :disabled="meta.current_page === meta.last_page"
      class="px-4 py-2 rounded-md border border-gray-300 text-gray-700 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-gray-100 transition"
      aria-label="Next page"
    >
      Next
    </button>
  </div>
</template>
