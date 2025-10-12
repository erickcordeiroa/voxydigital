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
import { Upload, X } from "lucide-vue-next";

const props = defineProps<{
  show: boolean;
  banner?: {
    id: number;
    title: string;
    description?: string;
    image: string;
    link_url?: string;
    link_text?: string;
    sort_order: number;
    is_active: boolean;
    starts_at?: string;
    ends_at?: string;
  } | null;
  isEditing?: boolean;
}>();

const emit = defineEmits<{
  close: [];
  created: [banners: any];
}>();

const form = ref({
  title: "",
  description: "",
  image: null as File | null,
  link_url: "",
  link_text: "",
  sort_order: 0,
  is_active: true,
  starts_at: "",
  ends_at: "",
});

const imagePreview = ref<string | null>(null);
const fileInput = ref<HTMLInputElement | null>(null);

const errors = ref({
  title: "",
  image: "",
  link_url: "",
  ends_at: "",
});

function resetForm() {
  form.value = {
    title: "",
    description: "",
    image: null,
    link_url: "",
    link_text: "",
    sort_order: 0,
    is_active: true,
    starts_at: "",
    ends_at: "",
  };
  imagePreview.value = null;
  errors.value = {
    title: "",
    image: "",
    link_url: "",
    ends_at: "",
  };
}

// Preenche o formulário ao editar
watch(
  () => props.banner,
  (newBanner) => {
    if (newBanner) {
      form.value.title = newBanner.title;
      form.value.description = newBanner.description || "";
      form.value.link_url = newBanner.link_url || "";
      form.value.link_text = newBanner.link_text || "";
      form.value.sort_order = newBanner.sort_order;
      form.value.is_active = newBanner.is_active;
      form.value.starts_at = newBanner.starts_at ? newBanner.starts_at.split('T')[0] : "";
      form.value.ends_at = newBanner.ends_at ? newBanner.ends_at.split('T')[0] : "";
      imagePreview.value = newBanner.image ? `/storage/${newBanner.image}` : null;
    } else {
      resetForm();
    }
  },
  { immediate: true }
);

function validate() {
  let isValid = true;
  
  if (!form.value.title.trim()) {
    errors.value.title = "O título é obrigatório.";
    isValid = false;
  } else {
    errors.value.title = "";
  }
  
  if (!props.isEditing && !form.value.image) {
    errors.value.image = "A imagem é obrigatória.";
    isValid = false;
  } else {
    errors.value.image = "";
  }
  
  if (form.value.link_url && !isValidUrl(form.value.link_url)) {
    errors.value.link_url = "URL inválida.";
    isValid = false;
  } else {
    errors.value.link_url = "";
  }
  
  if (form.value.starts_at && form.value.ends_at && form.value.ends_at <= form.value.starts_at) {
    errors.value.ends_at = "A data de fim deve ser posterior à data de início.";
    isValid = false;
  } else {
    errors.value.ends_at = "";
  }
  
  return isValid;
}

function isValidUrl(string: string) {
  try {
    new URL(string);
    return true;
  } catch (_) {
    return false;
  }
}

function handleImageChange(event: Event) {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  
  if (file) {
    form.value.image = file;
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target?.result as string;
    };
    reader.readAsDataURL(file);
  }
}

function removeImage() {
  form.value.image = null;
  imagePreview.value = null;
  if (fileInput.value) {
    fileInput.value.value = '';
  }
}

function triggerFileInput() {
  fileInput.value?.click();
}

function submit() {
  if (!validate()) return;

  const formData = new FormData();
  formData.append('title', form.value.title);
  formData.append('description', form.value.description);
  formData.append('link_url', form.value.link_url);
  formData.append('link_text', form.value.link_text);
  formData.append('sort_order', form.value.sort_order.toString());
  formData.append('is_active', form.value.is_active ? '1' : '0');
  formData.append('starts_at', form.value.starts_at);
  formData.append('ends_at', form.value.ends_at);
  
  if (form.value.image) {
    formData.append('image', form.value.image);
  }

  if (props.isEditing && props.banner) {
    formData.append('_method', 'PUT');
    router.post(`/banners/${props.banner.id}`, formData, {
      onSuccess: (response) => {
        toast.success('Banner atualizado com sucesso!');
        resetForm();
        emit("created", response.props.banners.data);
        emit("close");
      },
      onError: (serverErrors) => {
        toast.error('Ocorreu um erro ao atualizar o banner');
        console.error(serverErrors);
      },
    });
  } else {
    router.post("/banners", formData, {
      onSuccess: (response) => {
        toast.success('Banner criado com sucesso!');
        resetForm();
        emit("created", response.props.banners.data);
        emit("close");
      },
      onError: (serverErrors) => {
        toast.error('Ocorreu um erro ao criar o banner');
        console.error(serverErrors);
      },
    });
  }
}

function close() {
  resetForm();
  emit("close");
}
</script>

<template>
  <Toaster />
  <Dialog :open="show" @update:open="(value) => !value && close()">
    <DialogOverlay class="fixed inset-0 bg-black/50 z-40" />

    <DialogContent
      class="fixed z-50 top-1/2 left-1/2 w-full max-w-2xl max-h-[90vh] overflow-y-auto -translate-x-1/2 -translate-y-1/2 rounded-xl bg-white dark:bg-gray-900 p-6 shadow-lg"
    >
      <DialogTitle class="text-xl font-bold mb-4">
        {{ isEditing ? "Editar Banner" : "Novo Banner" }}
      </DialogTitle>

      <form class="space-y-6" @submit.prevent="submit">
        <!-- Título -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Título *
          </label>
          <input
            v-model="form.title"
            type="text"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="Digite o título do banner"
          />
          <p v-if="errors.title" class="mt-1 text-sm text-red-600">{{ errors.title }}</p>
        </div>

        <!-- Descrição -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Descrição
          </label>
          <textarea
            v-model="form.description"
            rows="3"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="Digite uma descrição (opcional)"
          ></textarea>
        </div>

        <!-- Upload de Imagem -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">
            Imagem {{ !isEditing ? '*' : '' }}
          </label>
          
          <div class="space-y-4">
            <!-- Preview da imagem -->
            <div v-if="imagePreview" class="relative inline-block">
              <img 
                :src="imagePreview" 
                alt="Preview" 
                class="w-32 h-20 object-cover rounded-lg border"
              />
              <button
                type="button"
                @click="removeImage"
                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600"
              >
                <X class="h-4 w-4" />
              </button>
            </div>
            
            <!-- Botão de upload -->
            <div
              @click="triggerFileInput"
              class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-indigo-400 transition-colors"
            >
              <Upload class="mx-auto h-12 w-12 text-gray-400" />
              <p class="mt-2 text-sm text-gray-600">
                Clique para selecionar uma imagem
              </p>
              <p class="text-xs text-gray-500">PNG, JPG até 2MB</p>
            </div>
            
            <input
              ref="fileInput"
              type="file"
              accept="image/*"
              class="hidden"
              @change="handleImageChange"
            />
          </div>
          
          <p v-if="errors.image" class="mt-1 text-sm text-red-600">{{ errors.image }}</p>
        </div>

        <!-- Link URL e Texto -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              URL do Link
            </label>
            <input
              v-model="form.link_url"
              type="url"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
              placeholder="https://exemplo.com"
            />
            <p v-if="errors.link_url" class="mt-1 text-sm text-red-600">{{ errors.link_url }}</p>
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Texto do Link
            </label>
            <input
              v-model="form.link_text"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
              placeholder="Saiba mais"
            />
          </div>
        </div>

        <!-- Ordem e Status -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Ordem de Exibição
            </label>
            <input
              v-model.number="form.sort_order"
              type="number"
              min="0"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
          </div>
          
          <div class="flex items-end">
            <label class="flex items-center">
              <input
                v-model="form.is_active"
                type="checkbox"
                class="mr-2 h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <span class="text-sm font-medium text-gray-700">Banner Ativo</span>
            </label>
          </div>
        </div>

        <!-- Período de Exibição -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Data de Início
            </label>
            <input
              v-model="form.starts_at"
              type="date"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
          </div>
          
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Data de Fim
            </label>
            <input
              v-model="form.ends_at"
              type="date"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
            />
            <p v-if="errors.ends_at" class="mt-1 text-sm text-red-600">{{ errors.ends_at }}</p>
          </div>
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-3 pt-4 border-t">
          <button
            type="button"
            @click="close"
            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Cancelar
          </button>
          <button
            type="submit"
            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            {{ isEditing ? "Atualizar" : "Criar" }} Banner
          </button>
        </div>
      </form>
    </DialogContent>
  </Dialog>
</template>