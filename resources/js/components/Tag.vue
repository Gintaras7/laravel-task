<script setup>
defineProps({
  tag: {
    type: Object,
  },
});
</script>

<template>
  <span
    class="bg-blue-50 border border-blue-200 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold select-none whitespace-nowrap"
  >
    <slot>
      {{ tag.title }}
    </slot>
  </span>
</template>
