<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import CreateBannerModal from "@/components/banners/CreateBannerModal.vue";
import { ref } from "vue";

const props = defineProps<{
  banner: {
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
  };
}>();

const showModal = ref(true);

function handleBannerUpdated(banners: any) {
  // Redireciona para a listagem após atualizar
  window.location.href = '/banners';
}

function handleClose() {
  // Redireciona para a listagem se cancelar
  window.location.href = '/banners';
}
</script>

<template>
  <Head title="Editar Banner" />

  <AppLayout :breadcrumbs="[
    { title: 'Banners', href: '/banners' },
    { title: 'Editar Banner' }
  ]">
    <div class="space-y-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Editar Banner</h1>
        <p class="mt-1 text-sm text-gray-600">
          Altere as informações do banner "{{ banner.title }}"
        </p>
      </div>

      <CreateBannerModal
        :show="showModal"
        :is-editing="true"
        :banner="banner"
        @created="handleBannerUpdated"
        @close="handleClose"
      />
    </div>
  </AppLayout>
</template>