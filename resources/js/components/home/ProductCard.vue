<template>
  <Card class="hover:shadow-lg transition-shadow">
    <CardHeader>
      <img
        :src="`/storage/${product.uri}`"
        :alt="product.name"
        class="rounded-md w-full h-40 object-cover"
      />
    </CardHeader>
    <CardContent>
      <h3 class="text-md font-semibold truncate">{{ product.name }}</h3>
      <div v-if="product.sale">
        <p class="text-gray-500 line-through text-xs">
          R$ {{ (product.price / 100).toLocaleString("pt-BR", { minimumFractionDigits: 2 }) }}
        </p>
        <p class="text-black font-bold">
          R$ {{ (product.sale / 100).toLocaleString("pt-BR", { minimumFractionDigits: 2 }) }}
        </p>
      </div>
      <div v-else>
        <p class="text-black font-bold">
          R$ {{ (product.price / 100).toLocaleString("pt-BR", { minimumFractionDigits: 2 }) }}
        </p>
      </div>
    </CardContent>
    <CardFooter>
      <Button variant="default" class="w-full cursor-pointer" @click="addToCart(product)">
        Adicionar ao Carrinho
      </Button>
    </CardFooter>
  </Card>
</template>

<script setup lang="ts">
import { Card, CardHeader, CardContent, CardFooter } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { defineProps } from "vue";

const props = defineProps({
  product: Object,
  addToCart: Function,
});
</script>