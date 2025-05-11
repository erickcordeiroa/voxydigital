<template>
    <div class="rounded-xl border p-4 shadow-sm hover:shadow-md transition">
        <img :src="product.image" class="h-32 w-full object-cover rounded-md mb-2" />
        <h2 class="font-bold truncate">{{ product.name }}</h2>
        <p class="text-sm text-gray-500">{{ product.category }}</p>
        <p class="text-lg font-semibold text-primary mt-1">R$ {{ product.price }}</p>
    </div>
</template>

<script setup lang="ts">
defineProps<{ product: any }>();
</script>
