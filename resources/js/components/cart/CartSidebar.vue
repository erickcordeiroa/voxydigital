<template>
  <div
    class="fixed inset-0 bg-black bg-opacity-40 z-40"
    @click.self="$emit('close-cart')"
  >
    <div
      class="absolute right-0 top-0 w-full max-w-lg bg-white h-full shadow-xl p-6 overflow-y-auto"
    >
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Seu Carrinho</h2>
        <button @click="$emit('close-cart')" class="text-gray-500 hover:text-gray-700">
          ✖
        </button>
      </div>

      <div v-if="items.length">
        <ul
          class="divide-y divide-gray-200 mb-4 max-h-[calc(100vh-400px)] overflow-y-auto"
        >
          <li v-for="item in items" :key="item.id" class="py-4 flex gap-4">
            <img
              :src="item.imageUrl"
              alt="imagem do produto"
              class="w-16 h-16 rounded object-cover"
            />
            <div class="flex-1 min-w-0">
              <div class="flex justify-between items-start gap-2">
                <div class="min-w-0">
                  <h4 class="font-semibold text-md truncate">{{ item.name }}</h4>
                  <p class="text-sm text-muted-foreground truncate">
                    {{ item.categoryName }}
                  </p>
                  <p>
                    <button
                      class="text-red-400 text-sm cursor-pointer whitespace-nowrap"
                      @click="$emit('remove-item', item.id)"
                    >
                      Remover
                    </button>
                  </p>
                </div>
                <div class="flex items-center gap-2 mt-2 flex-shrink-0">
                  <button
                    @click="$emit('decrease-quantity', item.id)"
                    :disabled="item.quantity <= 1"
                    class="px-2 py-1 bg-gray-200 rounded"
                  >
                    -
                  </button>
                  <span class="whitespace-nowrap">{{ item.quantity }}</span>
                  <button
                    @click="$emit('increase-quantity', item.id)"
                    class="px-2 py-1 bg-gray-200 rounded"
                  >
                    +
                  </button>
                </div>
                <p class="mt-2 font-semibold whitespace-nowrap">
                  {{ formatCurrency(item.price * item.quantity) }}
                </p>
              </div>
            </div>
          </li>
        </ul>

        <div class="mb-4 text-right text-lg">
          <p>Subtotal: {{ formatCurrency(total) }}</p>
          <p class="font-bold text-primary mt-2">
            Total: {{ formatCurrency(total) }}
          </p>
        </div>

        <Button variant="default" class="w-full" @click="$emit('checkout')">
          Realizar Pedido
        </Button>
      </div>

      <div v-else class="text-muted-foreground">Seu carrinho está vazio.</div>
    </div>
  </div>
</template>

<script setup>
import { Button } from "@/components/ui/button";

defineProps({
  items: {
    type: Array,
    default: () => []
  },
  total: {
    type: Number,
    default: 0
  }
});

defineEmits([
  'close-cart',
  'remove-item',
  'increase-quantity',
  'decrease-quantity',
  'checkout'
]);

const formatCurrency = (value) => {
  return `R$ ${(value / 100).toLocaleString("pt-BR", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  })}`;
};
</script>