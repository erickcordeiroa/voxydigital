<script setup lang="ts">
import { ref } from "vue";
import { Button } from "@/components/ui/button";
import { Toaster } from "@/components/ui/sonner";
import CartButton from "@/components/cart/CartButton.vue";
import CartSidebar from "@/components/cart/CartSidebar.vue";
import OrderConfirmationModal from "@/components/order/OrderConfirmationModal.vue";
import { useCart } from "@/composables/useCart";
import type { Product, Tenant, Category } from "@/types/cart.ts";

const props = defineProps<{
  product: Product;
  tenant: Tenant;
  categories: Category[];
}>();

const product = ref(props.product);
const tenant = ref(props.tenant);
const categories = ref(props.categories);

const {
  cart,
  isCartOpen,
  showOrderConfirmation,
  cartWithCategoryNames,
  cartTotal,
  addToCart,
  removeFromCart,
  increaseQuantity,
  decreaseQuantity,
  finalizarPedido,
  submitOrder,
} = useCart(categories, tenant);

const showModal = ref(false);

function handleCheckout() {
  showModal.value = true;
}

function handleModalClose() {
  showModal.value = false;
}

function handleModalSubmit(customerInfo: {
  name: string;
  phone: string;
  address: string;
  note?: string;
}) {
  submitOrder(customerInfo);
  showModal.value = false;
}

const formatPhoneNumber = (whatsapp: string) => {
  const cleaned = whatsapp?.replace(/\D/g, "") || "";
  const match = cleaned.match(/^(\d{2})(\d{4,5})(\d{4})$/);
  return match ? `(${match[1]}) ${match[2]}-${match[3]}` : whatsapp;
};
</script>

<template>
  <Toaster />
  <div class="min-h-screen bg-white flex flex-col">
    <CartButton
      v-if="!isCartOpen"
      :item-count="cart.length"
      @open-cart="isCartOpen = true"
    />

    <!-- Imagem do Produto -->
    <div class="w-full relative">
      <button
        @click="$inertia.visit('/')"
        class="absolute top-2 left-2 z-20 p-1 bg-white hover:bg-black/10 rounded transition"
        style="box-shadow: none"
      >
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-6 w-6 text-gray-700"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M15 19l-7-7 7-7"
          />
        </svg>
      </button>
      <img
        :src="`/storage/${product.uri}`"
        :alt="product.name"
        class="w-full h-auto object-cover"
      />
    </div>

    <!-- Detalhes do Produto -->
    <div class="flex-1 px-4 py-4">
      <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ product.name }}</h2>
      <p class="text-gray-600 mb-4">{{ product.description }}</p>

      <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
        <div v-if="product.sale">
          <p class="text-gray-500 line-through text-xs">
            R$
            {{
              (product.price / 100).toLocaleString("pt-BR", { minimumFractionDigits: 2 })
            }}
          </p>
          <p class="text-black font-bold text-2xl">
            R$
            {{
              (product.sale / 100).toLocaleString("pt-BR", { minimumFractionDigits: 2 })
            }}
          </p>
        </div>
        <Button
          @click="addToCart(product)"
          variant="default"
          class="w-full cursor-pointer bg-[var(--custom-button)] text-[var(--custom-button-text)]"
        >
          Adicionar ao Carrinho
        </Button>
      </div>
    </div>

    <!-- Sidebar do Carrinho -->
    <CartSidebar
      v-if="isCartOpen"
      :items="cartWithCategoryNames"
      :total="cartTotal"
      @close-cart="isCartOpen = false"
      @remove-item="removeFromCart"
      @increase-quantity="increaseQuantity"
      @decrease-quantity="decreaseQuantity"
      @checkout="finalizarPedido"
    />

    <!-- Modal de confirmação do pedido -->
    <OrderConfirmationModal
      v-if="showOrderConfirmation"
      @close="handleModalClose"
      @submit="handleModalSubmit"
    />

    <!-- Footer -->
    <footer class="bg-white border-t py-4 text-center text-sm text-gray-600">
      <p>{{ tenant.name }}</p>
      <p class="text-sm">Contato: {{ formatPhoneNumber(tenant.whatsapp) }}</p>
    </footer>
  </div>
</template>
