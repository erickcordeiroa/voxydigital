<template>
  <div class="fixed bottom-4 right-4 z-50">
    <button
      @click="$emit('open-cart')"
      class="relative bg-[var(--custom-button)] text-[var(--custom-button-text)] p-3 rounded-full shadow-lg cursor-pointer flex items-center justify-center"
      aria-label="Abrir carrinho"
    >
      <span class="text-2xl">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-shopping-cart-icon lucide-shopping-cart"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
      </span>
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