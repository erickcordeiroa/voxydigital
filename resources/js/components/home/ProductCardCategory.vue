<template>
  <Card class="hover:shadow-lg transition-shadow w-full max-w-[250px] md: max-w-full">
    <CardHeader class="px-3">
      <img
        :src="product.uri ? `/storage/${product.uri}` : '/storage/not_found.jpg'"
        :alt="product.name"
        class="rounded-md w-full object-cover aspect-square"
      />
    </CardHeader>
    <CardContent class="flex flex-col items-center text-center px-3">
      <h3 class="text-sm font-semibold line-clamp-2 w-full">
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
    <CardFooter class="px-3 pt-0">
      <Button 
        variant="default" 
        class="w-full cursor-pointer bg-[var(--custom-button)] text-[var(--custom-button-text)] text-sm py-2" 
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

const handleViewProduct = () => {
  router.get(
      route("product.show", {slug: props.product.slug})
    )
}
</script>