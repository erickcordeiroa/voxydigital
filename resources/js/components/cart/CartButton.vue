<template>
  <div class="fixed bottom-4 right-4 z-50">
    <button
      @click="$emit('open-cart')"
      class="relative bg-primary text-white p-3 rounded-full shadow-lg cursor-pointer flex items-center justify-center"
      aria-label="Abrir carrinho"
    >
      <span class="text-2xl">🛒</span>
      <span
        v-if="itemCount > 0"
        class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center border-2 border-white"
      >
        {{ itemCount }}
      </span>
    </button>
  </div>
</template>

<script setup lang="ts">
defineProps({
  itemCount: {
    type: Number,
    default: 0
  }
});

defineEmits(['open-cart']);
</script>