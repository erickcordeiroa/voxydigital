<script lang="ts" setup>
import { ref, defineProps, onMounted, computed } from "vue";
import AppLayout from "@/layouts/AppLayout.vue";
import type { Category, Product } from "@/types/cart";
import { Button } from "@/components/ui/button";
import { router } from "@inertiajs/vue3";
import { Toaster } from "@/components/ui/sonner";
import { toast } from "vue-sonner";

const props = defineProps<{
  product: Product;
  categories: Category[];
}>();

// Simule categorias para exemplo
const categories = ref(props.categories);
const product = ref(props.product);

const errors = ref({
  name: "",
  price: "",
  sale: "",
  category_id: "",
  status: "",
  description: "",
});

onMounted(() => {
  if (!Array.isArray(product.value.images)) product.value.images = [];
  if (!Array.isArray(product.value.variations)) product.value.variations = [];

  imagePreviews.value = [];
  product.value.images.forEach((img) => {
    if (typeof img === "object" && "uri" in img) {
      imagePreviews.value.push(img.uri);
    } else if (typeof img === "string") {
      imagePreviews.value.push(img);
    }
  });

  // Formatar preço e promoção ao carregar
  if (product.value.price && typeof product.value.price === "number") {
    product.value.price = formatToCurrency(product.value.price.toString());
  } else if (
    product.value.price &&
    typeof product.value.price === "string" &&
    !product.value.price.includes("R$")
  ) {
    product.value.price = formatToCurrency(product.value.price);
  }
  if (product.value.sale && typeof product.value.sale === "number") {
    product.value.sale = formatToCurrency(product.value.sale.toString());
  } else if (
    product.value.sale &&
    typeof product.value.sale === "string" &&
    !product.value.sale.includes("R$")
  ) {
    product.value.sale = formatToCurrency(product.value.sale);
  }
});

const statusString = computed({
  get() {
    return product.value.status === true ? "active" : "inactive";
  },
  set(val: string) {
    product.value.status = val === "active" ? true : false;
  },
});

function parseCurrencyToCents(value: string): number {
  const numeric = value.replace(/\D/g, "");
  return Number(numeric);
}
const imagePreviews = ref<(string | { uri: string })[]>([]);
const fileInputRef = ref<HTMLInputElement | null>(null);

function handleImageUpload(event: Event) {
  const files = (event.target as HTMLInputElement).files;
  if (files) {
    const fileArr = Array.from(files);
    product.value.images.push(...fileArr);
    fileArr.forEach((file) => {
      const reader = new FileReader();
      reader.onload = (e) => {
        imagePreviews.value.push(e.target?.result as string);
      };
      reader.readAsDataURL(file);
    });
  }
}

function removeImage(idx: number) {
  product.value.images.splice(idx, 1);
  imagePreviews.value.splice(idx, 1);

  if (fileInputRef.value) {
    const dt = new DataTransfer();
    product.value.images.forEach((file) => {
      if (file instanceof File) dt.items.add(file);
    });
    fileInputRef.value.files = dt.files;
  }
}

// Máscara de moeda
function formatToCurrency(value: string): string {
  const numericValue = value.replace(/\D/g, "");
  const formattedValue = (Number(numericValue) / 100).toLocaleString("pt-BR", {
    style: "currency",
    currency: "BRL",
  });
  return formattedValue;
}

function onPriceInput(e: Event) {
  const target = e.target as HTMLInputElement;
  product.value.price = formatToCurrency(target.value);
}

function onSaleInput(e: Event) {
  const target = e.target as HTMLInputElement;
  product.value.sale = formatToCurrency(target.value);
}

// Variações
const showVariationModal = ref(false);
const editingVariation = ref<number | null>(null);
const variationForm = ref({
  sku: "",
  reference: "",
  size: "",
});

function openVariationModal(variation: any = null, idx: number | null = null) {
  if (variation) {
    variationForm.value = { ...variation };
    editingVariation.value = idx;
  } else {
    variationForm.value = { sku: "", reference: "", size: "" };
    editingVariation.value = null;
  }
  showVariationModal.value = true;
}

function saveVariation() {
  const exists = product.value.variations.some(
    (v) => v.sku.trim().toLowerCase() === variationForm.value.sku.trim().toLowerCase()
  );
  if (exists) {
    toast.error("Já existe uma variação com este SKU para este produto.");
    return;
  }

  const formData = new FormData();
  formData.append("product_id", String(product.value.id));
  formData.append("sku", variationForm.value.sku);
  formData.append("reference", variationForm.value.reference);
  formData.append("size", variationForm.value.size);

  router.post(route("variations.store", product.value.id), formData, {
    forceFormData: true,
    onSuccess: () => {
      // Adiciona uma cópia dos dados do formulário
      product.value.variations.push({ ...variationForm.value });
      toast.success("Variação criada com sucesso!");
      showVariationModal.value = false;
      // Limpa o formulário para o próximo uso
      variationForm.value = { sku: "", reference: "", size: "" };
      editingVariation.value = null;
    },
    onError: (error) => {
      console.log(error);
      toast.error(error?.error || "Erro ao criar variação.");
    },
  });
}

function updateVariation(id: number) {
  const exists = product.value.variations.some((v, idx) => {
    if (editingVariation.value !== null && idx === editingVariation.value) return false;
    return v.sku.trim().toLowerCase() === variationForm.value.sku.trim().toLowerCase();
  });
  
  if (exists) {
    toast.error("Já existe uma variação com este SKU para este produto.");
    return;
  }

  router.put(
    route("variations.update", product.value.id),
    {
      product_id: product.value.id,
      sku: variationForm.value.sku,
      reference: variationForm.value.reference,
      size: variationForm.value.size,
      id: id,
    },
    {
      onSuccess: () => {
        if (editingVariation.value !== null) {
          product.value.variations[editingVariation.value] = {
            ...variationForm.value,
            id,
          };
        }
        toast.success("Variação atualizada com sucesso!");
        showVariationModal.value = false;
        // Limpa o formulário para o próximo uso
        variationForm.value = { sku: "", reference: "", size: "" };
        editingVariation.value = null;
      },
      onError: (error) => {
        console.log(error);
        toast.error(error?.error || "Erro ao atualizar variação.");
      },
    }
  );
}

function removeVariation(idx: number) {
  const variation = product.value.variations[idx];

  try {
    router.delete(route("variations.delete", variation.id), {
      onSuccess: () => {
        product.value.variations.splice(idx, 1);
        toast.success("Variação removida com sucesso!");
      },
      onError: (error) => {
        toast.error(error.response?.data?.error || "Erro ao remover variação.");
      },
    });
  } catch (error) {
    toast.error("Erro ao remover variação.");
  }
}

// Validação simples
function validate() {
  let isValid = true;
  if (!product.value.name.trim()) {
    errors.value.name = "O nome do produto é obrigatório.";
    isValid = false;
  } else {
    errors.value.name = "";
  }
  if (!product.value.price.trim()) {
    errors.value.price = "O preço do produto é obrigatório.";
    isValid = false;
  } else {
    errors.value.price = "";
  }
  if (!product.value.category_id) {
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
  formData.append("name", product.value.name);
  formData.append("price", parseCurrencyToCents(product.value.price).toString());
  formData.append(
    "sale",
    product.value.sale ? parseCurrencyToCents(product.value.sale).toString() : ""
  );
  formData.append("category_id", product.value.category_id);
  formData.append("status", product.value.status);
  formData.append("description", product.value.description);
  formData.append("note", product.value.note);

  // Imagens: envia arquivos novos, e URLs das antigas
  product.value.images.forEach((file, idx) => {
    if (file instanceof File) {
      formData.append(`images[${idx}]`, file);
    } else if (typeof file === "object") {
      formData.append(`existing_images[${idx}]`, file.uri);
    }
  });

  // Variações
  formData.append("variations", JSON.stringify(product.value.variations));

  try {
    router.post(`/products/${product.value.id}`, formData, {
      forceFormData: true,
      onSuccess: () => {
        router.visit("/products");
      },
    });
  } catch (error) {
    console.error("Erro ao enviar o formulário:", error);
  }
}
</script>

<template>
  <AppLayout :breadcrumbs="[{ title: 'Produtos', href: '/products' }]">
    <Toaster />
    <div class="p-6 bg-white rounded">
      <h1 class="text-2xl font-bold mb-4">Cadastro de Produto</h1>
      <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
          <div>
            <label class="block font-semibold mb-1">Nome</label>
            <input
              v-model="product.name"
              class="w-full border rounded px-3 py-2"
              required
            />
            <p v-if="errors.name" class="text-red-500 text-sm mt-1">{{ errors.name }}</p>
          </div>
          <div>
            <label class="block font-semibold mb-1">Categoria</label>
            <select
              v-model="product.category_id"
              class="w-full border rounded px-3 py-2"
              required
            >
              <option value="" disabled>Selecione uma categoria</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.name }}
              </option>
            </select>
            <p v-if="errors.category_id" class="text-red-500 text-sm mt-1">
              {{ errors.category_id }}
            </p>
          </div>
          <div class="flex gap-2">
            <div class="flex-1">
              <label class="block font-semibold mb-1">Preço</label>
              <input
                v-model="product.price"
                type="text"
                class="w-full border rounded px-3 py-2"
                required
                @input="onPriceInput"
              />
              <p v-if="errors.price" class="text-red-500 text-sm mt-1">
                {{ errors.price }}
              </p>
            </div>
            <div class="flex-1">
              <label class="block font-semibold mb-1">Promoção</label>
              <input
                v-model="product.sale"
                type="text"
                class="w-full border rounded px-3 py-2"
                @input="onSaleInput"
              />
            </div>
          </div>
          <div>
            <label class="block font-semibold mb-1">Descrição</label>
            <textarea
              v-model="product.description"
              class="w-full border rounded px-3 py-2"
              rows="3"
            />
          </div>
          <div>
            <label class="block font-semibold mb-1">Observação</label>
            <textarea
              v-model="product.note"
              class="w-full border rounded px-3 py-2"
              rows="2"
            />
          </div>
          <div>
            <label class="block font-semibold mb-1">Status</label>
            <select v-model="statusString" class="w-full border rounded px-3 py-2">
              <option value="active">Ativo</option>
              <option value="inactive">Desativado</option>
            </select>
          </div>
        </div>
        <!-- Imagens -->
        <div>
          <label class="block font-semibold mb-1">Imagens</label>
          <input
            type="file"
            multiple
            accept="image/*"
            @change="handleImageUpload"
            ref="fileInputRef"
          />

          <div v-if="imagePreviews.length" class="mt-4">
            <div
              class="flex overflow-x-auto gap-4 w-full h-56 px-2 md:grid md:grid-cols-3 md:gap-4 md:overflow-x-visible md:h-auto"
            >
              <div
                v-for="(img, idx) in imagePreviews"
                :key="idx"
                class="relative flex-shrink-0 w-48 h-48 flex items-center justify-center md:w-full md:h-48"
              >
                <img
                  :src="typeof img === 'string' ? img : img.uri"
                  class="object-contain w-full h-full rounded border"
                />
                <button
                  type="button"
                  class="absolute top-1 right-1 bg-red-500 text-white rounded-full px-2.5 py-0.25 text-lg"
                  @click="removeImage(idx)"
                  title="Remover"
                >
                  ×
                </button>
              </div>
            </div>
          </div>
          <div v-else class="text-gray-400 mt-4 text-center">
            Nenhuma imagem selecionada
          </div>
        </div>
        <div class="md:col-span-2">
          <div class="flex justify-between items-center mb-2 mt-6">
            <label class="block font-semibold">Variações</label>
            <Button
              type="button"
              @click="openVariationModal()"
              class="rounded-lg bg-primary px-4 py-2 text-white hover:bg-primary-dark transition cursor-pointer"
            >
              + Adicionar Variação
            </Button>
          </div>
          <div
            class="border rounded overflow-x-auto"
            style="max-height: 220px; overflow-y: auto"
          >
            <table class="min-w-full text-sm">
              <thead class="bg-gray-100">
                <tr>
                  <th class="px-3 py-2 text-left">SKU</th>
                  <th class="px-3 py-2 text-left">Referencia</th>
                  <th class="px-3 py-2 text-left">Tamanho</th>
                  <th class="px-3 py-2"></th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(variation, idx) in product.variations"
                  :key="idx"
                  class="border-t"
                >
                  <td class="px-3 py-2">{{ variation.sku }}</td>
                  <td class="px-3 py-2">{{ variation.reference }}</td>
                  <td class="px-3 py-2">{{ variation.size }}</td>
                  <td class="px-3 py-2 flex gap-2">
                    <button
                      type="button"
                      class="text-blue-600"
                      @click="openVariationModal(variation, idx)"
                    >
                      Editar
                    </button>
                    <button
                      type="button"
                      class="text-red-600"
                      @click="removeVariation(idx)"
                    >
                      Remover
                    </button>
                  </td>
                </tr>
                <tr v-if="!product.variations.length">
                  <td colspan="4" class="px-3 py-2 text-center text-gray-400">
                    Nenhuma variação cadastrada.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="md:col-span-2 mt-6 text-right">
          <Button variant="default"> Salvar Produto </Button>
        </div>
      </form>
    </div>

    <!-- Modal de Variação -->
    <div
      v-if="showVariationModal"
      class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
    >
      <div class="bg-white rounded shadow-lg p-6 w-full max-w-md">
        <h2 class="text-lg font-bold mb-4">
          {{ editingVariation !== null ? "Editar" : "Adicionar" }} Variação
        </h2>
        <form
          @submit.prevent="
            editingVariation !== null
              ? updateVariation(product.variations[editingVariation].id)
              : saveVariation()
          "
        >
          <div class="mb-3">
            <label class="block mb-1">SKU</label>
            <input
              v-model="variationForm.sku"
              type="text"
              class="w-full border rounded px-3 py-2"
              required
            />
          </div>
          <div class="mb-3">
            <label class="block mb-1">Referencia</label>
            <input
              v-model="variationForm.reference"
              type="text"
              min="0"
              step="0.01"
              class="w-full border rounded px-3 py-2"
              required
            />
          </div>
          <div class="mb-3">
            <label class="block mb-1">Tamanho</label>
            <input
              v-model="variationForm.size"
              type="text"
              min="0"
              class="w-full border rounded px-3 py-2"
              required
            />
          </div>
          <div class="flex justify-end gap-2 mt-4">
            <button
              type="button"
              class="rounded-lg bg-gray-100 px-4 py-2 text-black hover:bg-primary-dark transition cursor-pointer"
              @click="showVariationModal = false"
            >
              Cancelar
            </button>
            <button
              type="submit"
              class="rounded-lg bg-primary px-4 py-2 text-white hover:bg-primary-dark transition cursor-pointer"
            >
              Salvar
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
