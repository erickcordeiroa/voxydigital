<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Toaster } from '@/components/ui/sonner';
import { toast } from 'vue-sonner';


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
        preparing: 'bg-blue-100 text-blue-800',
        delivered: 'bg-green-100 text-green-800',
        cancelled: 'bg-red-100 text-red-800',
    };
    return statusColors[status] || 'bg-gray-100 text-gray-800';
};

const getStatusText = (status: string) => {
    const statusText: Record<string, string> = {
        pending: 'Pendente',
        preparing: 'Preparando',
        delivered: 'Concluído',
        cancelled: 'Cancelado',
    };
    return statusText[status] || 'Cancelado';
};

const printOrder = (order: any) => {
  const printContent = `
    <!DOCTYPE html>
    <html lang="pt-BR">
      <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pedido #${order.id}</title>
        <style>
          * { margin: 0; padding: 0; box-sizing: border-box; }
          body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            margin: 40px; 
            color: #222; 
            line-height: 1.6;
          }
          h2 { 
            margin-bottom: 20px; 
            padding-bottom: 10px;
            border-bottom: 2px solid #333;
          }
          .info { 
            margin-bottom: 30px; 
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
          }
          .info div { margin-bottom: 8px; }
          table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
          }
          th, td { 
            border: 1px solid #ddd; 
            padding: 10px 12px; 
            text-align: left; 
          }
          th { 
            background: #333; 
            color: white;
            font-weight: 600;
          }
          tbody tr:nth-child(even) {
            background: #f9f9f9;
          }
          .tax { 
            font-weight: bold; 
            font-size: 1em; 
            margin: 15px 0;
            text-align: right;
          }
          .total { 
            font-weight: bold; 
            font-size: 1.3em; 
            margin: 15px 0;
            text-align: right;
            color: #2563eb;
          }
          .label { 
            font-weight: bold; 
            color: #555;
          }
          @media print {
            body { margin: 20px; }
            .no-print { display: none; }
          }
        </style>
      </head>
      <body>
        <h2>Pedido #${order.id}</h2>
        <div class="info">
          <div><span class="label">Status:</span> ${getStatusText(order.status)}</div>
          <div><span class="label">Data:</span> ${new Date(order.created_at).toLocaleString('pt-BR')}</div>
          <div><span class="label">Cliente:</span> ${order.customer_name || 'Não informado'}</div>
          <div><span class="label">Telefone:</span> ${formatPhone(order.customer_phone)}</div>
          <div><span class="label">Endereço:</span> ${order.delivery_address}</div>
          <div><span class="label">Observações:</span> ${order.note || 'Nenhuma'}</div>
        </div>
        <table>
          <thead>
            <tr>
              <th>Produto</th>
              <th>Tamanho</th>
              <th style="text-align: center;">Qtd</th>
              <th style="text-align: right;">Preço Unit.</th>
              <th style="text-align: right;">Total</th>
            </tr>
          </thead>
          <tbody>
            ${order.items.map((item: any) => `
              <tr>
                <td>${item.product.name}</td>
                <td>${item.variation?.size || 'N/A'}</td>
                <td style="text-align: center;">${item.quantity}</td>
                <td style="text-align: right;">${formatCurrency(item.price / 100)}</td>
                <td style="text-align: right;">${formatCurrency((item.price * item.quantity) / 100)}</td>
              </tr>
            `).join('')}
          </tbody>
        </table>
        <div class="tax">Taxa de Entrega: ${formatCurrency(order.tax_fixed / 100)}</div>
        <div class="total">Total do Pedido: ${formatCurrency(order.total / 100)}</div>
      </body>
    </html>
  `;
  
  // Tenta abrir a janela de impressão
  const win = window.open('', '_blank', 'width=800,height=700,left=100,top=100');
  
  if (win) {
    // Escreve o conteúdo na nova janela
    win.document.open();
    win.document.write(printContent);
    win.document.close();
    
    // Aguarda o carregamento e imprime automaticamente
    win.onload = function() {
      setTimeout(() => {
        win.print();
        // Fecha a janela após a impressão ou cancelamento
        win.onafterprint = function() {
          win.close();
        };
      }, 500);
    };
  } else {
    // Fallback: se popup foi bloqueado, avisa o usuário
    toast.error('Popup bloqueado! Por favor, permita popups para este site e tente novamente.');
  }
};

const updateStatus = (orderId: number, status: string) => {
  router.post(route('orders.update'), {
    status: status,
    id: orderId,
   }, {
    onSuccess: (pageProps) => {
      const updatedOrder = pageProps.props.orders.data.find((order: any) => order.id === orderId);
      if (updatedOrder) {
        const index = orders.value.findIndex((order: any) => order.id === orderId);
        if (index !== -1) {
          orders.value[index] = updatedOrder;
        }
      }

      toast.success(`Pedido #${orderId} atualizado para ${getStatusText(status)}`);
    },
  });
};
</script>

<template>
  <Head title="Pedidos" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <Toaster />
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

      <!-- Empty State -->
      <div
        v-if="orders.length === 0"
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
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
          />
        </svg>
        <h2 class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">Nenhum pedido encontrado</h2>
        <p class="mb-4 max-w-md text-sm text-gray-600 dark:text-gray-400">
          Ainda não há pedidos cadastrados no sistema ou nenhum pedido corresponde à sua busca.
        </p>
      </div>

      <div v-else class="grid gap-4">
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
                    {{ getStatusText(order.status) }}
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
                <h3 class="mb-1 font-semibold text-gray-700 dark:text-gray-300">
                  Endereço de Entrega
                </h3>
                <p class="text-gray-600 dark:text-gray-300 mb-2">
                  {{ order.delivery_address }}
                </p>

                <h3 class="mb-1 font-semibold text-gray-700 dark:text-gray-300">
                  Telefone
                </h3>
                <p class="text-gray-600 dark:text-gray-300 mb-2">
                  {{ formatPhone(order.customer_phone) }}
                </p>

                <h3 class="mb-1 mt-4 font-semibold text-gray-700 dark:text-gray-300">
                  Observações
                </h3>
                <p class="text-gray-600 dark:text-gray-300 mb-2">
                  {{ order.note || "Nenhuma observação adicional" }}
                </p>

                <h3 class="mb-1 mt-4 font-semibold text-gray-700 dark:text-gray-300">
                  Taxa de Entrega
                </h3>
                <p class="text-gray-600 dark:text-gray-300 mb-2">
                  {{ formatCurrency(order.tax_fixed / 100) }}
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
                      class="mr-3 h-18 w-18 flex-shrink-0 overflow-hidden rounded-md bg-gray-100"
                    >
                      <!-- Aqui você pode adicionar uma imagem do produto se disponível -->
                      <div class="flex h-full items-center justify-center text-gray-400">
                        <img
                          :src="`/storage/${item.product.uri}`"
                          alt="Imagem do produto"
                          class="h-18 w-18 object-cover rounded-md border"
                        />
                      </div>
                    </div>
                    <div class="flex-1">
                      <p class="font-medium text-gray-900 dark:text-white">
                        {{ item.product.name }}
                      </p>
                      <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ item.quantity }} × {{ formatCurrency(item.price / 100) }}
                      </p>
                      <p class="text-sm text-gray-500 dark:text-gray-400">
                        Tamanho:  {{ item.variation?.size || 'N/A' }}
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
                @click.stop="updateStatus(order.id, 'preparing')"
                class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700"
              >
                Marcar como Preparando
              </button>
              <button
                @click.stop="updateStatus(order.id, 'delivered')"
                v-if="order.status === 'preparing'"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700"
              >
                Marcar como Enviado
              </button>
              <button
                @click.stop="printOrder(order)"
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
