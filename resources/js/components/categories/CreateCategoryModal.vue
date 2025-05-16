<script setup lang="ts">
import {
  Dialog,
  DialogContent,
  DialogTitle,
  DialogOverlay,
} from "@/components/ui/dialog";
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import { Toaster } from "@/components/ui/sonner";
import { toast } from 'vue-sonner';

const props = defineProps<{ 
  open: boolean; 
  category?: { id: number; name: string; description: string } | null; 
  isEditing?: boolean;
}>();
const emit = defineEmits(["update:open", "category-created"]);

const form = ref({
  name: "",
  description: "",
});

function resetForm() {
  form.value = {
    name: '',
    description: ''
  }
}

const errors = ref({
  name: "",
});

// Preenche o formulário ao editar
watch(
  () => props.category,
  (newCategory) => {
    if (newCategory) {
      form.value.name = newCategory.name;
      form.value.description = newCategory.description;
    } else {
      form.value.name = "";
      form.value.description = "";
    }
  },
  { immediate: true }
);

function validate() {
  let isValid = true;
  if (!form.value.name.trim()) {
    errors.value.name = "O nome da categoria é obrigatório.";
    isValid = false;
  } else {
    errors.value.name = "";
  }
  return isValid;
}

function submit() {
  if (!validate()) return;

  if (props.isEditing && props.category) {
    // Atualiza a categoria
    router.put(`/categories/${props.category.id}`, form.value, {
      onSuccess: (response) => {
        toast.success('Categoria alterada com sucesso!');
        resetForm();
        emit("category-created", response.data); // Emite a categoria atualizada
        emit("update:open", false);
      },
      onError: (serverErrors) => {
        toast.error('Ocorreu um erro ao alterar a categoria');
        console.error(serverErrors);
      },
    });
  } else {
    // Cria uma nova categoria
    router.post("/categories", form.value, {
      onSuccess: (response) => {
        toast.success('Categoria criada com sucesso!');
        resetForm();
        emit("category-created", response.data); // Emite a nova categoria criada
        emit("update:open", false);
      },
      onError: (serverErrors) => {
        toast.error('Ocorreu um erro ao criar a categoria');
        console.error(serverErrors);
      },
    });
  }
}
</script>

<template>
  <Toaster />
  <Dialog :open="open" @update:open="(value) => emit('update:open', value)">
    <DialogOverlay class="fixed inset-0 bg-black/50 z-40" />

    <DialogContent
      class="fixed z-50 top-1/2 left-1/2 w-full max-w-lg -translate-x-1/2 -translate-y-1/2 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg"
    >
      <DialogTitle class="text-xl font-bold mb-4">
        {{ isEditing ? "Editar Categoria" : "Nova Categoria" }}
      </DialogTitle>

      <form class="space-y-4" @submit.prevent="submit">
        <div>
          <input
            v-model="form.name"
            type="text"
            placeholder="Nome da Categoria"
            class="w-full rounded-md border px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
          />
          <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
        </div>

        <textarea
          v-model="form.description"
          placeholder="Descrição da categoria"
          class="w-full rounded-md border px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
        />

        <div class="text-right">
          <button
            type="submit"
            class="rounded-md bg-primary px-4 py-2 text-white hover:bg-primary-dark transition"
          >
            {{ isEditing ? "Salvar Alterações" : "Salvar" }}
          </button>
        </div>
      </form>
    </DialogContent>
  </Dialog>
</template>