<script setup lang="ts">
import { defineProps, defineEmits } from "vue";
import { Edit, Trash2 } from "lucide-vue-next"; // Importa os ícones do Lucide

// Define as props recebidas
defineProps({
  products: {
    type: Array,
    required: true,
  },
  pagination: {
    type: Object,
    required: true,
  },
});

// Define os eventos emitidos
defineEmits(["edit", "delete"]);
</script>
<template>
  <div class="grid gap-4 px-6 pb-6">
    <div
      v-for="product in products"
      :key="product.id"
      class="flex items-center justify-between rounded-lg border p-4 shadow-sm bg-white"
    >
      <div class="flex items-center gap-4">
        <img
          :src="product.uri"
          alt="Imagem do produto"
          class="h-16 w-16 object-cover rounded-md border"
        />
        <div>
          <h3 class="text-lg font-semibold">{{ product.name }}</h3>
          <p class="text-sm text-muted-foreground">
            {{ product.category?.name || "Sem categoria" }}
          </p>
          <p class="text-sm mt-1 text-gray-500 truncate max-w-md">
            {{ product.description }}
          </p>
          <span
            class="text-xs mt-1 inline-block px-2 py-1 rounded bg-green-100 text-green-800"
            v-if="product.status === 1"
            >Ativo</span
          >
          <span
            class="text-xs mt-1 inline-block px-2 py-1 rounded bg-red-100 text-red-800"
            v-else
            >Inativo</span
          >
        </div>
      </div>

      <div class="text-right">
        <p :class="product.sale ? 'text-gray-500 line-through' : 'font-semibold'">
          {{
            (product.price / 100).toLocaleString("pt-BR", {
              style: "currency",
              currency: "BRL",
            })
          }}
        </p>
        <p v-if="product.sale" class="font-semibold text-primary text-lg">
          {{
            (product.sale / 100).toLocaleString("pt-BR", {
              style: "currency",
              currency: "BRL",
            })
          }}
        </p>
        <div class="mt-2 flex gap-2 justify-end">
          <!-- Ícone de Editar -->
          <button
            @click="$emit('edit', product)"
            class="text-gray-500 hover:text-blue-500 transition cursor-pointer"
          >
            <Edit class="w-5 h-5" />
          </button>
          <!-- Ícone de Excluir -->
          <button
            @click="$emit('delete', product.id)"
            class="text-gray-500 hover:text-red-500 transition cursor-pointer"
          >
            <Trash2 class="w-5 h-5" />
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Paginação -->
  <div v-if="pagination.links.length > 3" class="flex justify-center mt-4 mb-10">
    <nav class="flex gap-2">
      <button
        v-for="link in pagination.links"
        :key="link.label"
        @click="link.url && router.get(link.url)"
        v-html="link.label"
        class="px-3 py-1 rounded border text-sm"
        :class="{
          'bg-primary text-white': link.active,
          'text-gray-600 hover:bg-gray-100': !link.active,
        }"
        :disabled="!link.url"
      />
    </nav>
  </div>
</template>