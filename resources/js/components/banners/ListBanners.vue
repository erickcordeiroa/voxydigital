<script setup lang="ts">
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";
import { router } from "@inertiajs/vue3";
import { Edit, Trash2, Eye, ExternalLink } from "lucide-vue-next";

defineOptions({ name: "ListBanners" });

const props = defineProps<{
  banners: Array<{
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
}>();

const emit = defineEmits<{
  update: [banner: any];
  delete: [bannerId: number];
}>();

function formatDate(dateString?: string): string {
  if (!dateString) return '-';
  return new Date(dateString).toLocaleDateString('pt-BR');
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
</script>

<template>
  <div class="w-full overflow-x-auto">
    <Table class="min-w-full">
      <TableCaption>Lista de todos os banners registrados</TableCaption>
      <TableHeader>
        <TableRow>
          <TableHead class="w-[80px]">ID</TableHead>
          <TableHead class="w-[120px]">Imagem</TableHead>
          <TableHead class="flex-grow">Título</TableHead>
          <TableHead class="w-[100px]">Ordem</TableHead>
          <TableHead class="w-[100px]">Status</TableHead>
          <TableHead class="w-[120px]">Período</TableHead>
          <TableHead class="w-[150px]">Ações</TableHead>
        </TableRow>
      </TableHeader>
      <TableBody>
        <TableRow v-for="banner in props.banners" :key="banner.id">
          <TableCell class="w-[80px] font-medium">{{ banner.id }}</TableCell>
          <TableCell class="w-[120px]">
            <img 
              v-if="banner.image" 
              :src="`/storage/${banner.image}`" 
              :alt="banner.title"
              class="w-16 h-10 object-cover rounded"
            />
            <div v-else class="w-16 h-10 bg-gray-200 rounded flex items-center justify-center">
              <span class="text-xs text-gray-500">Sem img</span>
            </div>
          </TableCell>
          <TableCell class="flex-grow">
            <div>
              <div class="font-medium">{{ banner.title }}</div>
              <div v-if="banner.description" class="text-sm text-gray-500 truncate max-w-xs">
                {{ banner.description }}
              </div>
            </div>
          </TableCell>
          <TableCell class="w-[100px] text-center">{{ banner.sort_order }}</TableCell>
          <TableCell class="w-[100px]">
            <span 
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
              :class="getStatusBadge(banner).class"
            >
              {{ getStatusBadge(banner).text }}
            </span>
          </TableCell>
          <TableCell class="w-[120px] text-sm">
            <div v-if="banner.starts_at || banner.ends_at">
              <div v-if="banner.starts_at">De: {{ formatDate(banner.starts_at) }}</div>
              <div v-if="banner.ends_at">Até: {{ formatDate(banner.ends_at) }}</div>
            </div>
            <span v-else class="text-gray-500">Sempre</span>
          </TableCell>
          <TableCell class="w-[150px]">
            <div class="flex space-x-2">
              <!-- Botão de Visualizar -->
              <button
                @click="router.visit(`/banners/${banner.id}`)"
                class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-full transition-colors"
                title="Visualizar"
              >
                <Eye class="h-4 w-4" />
              </button>
              
              <!-- Botão de Link Externo (se tiver link_url) -->
              <a
                v-if="banner.link_url"
                :href="banner.link_url"
                target="_blank"
                class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-full transition-colors"
                title="Abrir Link"
              >
                <ExternalLink class="h-4 w-4" />
              </a>
              
              <!-- Botão de Editar -->
              <button
                @click="emit('update', banner)"
                class="p-2 text-yellow-600 hover:text-yellow-800 hover:bg-yellow-50 rounded-full transition-colors"
                title="Editar"
              >
                <Edit class="h-4 w-4" />
              </button>
              
              <!-- Botão de Deletar -->
              <button
                @click="emit('delete', banner.id)"
                class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-full transition-colors"
                title="Deletar"
              >
                <Trash2 class="h-4 w-4" />
              </button>
            </div>
          </TableCell>
        </TableRow>
        
        <!-- Linha para quando não há banners -->
        <TableRow v-if="!props.banners || props.banners.length === 0">
          <TableCell :colspan="7" class="text-center py-8 text-gray-500">
            Nenhum banner encontrado
          </TableCell>
        </TableRow>
      </TableBody>
    </Table>
  </div>
</template>