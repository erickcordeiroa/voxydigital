<template>
  <div
    v-if="isCartOpen"
    class="fixed inset-0 bg-black bg-opacity-40 z-40"
    @click="closeCart"
  >
    <div
      class="absolute right-0 top-0 w-full max-w-lg bg-white h-full shadow-xl p-6 overflow-y-auto"
    >
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Seu Carrinho</h2>
        <button @click="closeCart" class="text-gray-500 hover:text-gray-700">
          ✖
        </button>
      </div>

      <div v-if="cart.length">
        <ul
          class="divide-y divide-gray-200 mb-4 max-h-[calc(100vh-400px)] overflow-y-auto"
        >
          <li v-for="item in cart" :key="item.id" class="py-4 flex gap-4">
            <img
              :src="`/storage/${item.uri}`"
              alt="imagem do produto"
              class="w-16 h-16 rounded object-cover"
            />
            <div class="flex-1 min-w-0">
              <div class="flex justify-between items-start gap-2">
                <div class="min-w-0">
                  <h4 class="font-semibold text-md truncate">{{ item.name }}</h4>
                  <p class="text-sm text-muted-foreground truncate">
                    {{ getCategoryName(item.category) }}
                  </p>
                  <p>
                    <button
                      class="text-red-400 text-sm cursor-pointer whitespace-nowrap"
                      @click.stop="removeFromCart(item.id, $event)"
                    >
                      Remover
                    </button>
                  </p>
                </div>
                <div class="flex items-center gap-2 mt-2 flex-shrink-0">
                  <button
                    @click.stop="item.quantity--"
                    :disabled="item.quantity <= 1"
                    class="px-2 py-1 bg-gray-200 rounded"
                  >
                    -
                  </button>
                  <span class="whitespace-nowrap">{{ item.quantity }}</span>
                  <button @click.stop="item.quantity++" class="px-2 py-1 bg-gray-200 rounded">
                    +
                  </button>
                </div>
                <p class="mt-2 font-semibold whitespace-nowrap">
                  R$
                  {{
                    (
                      ((item.sale ? item.sale : item.price) * item.quantity) /
                      100
                    ).toLocaleString("pt-BR", {
                      minimumFractionDigits: 2,
                      maximumFractionDigits: 2,
                    })
                  }}
                </p>
              </div>
            </div>
          </li>
        </ul>

        <div class="mb-4">
          <label for="obs" class="block text-sm font-medium mb-1"
            >Observações do Pedido</label
          >
          <textarea
            id="obs"
            v-model="orderNote"
            rows="3"
            placeholder="Ex: Entregar após às 18h"
            class="w-full border border-gray-300 rounded px-3 py-2"
          ></textarea>
        </div>

        <div class="mb-4 text-right text-lg">
          <p>
            Subtotal: R$
            {{
              (cartTotal / 100).toLocaleString("pt-BR", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
            }}
          </p>
          <p class="font-bold text-primary mt-2">
            Total: R$
            {{
              ((cartTotal) / 100).toLocaleString("pt-BR", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
              })
            }}
          </p>
        </div>

        <Button variant="default" class="w-full" @click="finalizarPedido">
          Realizar Pedido
        </Button>
      </div>

      <div v-else class="text-muted-foreground">Seu carrinho está vazio.</div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { defineProps, computed, ref } from "vue";
import { Button } from "@/components/ui/button";

const orderNote = ref("");

const props = defineProps({
  isCartOpen: Boolean,
  cart: Array,
  closeCart: Function,
  removeFromCart: Function,
  finalizarPedido: Function,
  getCategoryName: Function,
});

const cartTotal = computed(() => {
  return props.cart.reduce(
    (total, item) => total + (item.sale ? item.sale : item.price) * item.quantity,
    0
  );
});
</script>
