<template>
  <div class="mb-8">
    <h3 class="text-[var(--custom-title-color)] text-lg font-bold mb-3">{{ category.name }}</h3>
    <div class="flex overflow-x-auto gap-4">
      <ProductCard
        v-for="product in filteredProducts"
        :key="product.id"
        :product="product"
        @add-to-cart="$emit('add-to-cart', product)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import ProductCard from "@/components/home/ProductCard.vue";

const props = defineProps({
  category: {
    type: Object,
    required: true
  },
  products: {
    type: Array,
    default: () => []
  }
});

defineEmits(['add-to-cart']);

const filteredProducts = computed(() => {
  return props.products.filter(product => product.category_id === props.category.id);
});
</script>