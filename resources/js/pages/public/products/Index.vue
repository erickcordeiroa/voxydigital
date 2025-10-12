<script setup lang="ts">
import { ref, computed } from "vue";
import { Button } from "@/components/ui/button";
import { Toaster } from "@/components/ui/sonner";
import CartButton from "@/components/cart/CartButton.vue";
import CartSidebar from "@/components/cart/CartSidebar.vue";
import OrderConfirmationModal from "@/components/order/OrderConfirmationModal.vue";
import ProductCard from "@/components/home/ProductCard.vue";
import { useCart } from "@/composables/useCart";
import type { Product, Tenant, Category } from "@/types/cart.ts";

const props = defineProps<{
  product: Product;
  tenant: Tenant;
  categories: Category[];
  relatedProducts?: Product[];
}>();

const product = ref(props.product ?? {});
const tenant = ref(props.tenant ?? {});
const categories = ref(props.categories ?? []);
const relatedProducts = ref(props.relatedProducts ?? []);
const variations = ref(
  Array.isArray(product.value.variations) ? product.value.variations : []
);

const selectedVariationId = ref(variations.value[0]?.id || null);

// Carrossel de imagens
const images = ref(product.value.images && product.value.images.length > 0
    ? product.value.images
  : [`/storage/${product.value.uri}`]
);
const currentImage = ref(0);

function prevImage() {
  currentImage.value = (currentImage.value - 1 + images.value.length) % images.value.length;
}
function nextImage() {
  currentImage.value = (currentImage.value + 1) % images.value.length;
}

const selectedVariation = computed(() =>
  variations.value.find((v) => v.id === selectedVariationId.value)
);

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

function handleAddToCart() {
  addToCart(product.value, selectedVariation.value);
}

function handleModalClose() {
  showOrderConfirmation.value = false;
}

function handleModalSubmit(customerInfo: {
  name: string;
  phone: string;
  address: string;
  note?: string;
}) {
  submitOrder(customerInfo);
  showOrderConfirmation.value = false;
}

const formatPhoneNumber = (whatsapp: string) => {
  const cleaned = whatsapp?.replace(/\D/g, "") || "";
  const match = cleaned.match(/^(\d{2})(\d{4,5})(\d{4})$/);
  return match ? `(${match[1]}) ${match[2]}-${match[3]}` : whatsapp;
};
</script>

<template>
  <Toaster />
  <div class="min-h-screen bg-white flex flex-col items-center">
    <CartButton
      v-if="!isCartOpen"
      :item-count="cart.length"
      @open-cart="isCartOpen = true"
    />

    <!-- Container centralizado -->
    <div
      class="w-full max-w-[1240px] mx-auto flex md:flex-1 flex-col md:flex-row gap-8 md:px-6 md:py-8 items-start"
    >
      <!-- Coluna da imagem -->
      <div class="relative flex-1 flex flex-col items-center justify-center">
        <button
          @click="$inertia.visit('/')"
          class="absolute top-2 left-2 z-20 p-1 rounded transition back-btn"
          style="box-shadow: none"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-6 w-6"
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
        <button
          v-if="images.length > 1"
          @click="prevImage"
          class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/80 rounded-full p-2 shadow z-10"
        >
          <svg
            class="h-5 w-5 text-gray-700"
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
          :src="`/storage/${images[currentImage].uri}`"
          :alt="product.name"
          class="object-cover md:rounded-lg shadow w-full max-w-[520px] max-h-[520px] aspect-square"
        />
        <button
          v-if="images.length > 1"
          @click="nextImage"
          class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/80 rounded-full p-2 shadow z-10"
        >
          <svg
            class="h-5 w-5 text-gray-700"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M9 5l7 7-7 7"
            />
          </svg>
        </button>
        <!-- Indicadores do carrossel -->
        <div
          v-if="images.length > 1"
          class="hidden md:flex absolute -bottom-8 left-1/2 -translate-x-1/2 flex gap-1"
        >
          <span
            v-for="(img, idx) in images"
            :key="idx"
            class="w-2 h-2 rounded-full"
            :class="idx === currentImage ? 'bg-primary' : 'bg-gray-300'"
          ></span>
        </div>
      </div>

      <!-- Coluna dos detalhes -->
      <div
        class="flex-1 px-4 md:px-8 py-4 w-full max-w-lg mx-auto bg-white rounded-lg z-10 relative flex flex-col justify-center"
      >
        <h2 class="text-2xl font-semibold text-gray-800 mb-2 text-left">
          {{ product.name }}
        </h2>
        <p class="text-gray-600 mb-4 text-left">{{ product.description }}</p>

        <!-- Select de variações -->
        <div v-if="variations.length > 0" class="mb-4">
          <label class="block text-sm font-medium mb-1">Tamanho</label>
          <select v-model="selectedVariationId" class="w-full border rounded px-2 py-2">
            <option
              v-for="variation in variations"
              :key="variation.id"
              :value="variation.id"
            >
              {{ variation.size }}
            </option>
          </select>
        </div>

        <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
          <div v-if="product.sale">
            <p class="text-gray-500 line-through text-xs">
              R$
              {{
                (product.price / 100).toLocaleString("pt-BR", {
                  minimumFractionDigits: 2,
                })
              }}
            </p>
            <p class="text-black font-bold text-2xl">
              R$
              {{
                (product.sale / 100).toLocaleString("pt-BR", { minimumFractionDigits: 2 })
              }}
            </p>
          </div>
          <div v-else>
            <p class="text-black font-bold text-2xl">
              R$
              {{
                (product.price / 100).toLocaleString("pt-BR", {
                  minimumFractionDigits: 2,
                })
              }}
            </p>
          </div>
          <Button
            @click="handleAddToCart"
            variant="default"
            class="w-full cursor-pointer bg-[var(--custom-button)] text-[var(--custom-button-text)]"
          >
            Adicionar ao Carrinho
          </Button>
        </div>
      </div>
    </div>

    <!-- Sidebar do Carrinho -->
    <CartSidebar
      v-if="isCartOpen"
      :items="cartWithCategoryNames"
      :total="cartTotal"
      :taxFixed="Number(tenant.tax_fixed)"
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

    <!-- Produtos relacionados -->
    <div v-if="relatedProducts.length > 0" class="w-full max-w-[1240px] mx-auto px-4 md:px-6 mt-12">
      <h3 class="text-xl font-bold mb-4 text-gray-800">Produtos relacionados</h3>
      <div class="flex overflow-x-auto gap-20 pb-4">
        <div
          v-for="relatedProduct in relatedProducts"
          :key="relatedProduct.id"
          class="flex-shrink-0 w-48"
        >
          <ProductCard
            :product="relatedProduct"
            @add-to-cart="addToCart"
          />
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t py-4 text-center text-sm text-gray-600 w-full mt-8">
      <p>{{ tenant.name }}</p>
      <p class="text-sm">Contato: {{ formatPhoneNumber(tenant.whatsapp) }}</p>
    </footer>
  </div>
</template>

<style scoped>
@media (min-width: 768px) {
  .max-w-lg {
    max-width: 480px;
  }
  .rounded-lg {
    border-radius: 1rem;
  }
  .shadow-lg {
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
  }
  .back-btn {
    background: #000 !important;
    color: #fff !important;
  }
  .back-btn svg {
    color: #fff !important;
  }
}
@media (max-width: 767px) {
  .back-btn {
    background: #fff !important;
    color: #222 !important;
  }
  .back-btn svg {
    color: #222 !important;
  }
}

:host,
.min-h-screen {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}
.flex-1 {
  flex: 1 1 0%;
}
</style>
