<script setup lang="ts">
import { ref, watch } from "vue";
import { Head, router } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import CreateProductModal from "@/components/products/CreateProductModal.vue";
import ListProduct from "@/components/products/ListProducts.vue";

const props = defineProps({
  categories: Array,
  products: Object,
  filters: Object,
});

const products = ref(props.products.data);
const pagination = ref(props.products);

const showModal = ref(false);
const categories = ref(props.categories);
const isEditing = ref(false);
const selectedProduct = ref(null);

// Filtros de busca
const search = ref(props.filters.search || "");
const category = ref(props.filters.category || "");

function applyFilters() {
  router.get(
    "/products",
    {
      search: search.value,
      category: category.value,
    },
    { preserveState: true, replace: true }
  );
}

// Atualizar a lista de produtos quando props.products mudar
watch(
  () => props.products,
  (newProducts) => {
    products.value = newProducts.data;
    pagination.value = newProducts;
  }
);

function openCreateModal() {
  isEditing.value = false;
  selectedProduct.value = null;
  showModal.value = true;
}

function openEditModal(product) {
  isEditing.value = true;
  selectedProduct.value = {
    ...product,
    status: product.status === 1 ? 'active' : 'inactive',
    image: product.uri
  };
  showModal.value = true;
}

function deleteProduct(id: number) {
  if (confirm("Tem certeza que deseja excluir este produto?")) {
    router.delete(`/products/${id}`, {
      onSuccess: () => {
        products.value = products.value.filter((product) => product.id !== id);
      },
    });
  }
}
</script>
<template>
  <Head title="Produtos" />
  <AppLayout :breadcrumbs="[{ title: 'Produtos', href: '/products' }]">
    <!-- Header -->
    <div class="flex items-center justify-between m-6">
      <h1 class="text-2xl font-semibold">Produtos</h1>
      <button
        @click="openCreateModal"
        class="rounded-lg bg-primary px-4 py-2 text-white hover:bg-primary-dark transition"
      >
        + Novo Produto
      </button>
    </div>

    <!-- Filtros -->
    <div class="px-6 mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
      <input
        v-model="search"
        @input="applyFilters"
        placeholder="Buscar por nome"
        class="border p-2 rounded w-full"
      />
      <select v-model="category" @change="applyFilters" class="border p-2 rounded w-full">
        <option value="">Todas as categorias</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
          {{ cat.name }}
        </option>
      </select>
    </div>

    <!-- Lista -->
    <div
      v-if="products.length === 0"
      class="flex flex-col items-center justify-center py-20 text-center text-muted-foreground"
    >
      <!-- Empty state -->
      <h2 class="text-xl font-semibold mb-2">Nenhum produto encontrado</h2>
      <p class="mb-4 max-w-md text-sm">
        Verifique os filtros ou cadastre um novo produto.
      </p>
    </div>

    <div v-else>
      <ListProduct
        :products="products"
        :pagination="pagination"
        @edit="openEditModal"
        @delete="deleteProduct"
      />
    </div>
    <!-- Modal -->
    <CreateProductModal
      :open="showModal"
      :isEditing="isEditing"
      :product="selectedProduct"
      :categories="categories"
      @update:open="(value) => (showModal = value)"
      @product-saved="(product) => console.log('Produto salvo:', product)"
    />
  </AppLayout>
</template>