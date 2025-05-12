<script setup lang="ts">
import {
  Dialog,
  DialogContent,
  DialogTitle,
  DialogOverlay,
} from "@/components/ui/dialog";
import { ref, watch } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
  open: boolean;
  product?: {
    id: number;
    name: string;
    price: number;
    sale?: number;
    category_id: number;
    status: string;
    description: string;
    image: string | null;
    video?: string;
    note: string;
  } | null;
  isEditing?: boolean;
  categories: { id: number; name: string }[];
}>();

const emit = defineEmits(["update:open", "product-saved"]);

const form = ref({
  name: "",
  price: "",
  sale: "",
  category_id: "",
  status: "active",
  description: "",
  image: null as File | null,
  video: "",
  note: ""
});

const imagePreview = ref<string | null>(null);
const errors = ref({
  name: "",
  price: "",
  sale: "",
  category_id: "",
  status: "",
  description: "",
});

// Função para aplicar máscara de reais
function formatToCurrency(value: string): string {
  const numericValue = value.replace(/\D/g, ""); // Remove tudo que não for número
  const formattedValue = (Number(numericValue) / 100).toLocaleString("pt-BR", {
    style: "currency",
    currency: "BRL",
  });
  return formattedValue;
}

// Watchers para aplicar a máscara nos campos de preço e promoção
watch(
  () => form.value.price,
  (newValue) => {
    form.value.price = formatToCurrency(newValue);
  }
);

watch(
  () => form.value.sale,
  (newValue) => {
    form.value.sale = formatToCurrency(newValue);
  }
);

watch(
  () => props.product,
  (newProduct) => {
    if (newProduct) {
      form.value.name = newProduct.name;
      form.value.price = formatToCurrency(newProduct.price.toString());
      form.value.sale = newProduct.sale
        ? formatToCurrency(newProduct.sale.toString())
        : "";
      form.value.category_id = newProduct.category_id.toString();
      form.value.status = newProduct.status;
      form.value.description = newProduct.description;
      form.value.video = newProduct.video || "";
      imagePreview.value = newProduct.image || null;
      form.value.note = newProduct.note || null;
    } else {
      form.value.name = "";
      form.value.price = "";
      form.value.sale = "";
      form.value.category_id = "";
      form.value.status = "active";
      form.value.description = "";
      form.value.image = null;
      form.value.video = "";
      imagePreview.value = null;
      form.value.note = null;
    }
  },
  { immediate: true }
);

function handleImageUpload(event: Event) {
  const file = (event.target as HTMLInputElement).files?.[0];
  if (file) {
    form.value.image = file;

    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
}

function parseCurrencyToCents(value: string): number {
  // Remove tudo que não for dígito
  const numeric = value.replace(/\D/g, "");
  return Number(numeric);
}

function validate() {
  let isValid = true;
  if (!form.value.name.trim()) {
    errors.value.name = "O nome do produto é obrigatório.";
    isValid = false;
  } else {
    errors.value.name = "";
  }
  if (!form.value.price.trim()) {
    errors.value.price = "O preço do produto é obrigatório.";
    isValid = false;
  } else {
    errors.value.price = "";
  }
  
  if (!form.value.category_id) {
    errors.value.category_id = "A categoria é obrigatória.";
    isValid = false;
  } else {
    errors.value.category_id = "";
  }
  return isValid;
}

function submit() {
  if (!validate()) return;

  const formData = new FormData();
  formData.append("name", form.value.name);
  formData.append("price", parseCurrencyToCents(form.value.price).toString());
  formData.append(
    "sale",
    form.value.sale ? parseCurrencyToCents(form.value.sale).toString() : ""
  );
  formData.append("category_id", form.value.category_id);
  formData.append("status", form.value.status == 'active'? 1:0);
  formData.append("description", form.value.description);
  formData.append("video", form.value.video || "");
  formData.append("note", form.value.note || "");
  formData.append("image", form.value.image);

  if (props.isEditing && props.product) {
    router.post(`/products/${props.product.id}`, formData, {
      onSuccess: (response) => {
        emit("product-saved", response.data);
        emit("update:open", false);
      },
      onError: (serverErrors) => {
        console.error(serverErrors);
      },
    });
  } else {
    router.post("/products", formData, {
      onSuccess: (response) => {
        emit("product-saved", response.data);
        emit("update:open", false);
      },
      onError: (serverErrors) => {
        console.error(serverErrors);
      },
    });
  }
}
</script>

<template>
  <Dialog :open="open" @update:open="(value) => emit('update:open', value)">
    <DialogOverlay class="fixed inset-0 bg-black/50 z-40" />

    <DialogContent
      class="fixed z-50 top-1/2 left-1/2 w-full max-w-5xl -translate-x-1/2 -translate-y-1/2 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg sm:max-w-4xl"
    >
      <DialogTitle class="text-xl font-bold mb-4">
        {{ isEditing ? "Editar Produto" : "Novo Produto" }}
      </DialogTitle>

      <div class="grid grid-cols-3 gap-6">
        <!-- Formulário -->
        <form @submit.prevent="submit" class="col-span-2 grid grid-cols-2 gap-4">
          <!-- Nome -->
          <div class="col-span-2">
            <input
              v-model="form.name"
              type="text"
              placeholder="Nome do Produto"
              class="w-full rounded-md border px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
            />
            <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
          </div>

          <!-- Categoria -->
          <div class="col-span-2">
            <select
              v-model="form.category_id"
              class="w-full rounded-md border px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
            >
              <option value="" disabled>Selecione uma categoria</option>
              <option
                v-for="category in categories"
                :key="category.id"
                :value="category.id"
              >
                {{ category.name }}
              </option>
            </select>
            <p v-if="errors.category_id" class="text-red-500 text-sm mt-1">
              {{ errors.category_id }}
            </p>
          </div>

          <!-- Preço -->
          <div>
            <input
              v-model="form.price"
              type="text"
              placeholder="Preço"
              class="w-full rounded-md border px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
            />
            <p v-if="errors.price" class="text-red-500 text-sm mt-1">
              {{ errors.price }}
            </p>
          </div>

          <!-- Promoção -->
          <div>
            <input
              v-model="form.sale"
              type="text"
              placeholder="Promoção"
              class="w-full rounded-md border px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
            />
            <p v-if="errors.sale" class="text-red-500 text-sm mt-1">{{ errors.sale }}</p>
          </div>

          <!-- Descrição -->
          <div class="col-span-2">
            <textarea
              v-model="form.description"
              placeholder="Descrição do Produto"
              class="w-full rounded-md border px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
            ></textarea>
          </div>

          <!-- Imagem -->
          <div class="col-span-2">
            <input
              type="file"
              @change="handleImageUpload"
              class="w-full cursor-pointer rounded-md border px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
              accept="image/*"
            />
          </div>

          <!-- Video -->
          <div class="col-span-2">
            <input
              v-model="form.video"
              type="text"
              placeholder="Link do Vídeo"
              class="w-full rounded-md border px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
            />
            <p v-if="errors.video" class="text-red-500 text-sm mt-1">
              {{ errors.video }}
            </p>
          </div>

          <!-- Observação -->
          <div class="col-span-2">
            <textarea
              v-model="form.note"
              placeholder="Observação do Produto"
              class="w-full rounded-md border px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
            ></textarea>
          </div>

          <!-- Status -->
          <div class="col-span-2">
            <select
              v-model="form.status"
              class="w-full rounded-md border px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
            >
              <option value="active" selected>Ativo</option>
              <option value="inactive">Desativado</option>
            </select>
            <p v-if="errors.status" class="text-red-500 text-sm mt-1">
              {{ errors.status }}
            </p>
          </div>

          <!-- Botão de Salvar -->
          <div class="col-span-2 text-right">
            <button
              type="submit"
              class="rounded-md bg-primary px-4 py-2 text-white hover:bg-primary-dark transition"
            >
              {{ isEditing ? "Salvar Alterações" : "Salvar" }}
            </button>
          </div>
        </form>

        <!-- Pré-visualização da Imagem -->
        <div class="flex items-center justify-center border rounded-lg p-4">
          <div v-if="imagePreview" class="text-center">
            <img
              :src="imagePreview"
              alt="Pré-visualização da imagem"
              class="max-w-full max-h-96 object-contain"
            />
            <p class="text-sm text-gray-500 mt-2">Pré-visualização da imagem</p>
          </div>
          <div v-else class="text-center text-gray-500">
            <p>Nenhuma imagem selecionada</p>
          </div>
        </div>
      </div>
    </DialogContent>
  </Dialog>
</template>
