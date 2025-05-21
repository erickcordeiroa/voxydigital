<template>
  <Toaster />
  <div class="bg-gray-50 min-h-screen">
    <StoreHeader
      :logo-url="`/storage/${tenant.logo}`"
      :store-name="tenant.name"
      :contact-phone="tenant.whatsapp"
      :coverImage="`/storage/${tenant.cover}`"
    />

    <CartButton
      v-if="!isCartOpen"
      :item-count="cart.length"
      @open-cart="isCartOpen = true"
    />

    <div class="p-4 md:p-6 lg:p-10">

       <!-- Campo de pesquisa -->
      <div class="mb-6 max-w-lg mx-auto">
        <input
          v-model="search"
          type="text"
          placeholder="Pesquisar produto..."
          class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-primary"
        />
      </div>

      <!-- Verificação para exibir mensagem caso não haja produtos nem categorias -->
      <div
        v-if="categories.length == 0 && products.length == 0"
        class="text-center text-gray-500"
      >

        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-16 w-16 text-gray-400 mb-4 mx-auto"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M9.75 9.75h4.5m-2.25-2.25v4.5m-7.5 9h15a2.25 2.25 0 002.25-2.25v-15A2.25 2.25 0 0019.5 3h-15A2.25 2.25 0 002.25 5.25v15A2.25 2.25 0 004.5 22.5z"
          />
        </svg>
        <p class="text-lg font-semibold">Nenhuma informação disponível</p>
        <p class="text-sm">
          Esta empresa ainda não cadastrou produtos ou categorias. Volte mais tarde para conferir as novidades!
        </p>
      </div>

      <div v-else>
        <CategorySlider
          :categories="categories"
          :selected-category="selectedCategory"
          @category-select="filterByCategory"
        />

        <ProductSection
          v-for="category in filteredCategories"
          :key="category.id"
          :category="category"
          :products="filteredProducts(category.id)"
          @add-to-cart="addToCart"
        />
      </div>
    </div>

    <CartSidebar
      v-if="isCartOpen"
      :items="cartWithCategoryNames"
      :total="cartTotal"
      :taxFixed="tenant.tax_fixed"
      @close-cart="isCartOpen = false"
      @remove-item="removeFromCart"
      @increase-quantity="increaseQuantity"
      @decrease-quantity="decreaseQuantity"
      @checkout="finalizarPedido"
    />

    <OrderConfirmationModal
      v-if="showOrderConfirmation"
      @close="showOrderConfirmation = false"
      @submit="submitOrder"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import StoreHeader from "@/components/store/StoreHeader.vue";
import CartButton from "@/components/cart/CartButton.vue";
import CartSidebar from "@/components/cart/CartSidebar.vue";
import OrderConfirmationModal from "@/components/order/OrderConfirmationModal.vue";
import ProductSection from "@/components/products/ProductSection.vue";
import CategorySlider from "@/components/home/CategorySlider.vue";
import { Toaster } from "@/components/ui/sonner";
import { useCart } from "@/composables/useCart";
import type { Category, Product, Tenant } from "@/types/cart"; // Use apenas o composable

const { props } = usePage();
const categories = ref<Category[]>(props.categories as Category[]);
const products = ref<Product[]>(props.products as Product[]);
const tenant = ref<Tenant>(props.tenant as Tenant);
const search = ref("");
const selectedCategory = ref<number | null>(null);

// Use apenas o composable para o carrinho e pedidos
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

const filteredCategories = computed(() => {
  if (search.value.trim()) {
    const filteredProducts = products.value.filter((p) =>
      p.name.toLowerCase().includes(search.value.toLowerCase())
    );
    return categories.value.filter((category) =>
      filteredProducts.some((p) => p.category_id === category.id)
    );
  }
  return selectedCategory.value
    ? categories.value.filter((c) => c.id === selectedCategory.value)
    : categories.value;
});

const filteredProducts = (categoryId: number) => {
  let filtered = products.value.filter((p) => p.category_id === categoryId);
  if (search.value.trim()) {
    filtered = filtered.filter((p) =>
      p.name.toLowerCase().includes(search.value.toLowerCase())
    );
  }
  return filtered;
};

onMounted(() => {
  if (tenant.value.custom_button) {
    document.documentElement.style.setProperty('--custom-button', tenant.value.custom_button);
  }
  if (tenant.value.custom_button_text) {
    document.documentElement.style.setProperty('--custom-button-text', tenant.value.custom_button_text);
  }
  if (tenant.value.custom_title_color) {
    document.documentElement.style.setProperty('--custom-title-color', tenant.value.custom_title_color);
  }
});

const filterByCategory = (categoryId: number) => {
  selectedCategory.value = categoryId;
};
</script>

<style scoped>
::-webkit-scrollbar {
  height: 6px;
  width: 6px;
}
::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.2);
  border-radius: 4px;
}
</style>
