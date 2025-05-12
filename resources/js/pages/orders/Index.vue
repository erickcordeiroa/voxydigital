<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Pedidos',
        href: '/orders',
    },
];

const { props } = usePage();
const orders = ref(props.orders.data);
const pagination = ref({
    current_page: props.orders.current_page,
    last_page: props.orders.last_page,
    total: props.orders.total,
});
const expandedOrder = ref<number | null>(null);
const searchQuery = ref(props.filters?.search || '');

const changePage = (page: number) => {
    router.get('/orders', { page, search: searchQuery.value }, {
        preserveState: true,
        replace: true,
        onSuccess: (pageProps) => {
            orders.value = pageProps.props.orders.data;
            pagination.value = {
                current_page: pageProps.props.orders.current_page,
                last_page: pageProps.props.orders.last_page,
                total: pageProps.props.orders.total,
            };
        },
    });
};

watch(searchQuery, (newValue) => {
    router.get('/orders', { search: newValue }, {
        preserveState: true,
        replace: true,
        onSuccess: (pageProps) => {
            orders.value = pageProps.props.orders.data;
            pagination.value = {
                current_page: pageProps.props.orders.current_page,
                last_page: pageProps.props.orders.last_page,
                total: pageProps.props.orders.total,
            };
        },
    });
});

const toggleOrder = (orderId: number) => {
    expandedOrder.value = expandedOrder.value === orderId ? null : orderId;
};

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(value);
};

const formatPhone = (phone: string) => {
    return phone.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
};

const getStatusColor = (status: string) => {
    const statusColors: Record<string, string> = {
        pending: 'bg-yellow-100 text-yellow-800',
        processing: 'bg-blue-100 text-blue-800',
        completed: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
    };
    return statusColors[status] || 'bg-gray-100 text-gray-800';
};
</script>

<template>
  <Head title="Pedidos" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-6">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
          Histórico de Pedidos
        </h1>
        <div class="relative w-64">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Buscar pedidos..."
            class="w-full rounded-lg border border-gray-300 px-4 py-2 pl-10 focus:border-primary focus:ring-primary"
          />
          <span class="absolute left-3 top-2.5 text-gray-400">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-5 w-5"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
              />
            </svg>
          </span>
        </div>
      </div>

      <div class="grid gap-4">
        <div
          v-for="order in orders"
          :key="order.id"
          class="rounded-xl border border-gray-200 bg-white shadow-sm transition-all hover:shadow-md dark:border-gray-700 dark:bg-gray-800"
        >
          <div class="cursor-pointer p-5" @click="toggleOrder(order.id)">
            <div class="flex items-start justify-between">
              <div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                  Pedido #{{ order.id }}
                  <span
                    class="ml-2 rounded-full px-3 py-1 text-xs font-medium"
                    :class="getStatusColor(order.status)"
                  >
                    {{
                      order.status === "pending"
                        ? "Pendente"
                        : order.status === "processing"
                        ? "Em preparo"
                        : order.status === "completed"
                        ? "Concluído"
                        : "Cancelado"
                    }}
                  </span>
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{
                    new Date(order.created_at).toLocaleDateString("pt-BR", {
                      day: "2-digit",
                      month: "2-digit",
                      year: "numeric",
                      hour: "2-digit",
                      minute: "2-digit",
                    })
                  }}
                </p>
              </div>
              <div class="text-right">
                <p class="text-lg font-bold text-primary">
                  {{ formatCurrency(order.total / 100) }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                  {{ order.items.length }} item{{ order.items.length > 1 ? "s" : "" }}
                </p>
              </div>
            </div>
          </div>

          <!-- Expanded -->
          <div
            v-if="expandedOrder === order.id"
            class="border-t border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-700"
          >
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
              <div>
                <h3 class="mb-2 font-semibold text-gray-700 dark:text-gray-300">
                  Endereço de Entrega
                </h3>
                <p class="text-gray-600 dark:text-gray-300">
                  {{ order.delivery_address }}
                </p>

                <h3 class="mb-2 mt-4 font-semibold text-gray-700 dark:text-gray-300">
                  Observações
                </h3>
                <p class="text-gray-600 dark:text-gray-300">
                  {{ order.note || "Nenhuma observação adicional" }}
                </p>
              </div>

              <div>
                <h3 class="mb-2 font-semibold text-gray-700 dark:text-gray-300">
                  Itens do Pedido
                </h3>
                <div class="space-y-3">
                  <div
                    v-for="item in order.items"
                    :key="item.id"
                    class="flex items-start rounded-lg border border-gray-200 p-3 dark:border-gray-600"
                  >
                    <div
                      class="mr-3 h-12 w-12 flex-shrink-0 overflow-hidden rounded-md bg-gray-100"
                    >
                      <!-- Aqui você pode adicionar uma imagem do produto se disponível -->
                      <div class="flex h-full items-center justify-center text-gray-400">
                        <img
                          :src="`/storage/${item.product.uri}`"
                          alt="Imagem do produto"
                          class="h-16 w-16 object-cover rounded-md border"
                        />
                      </div>
                    </div>
                    <div class="flex-1">
                      <p class="font-medium text-gray-900 dark:text-white">
                        Produto #{{ item.product_id }}
                      </p>
                      <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ item.quantity }} × {{ formatCurrency((item.price / 100)) }}
                      </p>
                    </div>
                    <div class="ml-2 font-medium text-gray-900 dark:text-white">
                      {{ formatCurrency((item.price * item.quantity) / 100) }}
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div
              class="mt-4 flex justify-end space-x-3 border-t pt-4 dark:border-gray-600"
            >
              <button
                v-if="order.status === 'pending'"
                class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700"
              >
                Marcar como Preparando
              </button>
              <button
                v-if="order.status === 'processing'"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
              >
                Marcar como Enviado
              </button>
              <button
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600"
              >
                Imprimir
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="flex items-center justify-between mt-6">
        <span class="text-sm text-gray-700 dark:text-gray-400">
          Mostrando <span class="font-semibold">{{ pagination.current_page }}</span> de
          <span class="font-semibold">{{ pagination.last_page }}</span> páginas
        </span>
        <div class="inline-flex space-x-2">
          <button
            @click="changePage(pagination.current_page - 1)"
            :disabled="pagination.current_page === 1"
            class="rounded-lg border border-gray-300 bg-white px-3 py-1 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700"
          >
            Anterior
          </button>
          <button
            @click="changePage(pagination.current_page + 1)"
            :disabled="pagination.current_page === pagination.last_page"
            class="rounded-lg border border-gray-300 bg-white px-3 py-1 text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700"
          >
            Próximo
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
