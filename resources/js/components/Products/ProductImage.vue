<script setup>
defineProps({
  src: String,
  alt: String,
});
</script>

<template>
  <div class="h-[100px] overflow-hidden rounded-2xl flex items-center justify-center bg-gray-100">
    <img :src="src" :alt="alt" class="max-h-[100px] object-contain" loading="lazy" />
  </div>
</template>
