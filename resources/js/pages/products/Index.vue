<script setup lang="ts">
import { ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import ProductCard from '@/components/products/ProductCard.vue';
// import CreateProductModal from '@/components/CreateProductModal.vue';

const products = ref([]); // produtos mockados ou vindos via props
const showModal = ref(false);

</script>

<template>
    <Head title="Produtos" />

    <AppLayout :breadcrumbs="[{ title: 'Produtos', href: '/products' }]">
        <div class="flex items-center justify-between m-6">
            <h1 class="text-2xl font-semibold">Produtos</h1>
            <button @click="showModal = true" class="rounded-lg bg-primary px-4 py-2 text-white hover:bg-primary-dark transition">
                + Novo Produto
            </button>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            <ProductCard v-for="product in products" :key="product.id" :product="product" />
        </div>

        <CreateProductModal v-model:open="showModal" />
    </AppLayout>
</template>
