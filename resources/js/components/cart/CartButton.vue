<template>
  <div class="fixed bottom-4 right-4 z-50">
    <button
      @click="$emit('open-cart')"
      class="bg-primary text-white px-4 py-2 rounded-full shadow-lg cursor-pointer"
    >
      🛒 Carrinho ({{ itemCount }})
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