<script setup lang="ts">
import { ref } from "vue";
import AppLayout from "@/layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import ListCategories from "@/components/categories/ListCategories.vue";
import CreateCategoryModal from "@/components/categories/CreateCategoryModal.vue";
import ConfirmDialog from "@/components/ConfirmDialog.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
  categories: {
    data: Array<{ id: number; name: string; description: string }>;
    current_page: number;
    last_page: number;
  };
}>();

const categories = ref(props.categories.data || []);
const showModal = ref(false);
const isEditing = ref(false);
const editingCategory = ref(null);
const categoryToDelete = ref(null);

function handleCategoryCreated(newCategory) {
  if (isEditing.value && editingCategory.value) {
    const index = categories.value.findIndex((cat) => cat.id === editingCategory.value.id);
    if (index !== -1) {
      categories.value[index] = newCategory;
    }
  } else {
    categories.value.push(newCategory);
  }
  showModal.value = false;
  isEditing.value = false;
  editingCategory.value = null;
}

function updateCategory(category) {
  editingCategory.value = category;
  isEditing.value = true;
  showModal.value = true;
}

function deleteCategory(categoryId) {
  categoryToDelete.value = categoryId;
}

function confirmDelete() {
  if (categoryToDelete.value !== null) {
    router.delete(`/categories/${categoryToDelete.value}`, {
      onSuccess: () => {
        categories.value = categories.value.filter((cat) => cat.id !== categoryToDelete.value);
        categoryToDelete.value = null;
      },
      onError: (errors) => {
        console.error(errors);
      },
    });
  }
}

function cancelDelete() {
  categoryToDelete.value = null;
}
</script>

<template>
  <Head title="Categorias" />

  <AppLayout :breadcrumbs="[{ title: 'Categorias', href: '/categories' }]">
    <div class="flex items-center justify-between m-6">
      <h1 class="text-2xl font-semibold">Categorias</h1>
      <button
        @click="
          () => {
            showModal = true;
            isEditing = false;
            editingCategory = null;
          }
        "
        class="rounded-lg bg-primary px-4 py-2 text-white hover:bg-primary-dark transition"
      >
        + Nova Categoria
      </button>
    </div>

    <div
      v-if="categories.length === 0"
      class="flex flex-col items-center justify-center py-20 text-center text-muted-foreground"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        class="h-20 w-20 mb-4 text-gray-400"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          stroke-width="1.5"
          d="M3 7l6 6-6 6M21 7l-6 6 6 6"
        />
      </svg>
      <h2 class="text-xl font-semibold mb-2">Nenhuma categoria cadastrada</h2>
      <p class="mb-4 max-w-md text-sm">
        Você ainda não possui nenhuma categoria. Comece agora cadastrando a primeira!
      </p>
    </div>

    <div v-else class="mx-6 my-4">
      <ListCategories
        :categories="props.categories"
        @edit="updateCategory"
        @delete="deleteCategory"
      />
    </div>

    <CreateCategoryModal
      v-model:open="showModal"
      :category="editingCategory"
      :is-editing="isEditing"
      @category-created="handleCategoryCreated"
    />

    <ConfirmDialog
      v-if="categoryToDelete !== null"
      :open="categoryToDelete !== null"
      title="Excluir Categoria"
      description="Tem certeza que deseja excluir esta categoria? Esta ação não pode ser desfeita."
      @confirm="confirmDelete"
      @cancel="cancelDelete"
    />
  </AppLayout>
</template>