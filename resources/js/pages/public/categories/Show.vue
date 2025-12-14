<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import StoreHeader from "@/components/store/StoreHeader.vue";
import CartButton from "@/components/cart/CartButton.vue";
import CartSidebar from "@/components/cart/CartSidebar.vue";
import ProductCardCategory from "@/components/home/ProductCardCategory.vue";
import { Toaster } from "@/components/ui/sonner";
import { useCart } from "@/composables/useCart";
import { Input } from "@/components/ui/input";

interface Category {
    id: number;
    name: string;
    slug: string;
    description?: string;
}

interface Product {
    id: number;
    name: string;
    description: string;
    price: number;
    category_id: number;
    status: boolean;
    slug: string;
    uri?: string;
    variations?: any[];
    images?: any[];
}

interface Tenant {
    id: number;
    name: string;
    logo: string;
    cover: string;
    whatsapp: string;
    custom_button?: string;
    custom_button_text?: string;
    custom_title_color?: string;
    tax_fixed?: number;
}

interface Props {
    category: Category;
    products: Product[];
    categories: Category[];
    tenant: Tenant;
}

const props = defineProps<Props>();

const category = ref<Category>(props.category);
const products = ref<Product[]>(props.products);
const categories = ref<Category[]>([props.category]);
const tenant = ref<Tenant>(props.tenant);
const search = ref("");

const {
    cart,
    isCartOpen,
    cartWithCategoryNames,
    cartTotal,
    addToCart,
    removeFromCart,
    increaseQuantity,
    decreaseQuantity,
    finalizarPedido,
} = useCart(categories, tenant);

const filteredProducts = computed(() => {
    if (search.value.trim()) {
        return products.value.filter((p) =>
            p.name.toLowerCase().includes(search.value.toLowerCase())
        );
    }
    return products.value;
});

onMounted(() => {
    if (tenant.value.custom_button) {
        document.documentElement.style.setProperty(
            "--custom-button",
            tenant.value.custom_button
        );
    }
    if (tenant.value.custom_button_text) {
        document.documentElement.style.setProperty(
            "--custom-button-text",
            tenant.value.custom_button_text
        );
    }
    if (tenant.value.custom_title_color) {
        document.documentElement.style.setProperty(
            "--custom-title-color",
            tenant.value.custom_title_color
        );
    }
});
</script>

<template>
    <Toaster />
    <div class="bg-gray-50 min-h-screen">
        <StoreHeader
            :logo-url="`/storage/${tenant.logo}`"
            :store-name="tenant.name"
            :contact-phone="tenant.whatsapp"
            :cover-image="`/storage/${tenant.cover}`"
        />

        <CartButton
            v-if="!isCartOpen"
            :item-count="cart.length"
            @open-cart="isCartOpen = true"
        />

        <div class="p-4 md:p-6 lg:p-10 ">

            <div class="absolute top-2 left-2 z-20">
            <!-- Botão de Voltar -->
            <button
                @click="router.visit('/')"
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
            
            </div>

            <!-- Navegação -->
            <div class="mb-6">
                <nav class="text-sm breadcrumbs text-center ">
                    <a href="/" class="text-[var(--custom-title-color)] hover:underline">Início</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-600">{{ category.name }}</span>
                </nav>
            </div>

            <!-- Título da categoria -->
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-[var(--custom-title-color)] text-center">{{ category.name }}</h1>
                <p class="text-sm text-gray-500 mt-2 text-center">{{ products.length }} produtos encontrados</p>
            </div>

            <!-- Campo de pesquisa -->
            <div class="mb-6 max-w-lg mx-auto">
                <Input
                    v-model="search"
                    type="text"
                    placeholder="Pesquisar produtos nesta categoria..."
                    class="w-full"
                />
            </div>

            <!-- Grid de produtos -->
            <div v-if="filteredProducts.length > 0" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 justify-items-center">
                <ProductCardCategory
                    v-for="product in filteredProducts"
                    :key="product.id"
                    :product="product"
                    @add-to-cart="addToCart"
                />
            </div>

            <div v-else class="text-center py-20">
                <p class="text-lg font-semibold text-gray-600">Nenhum produto encontrado</p>
                <p class="text-sm text-gray-500">Tente ajustar sua pesquisa</p>
            </div>
        </div>

        <CartSidebar
            v-if="isCartOpen"
            :items="cartWithCategoryNames"
            :total="cartTotal"
            :tax-fixed="tenant.tax_fixed"
            @close-cart="isCartOpen = false"
            @remove-item="removeFromCart"
            @increase-quantity="increaseQuantity"
            @decrease-quantity="decreaseQuantity"
            @checkout="finalizarPedido"
        />
    </div>
</template>

<style scoped>
.back-btn {
  background: var(--custom-button) !important;
  color: var(--custom-button-text) !important;
}
.back-btn svg {
  color: var(--custom-button-text) !important;
}
</style>