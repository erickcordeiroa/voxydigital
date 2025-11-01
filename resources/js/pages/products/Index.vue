<script setup lang="ts">
import { ref, watch, computed } from "vue";
import { Head, router } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import ListProduct from "@/components/products/ListProducts.vue";
import { Toaster } from '@/components/ui/sonner';
import { toast } from 'vue-sonner';
import { useDebounceFn } from "@vueuse/core";
import ConfirmDialog from "@/components/ConfirmDialog.vue";

interface Category {
  id: number;
  name: string;
}

interface Product {
  id: number;
  name: string;
  [key: string]: any;
}

interface PaginatedProducts {
  data: Product[];
  [key: string]: any;
}

interface Filters {
  search?: string;
  category?: string;
}

interface Props {
  categories?: Category[];
  products?: PaginatedProducts;
  filters?: Filters;
}

const props = defineProps<Props>();

// Usar computed para sempre ter os dados atualizados
const products = computed(() => props.products?.data || []);
const pagination = computed(() => props.products || {});
const categories = computed(() => props.categories || []);

// Filtros de busca - sincronizar com as props
const search = ref(props.filters?.search || "");
const category = ref(props.filters?.category || "");
const isLoading = ref(false);
const productToDelete = ref<number | null>(null);

// Watch para atualizar os filtros quando as props mudarem (ex: navegação de volta)
watch(() => props.filters, (newFilters) => {
  if (newFilters) {
    search.value = newFilters.search || "";
    category.value = newFilters.category || "";
  }
}, { deep: true });

// Função de aplicar filtros
function applyFilters() {
  isLoading.value = true;
  
  router.get(
    "/products",
    {
      search: search.value,
      category: category.value,
    },
    { 
      preserveState: false, // MUDANÇA CRÍTICA: permite atualizar os dados
      replace: true,
      only: ['products', 'filters'], // Atualiza apenas products e filters
      onFinish: () => {
        isLoading.value = false;
      }
    }
  );
}

// Debounce para busca de texto (aguarda 500ms após parar de digitar)
const debouncedApplyFilters = useDebounceFn(() => {
  applyFilters();
}, 500);

// Watchers separados para comportamentos diferentes
watch(search, () => {
  debouncedApplyFilters(); // Busca com delay
});

watch(category, () => {
  applyFilters(); // Categoria aplica imediatamente (sem debounce)
});

function deleteProduct(id: number) {
  productToDelete.value = productToDelete.value === id ? null : id;
}
function confirmDelete() {
  if (productToDelete.value !== null) {
    router.delete(`/products/${productToDelete.value}`, {
      onSuccess: (response) => {
        toast.success('Produto excluído com sucesso!');
        productToDelete.value = null;
        products.value = response.data.products;
      },
      onError: (errors) => {
        console.error(errors);
        toast.error('Erro ao excluir produto');
      },
      preserveState: false,
    });
  }
}

function cancelDelete() {
  productToDelete.value = null;
}
</script>

<template>
  <Head title="Produtos" />
  <Toaster />
  <AppLayout :breadcrumbs="[{ title: 'Produtos', href: '/products' }]">
    <!-- Header -->
    <div class="flex items-center justify-between m-6">
      <h1 class="text-2xl font-semibold">Produtos</h1>
      <button
        @click="router.get('/products/create')"
        class="rounded-lg bg-primary px-4 py-2 text-white hover:bg-primary-dark transition cursor-pointer"
      >
        + Novo Produto
      </button>
    </div>

    <!-- Filtros -->
    <div class="px-6 mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="relative">
        <input
          v-model="search"
          placeholder="Buscar por nome"
          class="border p-2 rounded w-full pr-10"
          :disabled="isLoading"
        />
        <!-- Loading indicator -->
        <div v-if="isLoading" class="absolute right-3 top-1/2 -translate-y-1/2">
          <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>
      </div>
      
      <select 
        v-model="category" 
        class="border p-2 rounded w-full"
        :disabled="isLoading"
      >
        <option value="">Todas as categorias</option>
        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
          {{ cat.name }}
        </option>
      </select>
    </div>

    <!-- Lista -->
    <div v-if="isLoading" class="px-6">
      <!-- Loading skeleton -->
      <div class="animate-pulse space-y-4">
        <div class="h-24 bg-gray-200 rounded"></div>
        <div class="h-24 bg-gray-200 rounded"></div>
        <div class="h-24 bg-gray-200 rounded"></div>
      </div>
    </div>
    
    <div
      v-else-if="products.length === 0"
      class="flex flex-col items-center justify-center py-20 text-center text-muted-foreground"
    >
      <h2 class="text-xl font-semibold mb-2">Nenhum produto encontrado</h2>
      <p class="mb-4 max-w-md text-sm">
        Verifique os filtros.
      </p>
    </div>

    <div v-else>
      <ListProduct
        :products="products"
        :pagination="pagination"
        @delete="deleteProduct"
      />
    </div>

    <ConfirmDialog
      v-if="productToDelete !== null"
      :show="productToDelete !== null"
      title="Excluir Produto"
      description="Tem certeza que deseja excluir este produto? Esta ação não pode ser desfeita."
      @confirm="confirmDelete"
      @cancel="cancelDelete"
    />
  </AppLayout>
</template>