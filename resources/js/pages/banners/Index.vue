<script setup lang="ts">
import { ref } from "vue";
import AppLayout from "@/layouts/AppLayout.vue";
import { Head } from "@inertiajs/vue3";
import ListBanners from "@/components/banners/ListBanners.vue";
import CreateBannerModal from "@/components/banners/CreateBannerModal.vue";
import ConfirmDialog from "@/components/ConfirmDialog.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps<{
  banners: {
    data: Array<{
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
      created_at: string;
    }>;
    current_page: number;
    last_page: number;
  };
}>();

const banners = ref(props.banners.data || []);
const showModal = ref(false);
const isEditing = ref(false);
const editingBanner = ref(null);
const bannerToDelete = ref(null);

function handleBannerCreated(updatedBanners) {
  banners.value = updatedBanners;
  showModal.value = false;
  isEditing.value = false;
  editingBanner.value = null;
}

function updateBanner(banner) {
  editingBanner.value = banner;
  isEditing.value = true;
  showModal.value = true;
}

function deleteBanner(bannerId) {
  bannerToDelete.value = bannerId;
}

function confirmDelete() {
  if (bannerToDelete.value !== null) {
    router.delete(`/banners/${bannerToDelete.value}`, {
      onSuccess: () => {
        banners.value = banners.value.filter((banner) => banner.id !== bannerToDelete.value);
        bannerToDelete.value = null;
      },
      onError: (errors) => {
        console.error(errors);
      },
    });
  }
}

function cancelDelete() {
  bannerToDelete.value = null;
}
</script>

<template>
  <Head title="Banners" />

  <AppLayout :breadcrumbs="[{ name: 'Banners', href: '/banners' }]">
    <div class="space-y-6">
      <div class="flex items-center justify-between m-6">

        <div>
          <h1 class="text-2xl font-bold text-gray-900">Banners</h1>
          <p class="mt-1 text-sm text-gray-600">
            Gerencie os banners do seu site
          </p>
        </div>
        <button
          @click="showModal = true"
          class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
          <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Novo Banner
        </button>
      </div>

      <div
      v-if="banners.length === 0"
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
      <h2 class="text-xl font-semibold mb-2">Nenhum banner cadastrado</h2>
      <p class="mb-4 max-w-md text-sm">
        Você ainda não possui nenhum banner. Comece agora cadastrando o primeiro!
      </p>
    </div>

    <div v-else class="mx-6 my-4">
      <ListBanners
        :banners="banners"
        @update="updateBanner"
        @delete="deleteBanner"
      />
    </div>    

      <CreateBannerModal
        v-if="showModal"
        :show="showModal"
        :is-editing="isEditing"
        :banner="editingBanner"
        @close="showModal = false"
        @created="handleBannerCreated"
      />

      <ConfirmDialog
        :show="bannerToDelete !== null"
        title="Excluir Banner"
        message="Tem certeza que deseja excluir este banner? Esta ação não pode ser desfeita."
        @confirm="confirmDelete"
        @cancel="cancelDelete"
      />
    </div>
  </AppLayout>
</template>