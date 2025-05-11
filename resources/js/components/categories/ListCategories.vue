<script setup lang="ts">
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { router } from "@inertiajs/vue3";
import { Edit, Trash2 } from "lucide-vue-next"; // Importa os ícones do Lucide

defineOptions({ name: "ListCategories" });

const props = defineProps<{
  categories: {
    data: Array<{ id: number; name: string; description?: string }>;
    current_page: number;
    last_page: number;
    links: Array<{ url: string | null; label: string; active: boolean }>;
  };
}>();

defineEmits(["edit", "delete"]);

function goToPage(url: string | null) {
  if (url) router.visit(url);
}

</script>

<template>
  <div class="w-full overflow-x-auto">
    <Table class="min-w-full">
      <TableCaption>Lista de todas as categorias registradas</TableCaption>
      <TableHeader>
        <TableRow>
          <TableHead class="w-[100px]">ID</TableHead>
          <TableHead class="flex-grow">Nome</TableHead>
          <TableHead class="w-[150px]">Ações</TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <!-- Verifique se categories.data é um array antes de iterar -->
        <TableRow v-for="category in (props.categories?.data || [])" :key="category?.id">
          <TableCell class="w-[100px] font-medium">{{ category?.id }}</TableCell>
          <TableCell class="flex-grow">{{ category?.name }}</TableCell>
          <TableCell class="w-[150px]">
            <div class="flex space-x-2">
              <!-- Botão de Editar -->
              <button
                @click="$emit('edit', category)"
                class="text-gray-500 hover:text-blue-500 transition cursor-pointer"
              >
                <Edit class="w-5 h-5" />
              </button>
              <!-- Botão de Excluir -->
              <button
                @click="$emit('delete', category.id)"
                class="text-gray-500 hover:text-red-500 transition cursor-pointer"
              >
                <Trash2 class="w-5 h-5" />
              </button>
            </div>
          </TableCell>
        </TableRow>
      </TableBody>
    </Table>

    <!-- Paginação -->
    <div class="flex justify-center mt-6 space-x-2">
      <button
        v-for="link in (categories?.links || [])"
        :key="link.label"
        @click="goToPage(link.url)"
        v-html="link.label"
        class="px-3 py-1 rounded border text-sm cursor-pointer"
        :class="{
          'bg-primary text-white': link.active,
          'text-gray-700 hover:bg-gray-200': !link.active,
          'cursor-not-allowed opacity-50': !link.url,
        }"
        :disabled="!link.url"
      />
    </div>
  </div>
</template>