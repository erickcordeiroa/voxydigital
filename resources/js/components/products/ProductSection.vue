<template>
  <div class="mb-8">
    <div class="flex justify-between items-center mb-4">
      <h2 class="text-2xl font-bold text-[var(--custom-title-color)]">
        {{ category.name }}
      </h2>
      <Button 
        v-if="filteredProducts.length > limit"
        variant="link"
        @click="handleViewCategory"
        class="text-sm text-[var(--custom-title-color)] cursor-pointer"
      >
        Ver mais
      </Button>
    </div>

    <div class="flex overflow-x-auto gap-4">
      <ProductCard
        v-for="product in displayProducts"
        :key="product.id"
        :product="product"
        @add-to-cart="$emit('add-to-cart', product)"
        @click="handleViewProduct(product.slug)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import ProductCard from "@/components/home/ProductCard.vue";
import Button from "@/components/ui/button/Button.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  category: {
    type: Object,
    required: true,
  },
  products: {
    type: Array,
    default: () => [],
  },
  limit: {
    type: Number,
    default: 10
  }
});

defineEmits(["add-to-cart"]);

const filteredProducts = computed(() => {
  return props.products.filter((product) => product.category_id === props.category.id);
});

const displayProducts = computed(() => {
  return filteredProducts.value.slice(0, props.limit);
})

const handleViewProduct = (slug: string) => {
  router.get(route("product.show", { slug: slug }));
};

const handleViewCategory = () => {
  router.get(route("category.public.show", {slug: props.category.slug}))
}
</script>
