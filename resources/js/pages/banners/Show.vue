<script setup lang="ts">
import { Head, router } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import { Edit, Trash2, ExternalLink, Calendar, Image } from "lucide-vue-next";
import ConfirmDialog from "@/components/ConfirmDialog.vue";
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
    created_at: string;
    updated_at: string;
  };
}>();

const showDeleteDialog = ref(false);

function formatDate(dateString?: string): string {
  if (!dateString) return 'Não definido';
  return new Date(dateString).toLocaleDateString('pt-BR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
}

function getStatusBadge(banner: any): { text: string; class: string } {
  if (!banner.is_active) {
    return { text: 'Inativo', class: 'bg-gray-100 text-gray-800' };
  }
  
  const now = new Date();
  const startsAt = banner.starts_at ? new Date(banner.starts_at) : null;
  const endsAt = banner.ends_at ? new Date(banner.ends_at) : null;
  
  if (startsAt && now < startsAt) {
    return { text: 'Agendado', class: 'bg-yellow-100 text-yellow-800' };
  }
  
  if (endsAt && now > endsAt) {
    return { text: 'Expirado', class: 'bg-red-100 text-red-800' };
  }
  
  return { text: 'Ativo', class: 'bg-green-100 text-green-800' };
}

function editBanner() {
  router.visit(`/banners/${props.banner.id}/edit`);
}

function confirmDelete() {
  router.delete(`/banners/${props.banner.id}`, {
    onSuccess: () => {
      router.visit('/banners');
    },
    onError: (errors) => {
      console.error(errors);
    },
  });
}
</script>

<template>
  <Head :title="`Banner: ${banner.title}`" />

  <AppLayout :breadcrumbs="[
    { title: 'Banners', href: '/banners' },
    { title: banner.title }
  ]">
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex items-center justify-between m-6">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">{{ banner.title }}</h1>
          <div class="mt-2 flex items-center space-x-4">
            <span 
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
              :class="getStatusBadge(banner).class"
            >
              {{ getStatusBadge(banner).text }}
            </span>
            <span class="text-sm text-gray-500">
              Ordem: {{ banner.sort_order }}
            </span>
          </div>
        </div>
        
        <div class="flex space-x-3">
          <button
            @click="editBanner"
            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <Edit class="h-4 w-4 mr-2" />
            Editar
          </button>
          
          <button
            @click="showDeleteDialog = true"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
          >
            <Trash2 class="h-4 w-4 mr-2" />
            Excluir
          </button>
        </div>
      </div>

      <!-- Conteúdo Principal -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 m-6">
        <!-- Imagem do Banner -->
        <div class="lg:col-span-2">
          <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 flex items-center">
                <Image class="h-5 w-5 mr-2" />
                Imagem do Banner
              </h3>
              
              <div class="aspect-video w-full bg-gray-100 rounded-lg overflow-hidden">
                <img 
                  v-if="banner.image" 
                  :src="`/storage/${banner.image}`" 
                  :alt="banner.title"
                  class="w-full h-full object-cover"
                />
                <div v-else class="w-full h-full flex items-center justify-center text-gray-500">
                  Sem imagem
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Informações do Banner -->
        <div class="space-y-6">
          <!-- Detalhes Básicos -->
          <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Detalhes
              </h3>
              
              <dl class="space-y-4">
                <div v-if="banner.description">
                  <dt class="text-sm font-medium text-gray-500">Descrição</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ banner.description }}</dd>
                </div>
                
                <div v-if="banner.link_url">
                  <dt class="text-sm font-medium text-gray-500">Link</dt>
                  <dd class="mt-1">
                    <a 
                      :href="banner.link_url" 
                      target="_blank"
                      class="inline-flex items-center text-sm text-indigo-600 hover:text-indigo-800"
                    >
                      <ExternalLink class="h-4 w-4 mr-1" />
                      {{ banner.link_text || 'Abrir Link' }}
                    </a>
                  </dd>
                </div>
                
                <div>
                  <dt class="text-sm font-medium text-gray-500">Status</dt>
                  <dd class="mt-1">
                    <span 
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      :class="getStatusBadge(banner).class"
                    >
                      {{ getStatusBadge(banner).text }}
                    </span>
                  </dd>
                </div>
                
                <div>
                  <dt class="text-sm font-medium text-gray-500">Ordem de Exibição</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ banner.sort_order }}</dd>
                </div>
              </dl>
            </div>
          </div>

          <!-- Período de Exibição -->
          <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4 flex items-center">
                <Calendar class="h-5 w-5 mr-2" />
                Período de Exibição
              </h3>
              
              <dl class="space-y-4">
                <div>
                  <dt class="text-sm font-medium text-gray-500">Data de Início</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ formatDate(banner.starts_at) }}</dd>
                </div>
                
                <div>
                  <dt class="text-sm font-medium text-gray-500">Data de Fim</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ formatDate(banner.ends_at) }}</dd>
                </div>
              </dl>
            </div>
          </div>

          <!-- Informações do Sistema -->
          <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
              <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                Informações do Sistema
              </h3>
              
              <dl class="space-y-4">
                <div>
                  <dt class="text-sm font-medium text-gray-500">Criado em</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ formatDate(banner.created_at) }}</dd>
                </div>
                
                <div>
                  <dt class="text-sm font-medium text-gray-500">Última atualização</dt>
                  <dd class="mt-1 text-sm text-gray-900">{{ formatDate(banner.updated_at) }}</dd>
                </div>
              </dl>
            </div>
          </div>
        </div>
      </div>

      <!-- Dialog de Confirmação -->
      <ConfirmDialog
        :show="showDeleteDialog"
        title="Excluir Banner"
        message="Tem certeza que deseja excluir este banner? Esta ação não pode ser desfeita."
        @confirm="confirmDelete"
        @cancel="showDeleteDialog = false"
      />
    </div>
  </AppLayout>
</template>