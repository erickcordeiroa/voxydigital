<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Download, FileCodeIcon, ExternalLink } from 'lucide-vue-next';
import { toast } from 'vue-sonner';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Exportar Produtos',
    href: '/products/export',
  },
];

const downloadGoogleShopping = () => {
  const url = route('products.export.google-shopping');
  window.open(url, '_blank');
  toast.success('Download iniciado!', {
    description: 'O arquivo XML do Google Shopping está sendo baixado.',
  });
};

const downloadMeta = () => {
  const url = route('products.export.meta');
  window.open(url, '_blank');
  toast.success('Download iniciado!', {
    description: 'O arquivo XML do Meta está sendo baixado.',
  });
};

const openGoogleShopping = () => {
  const url = route('products.export.google-shopping.public');
  window.open(url, '_blank');
};

const openMeta = () => {
  const url = route('products.export.meta.public');
  window.open(url, '_blank');
};
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <Head title="Exportar Produtos" />

    <SettingsLayout>
      <div class="flex flex-col space-y-6">
        <HeadingSmall
          title="Exportar Produtos"
          description="Exporte seus produtos em formato XML para integração com marketplaces"
        />

        <div class="grid gap-6 md:grid-cols-2">
          <!-- Google Shopping -->
          <Card>
            <CardHeader>
              <div class="flex items-center gap-2">
                <FileCodeIcon class="h-5 w-5 text-primary" />
                <CardTitle>Google Shopping</CardTitle>
              </div>
              <CardDescription>
                Exporte seus produtos para o Google Merchant Center. Formato compatível
                com Google Shopping.
              </CardDescription>
            </CardHeader>
            <CardContent class="space-y-4">
              <div class="space-y-2">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                  O arquivo XML contém todos os seus produtos ativos formatados para o
                  Google Shopping.
                </p>
                <ul
                  class="list-inside list-disc space-y-1 text-sm text-gray-600 dark:text-gray-400"
                >
                  <li>Formato RSS 2.0 com namespace Google</li>
                  <li>Inclui imagens, preços e descrições</li>
                  <li>Compatível com Google Merchant Center</li>
                </ul>
              </div>
              <div class="flex gap-2">
                <Button @click="downloadGoogleShopping" class="flex-1">
                  <Download class="mr-2 h-4 w-4" />
                  Baixar XML
                </Button>
                <Button @click="openGoogleShopping" variant="outline">
                  <ExternalLink class="h-4 w-4" />
                </Button>
              </div>
            </CardContent>
          </Card>

          <!-- Meta (Facebook/Instagram) -->
          <Card>
            <CardHeader>
              <div class="flex items-center gap-2">
                <FileCodeIcon class="h-5 w-5 text-primary" />
                <CardTitle>Meta (Facebook/Instagram)</CardTitle>
              </div>
              <CardDescription>
                Exporte seus produtos para o Facebook e Instagram Shopping. Formato
                compatível com Meta Commerce.
              </CardDescription>
            </CardHeader>
            <CardContent class="space-y-4">
              <div class="space-y-2">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                  O arquivo XML contém todos os seus produtos ativos formatados para o
                  Meta Shopping.
                </p>
                <ul
                  class="list-inside list-disc space-y-1 text-sm text-gray-600 dark:text-gray-400"
                >
                  <li>Formato RSS 2.0 compatível com Meta</li>
                  <li>Inclui imagens, preços e descrições</li>
                  <li>Compatível com Facebook e Instagram Shopping</li>
                </ul>
              </div>
              <div class="flex gap-2">
                <Button @click="downloadMeta" class="flex-1">
                  <Download class="mr-2 h-4 w-4" />
                  Baixar XML
                </Button>
                <Button @click="openMeta" variant="outline">
                  <ExternalLink class="h-4 w-4" />
                </Button>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Informações adicionais -->
        <Card>
          <CardHeader>
            <CardTitle>Como usar</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="space-y-4 text-sm text-gray-600 dark:text-gray-400">
              <div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
                  Google Shopping
                </h3>
                <ol class="list-inside list-decimal space-y-1 ml-2">
                  <li>Baixe o arquivo XML do Google Shopping</li>
                  <li>Acesse o Google Merchant Center</li>
                  <li>Vá em "Produtos" → "Feeds"</li>
                  <li>Adicione um novo feed e faça upload do arquivo XML</li>
                  <li>Ou configure uma URL de feed usando o link público</li>
                </ol>
              </div>
              <div>
                <h3 class="font-semibold text-gray-900 dark:text-white mb-2">
                  Meta Shopping
                </h3>
                <ol class="list-inside list-decimal space-y-1 ml-2">
                  <li>Baixe o arquivo XML do Meta</li>
                  <li>Acesse o Facebook Commerce Manager</li>
                  <li>Vá em "Catálogo" → "Feeds de dados"</li>
                  <li>Adicione um novo feed e faça upload do arquivo XML</li>
                  <li>Ou configure uma URL de feed usando o link público</li>
                </ol>
              </div>
              <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <p class="text-sm">
                  <strong>Dica:</strong> Os links públicos podem ser usados para
                  atualização automática dos feeds. Configure-os diretamente nos
                  marketplaces para atualizações em tempo real.
                </p>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </SettingsLayout>
  </AppLayout>
</template>
