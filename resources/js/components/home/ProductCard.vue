<template>
  <Card class="hover:shadow-lg transition-shadow max-w-[250px] min-w-[250px]">
    <CardHeader class="min-h-[220px]">
      <img
        :src="`/storage/${product.uri}`"
        :alt="product.name"
        class="rounded-md w-full object-cover"
        style="width: 220px; height: 220px; object-fit: cover; margin: 0 auto;"
      />
    </CardHeader>
    <CardContent class="flex flex-col items-center text-center">
      <h3 class="text-md font-semibold truncate w-full" style="max-width: 200px;">
        {{ product.name }}
      </h3>
      <div v-if="product.sale" class="flex flex-col items-center">
        <p class="text-gray-500 line-through text-xs">
          R$ {{ (product.price / 100).toLocaleString("pt-BR", { minimumFractionDigits: 2 }) }}
        </p>
        <p class="text-black font-bold">
          R$ {{ (product.sale / 100).toLocaleString("pt-BR", { minimumFractionDigits: 2 }) }}
        </p>
      </div>
      <div v-else class="flex flex-col items-center">
        <p class="text-black font-bold">
          R$ {{ (product.price / 100).toLocaleString("pt-BR", { minimumFractionDigits: 2 }) }}
        </p>
      </div>
    </CardContent>
    <CardFooter class="flex-wrap gap-y-2">
      <Button 
        variant="default" 
        class="w-full cursor-pointer bg-[var(--custom-button)] text-[var(--custom-button-text)]" 
        @click="handleAddToCart"
      >
        Adicionar ao Carrinho
      </Button>
      <Button 
        variant="default" 
        class="w-full cursor-pointer bg-[var(--custom-button)] text-[var(--custom-button-text)]" 
        @click="handleViewProduct"
      >
        Ver Produto
      </Button>
    </CardFooter>
  </Card>
</template>

<script setup lang="ts">
import { Card, CardHeader, CardContent, CardFooter } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  product: {
    type: Object,
    required: true
  }
});

const emit = defineEmits(['add-to-cart']);

const handleAddToCart = () => {
  emit('add-to-cart', props.product);
};

const handleViewProduct = () => {
  router.get(
      route("product.show", {slug: props.product.slug})
    )
}
</script>