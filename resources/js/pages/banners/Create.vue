<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import CreateBannerModal from "@/components/banners/CreateBannerModal.vue";
import { ref } from "vue";

const showModal = ref(true);

function handleBannerCreated(banners: any) {
  // Redireciona para a listagem após criar
  window.location.href = '/banners';
}

function handleClose() {
  // Redireciona para a listagem se cancelar
  window.location.href = '/banners';
}
</script>

<template>
  <Head title="Criar Banner" />

  <AppLayout :breadcrumbs="[
    { title: 'Banners', href: '/banners' },
    { title: 'Criar Banner' }
  ]">
    <div class="space-y-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Criar Banner</h1>
        <p class="mt-1 text-sm text-gray-600">
          Preencha as informações abaixo para criar um novo banner
        </p>
      </div>

      <CreateBannerModal
        :show="showModal"
        :is-editing="false"
        :banner="null"
        @created="handleBannerCreated"
        @close="handleClose"
      />
    </div>
  </AppLayout>
</template>