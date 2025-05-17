<script setup lang="ts">
import { ref, defineProps } from "vue";
import { Upload, Save } from "lucide-vue-next";
import { router } from "@inertiajs/vue3";
import { Toaster } from "@/components/ui/sonner";
import { toast } from "vue-sonner";

const props = defineProps<{
  tenant: Object;
}>();

// Dados da loja
const storeData = ref({
  name: props.tenant.name,
  document: props.tenant.document,
  domain: props.tenant.domain,
  whatsapp: props.tenant.whatsapp,
  logo: null as File | null,
  cover: null as File | null,
  status: props.tenant.status == 1 ? true : false,
  custom_button: props.tenant.custom_button,
  custom_button_text: props.tenant.custom_button_text,
  custom_title_color: props.tenant.custom_title_color,
  current_logo: `/storage/${props.tenant.logo}`,
  current_cover: `/storage/${props.tenant.cover}`,
});

// Referências para os inputs de arquivo
const loading = ref(false);
const error = ref("");
const success = ref("");

const logoInput = ref<HTMLInputElement | null>(null);
const coverInput = ref<HTMLInputElement | null>(null);

// Manipuladores de arquivos
const handleLogoUpload = (event: Event) => {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files[0]) {
    storeData.value.logo = input.files[0];
  }
};

const handleCoverUpload = (event: Event) => {
  const input = event.target as HTMLInputElement;
  if (input.files && input.files[0]) {
    storeData.value.cover = input.files[0];
  }
};

// Enviar dados
const submitForm = () => {
  loading.value = true;
  error.value = "";
  success.value = "";

  router.post(
    "/settings/appearance",
    {
      name: storeData.value.name,
      document: storeData.value.document,
      domain: storeData.value.domain,
      whatsapp: storeData.value.whatsapp,
      logo: storeData.value.logo,
      cover: storeData.value.cover,
      status: storeData.value.status,
      custom_button: storeData.value.custom_button,
      custom_button_text: storeData.value.custom_button_text,
      custom_title_color: storeData.value.custom_title_color,
    },
    {
      preserveScroll: true,
      onSuccess: () => {
        toast.success("Configurações salvas com sucesso!");
        window.location.reload();
      },
      onError: (errors) => {
        toast.error("Ocorreu um erro ao salvar as configurações");
      },
      onFinish: () => {
        loading.value = false;
      },
    }
  );
};
</script>

<template>
  <Toaster />
  <div class="max-w-full mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Configurações da Loja</h1>

    <!-- Mensagens de status -->
    <div
      v-if="error"
      class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4"
    >
      {{ error }}
    </div>

    <div
      v-if="success"
      class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"
    >
      {{ success }}
    </div>

    <form @submit.prevent="submitForm" class="space-y-6">
      <!-- Seção de Informações Básicas -->
      <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-medium mb-4">Informações Básicas</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="name" class="block text-sm font-medium mb-1">Nome da Loja</label>
            <input
              id="name"
              v-model="storeData.name"
              type="text"
              class="w-full px-3 py-2 border rounded-md dark:bg-neutral-700 dark:border-neutral-600"
              required
            />
          </div>

          <div>
            <label for="document" class="block text-sm font-medium mb-1">CNPJ/CPF</label>
            <input
              id="document"
              v-model="storeData.document"
              type="text"
              class="w-full px-3 py-2 border rounded-md dark:bg-neutral-700 dark:border-neutral-600"
              required
            />
          </div>

          <div>
            <label for="domain" class="block text-sm font-medium mb-1">Domínio</label>
            <input
              readonly
              id="domain"
              v-model="storeData.domain"
              type="text"
              class="w-full px-3 py-2 border rounded-md dark:bg-neutral-700 dark:border-neutral-600"
              required
            />
          </div>

          <div>
            <label for="whatsapp" class="block text-sm font-medium mb-1">WhatsApp</label>
            <input
              id="whatsapp"
              v-model="storeData.whatsapp"
              type="text"
              class="w-full px-3 py-2 border rounded-md dark:bg-neutral-700 dark:border-neutral-600"
              required
            />
          </div>
        </div>
      </div>

      <!-- Seção de Imagens -->
      <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-medium mb-4">Imagens</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Logo -->
          <div>
            <label class="block text-sm font-medium mb-2">Logo</label>
            <div class="flex flex-col gap-4">
              <div
                class="w-20 h-20 rounded-md overflow-hidden bg-neutral-100 dark:bg-neutral-700"
              >
                <img
                  v-if="storeData.current_logo"
                  :src="storeData.current_logo"
                  alt="Logo atual"
                  class="w-full h-full object-contain"
                />
                <div
                  v-else
                  class="w-full h-full flex items-center justify-center text-neutral-400"
                >
                  Sem logo
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Selecionar novo logo</label>
                <input
                  type="file"
                  class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100"
                  accept="image/*"
                  @change="handleLogoUpload"
                  ref="logoInput"
                />
                <p v-if="storeData.logo" class="mt-1 text-sm text-gray-500">
                  Novo arquivo selecionado: {{ storeData.logo.name }}
                </p>
              </div>
            </div>
          </div>

          <!-- Capa -->
          <div>
            <label class="block text-sm font-medium mb-2">Capa</label>
            <div class="flex flex-col gap-4">
              <div
                class="w-20 h-20 rounded-md overflow-hidden bg-neutral-100 dark:bg-neutral-700"
              >
                <img
                  v-if="storeData.current_cover"
                  :src="storeData.current_cover"
                  alt="Capa atual"
                  class="w-full h-full object-cover"
                />
                <div
                  v-else
                  class="w-full h-full flex items-center justify-center text-neutral-400"
                >
                  Sem capa
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium mb-1">Selecionar nova capa</label>
                <input
                  type="file"
                  class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100"
                  accept="image/*"
                  @change="handleCoverUpload"
                  ref="coverInput"
                />
                <p v-if="storeData.cover" class="mt-1 text-sm text-gray-500">
                  Novo arquivo selecionado: {{ storeData.cover.name }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Seção de Personalização -->
      <div class="bg-white dark:bg-neutral-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-medium mb-4">Personalização</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label for="custom_button" class="block text-sm font-medium mb-1"
              >Cor dos Botões</label
            >
            <div class="flex items-center gap-2">
              <input
                id="custom_title_color"
                v-model="storeData.custom_button"
                type="color"
                class="h-10 w-10 cursor-pointer rounded border"
              />
              <input
                v-model="storeData.custom_button"
                type="text"
                class="flex-1 px-3 py-2 border rounded-md dark:bg-neutral-700 dark:border-neutral-600"
              />
            </div>
          </div>

          <div>
            <label for="custom_button_text" class="block text-sm font-medium mb-1"
              >Cor do Texto do Botão</label
            >
            <div class="flex items-center gap-2">
              <input
                id="custom_title_color"
                v-model="storeData.custom_button_text"
                type="color"
                class="h-10 w-10 cursor-pointer rounded border"
              />
              <input
                v-model="storeData.custom_button_text"
                type="text"
                class="flex-1 px-3 py-2 border rounded-md dark:bg-neutral-700 dark:border-neutral-600"
              />
            </div>
          </div>

          <div>
            <label for="custom_title_color" class="block text-sm font-medium mb-1"
              >Cor dos Títulos</label
            >
            <div class="flex items-center gap-2">
              <input
                id="custom_title_color"
                v-model="storeData.custom_title_color"
                type="color"
                class="h-10 w-10 cursor-pointer rounded border"
              />
              <input
                v-model="storeData.custom_title_color"
                type="text"
                class="flex-1 px-3 py-2 border rounded-md dark:bg-neutral-700 dark:border-neutral-600"
              />
            </div>
          </div>
        </div>
      </div>

      <!-- Botão de submit -->
      <div class="flex justify-end">
        <button
          type="submit"
          :disabled="loading"
          class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <Save class="h-4 w-4 mr-2" />
          <span>{{ loading ? "Salvando..." : "Salvar Configurações" }}</span>
        </button>
      </div>
    </form>
  </div>
</template>