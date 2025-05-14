<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import BarChart from '@/components/ui/chart/BarChart.vue';
import LineChart from '@/components/ui/chart/LineChart.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import {ref, defineProps} from 'vue';

const props = defineProps<{
  totalProducts: number;
  totalOrdersReceived: number;
  totalOrderValues: number;
  averageOrderValue: number;
  recentOrders: Array;
  weeklyOrdersData: Array;
  revenueData: Array;
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
];

const metrics = {
  totalProducts: props.totalProducts || 0,
  totalOrders: props.totalOrdersReceived || 0,
  totalRevenue: ((props.totalOrderValues) /100).toFixed(2),
  weeklyGrowth: ((props.averageOrderValue) / 100).toFixed(2),
};

const weeklyOrdersData = ref(props.weeklyOrdersData);
const revenueData = ref(props.revenueData);
const recentOrders = props.recentOrders;

const formatCurrency = (value: number) => {
  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  }).format(value);
};

const formatDate = (dateString: string) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('pt-BR');
};

const getStatusColor = (status: string) => {
  const statusColors = {
    completed: 'bg-green-100 text-green-800',
    processing: 'bg-blue-100 text-blue-800',
    pending: 'bg-yellow-100 text-yellow-800',
    cancelled: 'bg-red-100 text-red-800',
  };
  return statusColors[status] || 'bg-gray-100 text-gray-800';
};

const getStatusText = (status: string) => {
  const statusTexts = {
    completed: 'Concluído',
    processing: 'Em preparo',
    pending: 'Pendente',
    cancelled: 'Cancelado',
  };
  return statusTexts[status] || status;
};
</script>

<template>
  <Head title="Dashboard" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-6">
      <!-- Cards de Métricas -->
      <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Produtos Cadastrados</CardTitle>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              class="h-4 w-4 text-muted-foreground"
            >
              <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
            </svg>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ metrics.totalProducts }}</div>
            <p class="text-xs text-muted-foreground">Total de produtos no sistema</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Pedidos Recebidos</CardTitle>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              class="h-4 w-4 text-muted-foreground"
            >
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
              <circle cx="9" cy="7" r="4" />
              <path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">{{ metrics.totalOrders }}</div>
            <p class="text-xs text-muted-foreground">
              <span
                :class="metrics.weeklyGrowth >= 0 ? 'text-green-500' : 'text-red-500'"
              >
                {{ metrics.weeklyGrowth >= 0 ? "+" : "" }}{{ metrics.weeklyGrowth }}%
              </span>
              em relação à semana passada
            </p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Valor Total</CardTitle>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              class="h-4 w-4 text-muted-foreground"
            >
              <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
            </svg>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">
              {{ formatCurrency(metrics.totalRevenue) }}
            </div>
            <p class="text-xs text-muted-foreground">Faturamento total</p>
          </CardContent>
        </Card>

        <Card>
          <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-sm font-medium">Média por Pedido</CardTitle>
            <svg
              xmlns="http://www.w3.org/2000/svg"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              class="h-4 w-4 text-muted-foreground"
            >
              <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
            </svg>
          </CardHeader>
          <CardContent>
            <div class="text-2xl font-bold">
              {{ formatCurrency(metrics.totalRevenue / metrics.totalOrders) }}
            </div>
            <p class="text-xs text-muted-foreground">Ticket médio</p>
          </CardContent>
        </Card>
      </div>

      <!-- Gráficos -->
      <div class="grid gap-4 md:grid-cols-2">
        <Card class="p-4">
          <CardHeader>
            <CardTitle>Pedidos na Última Semana</CardTitle>
          </CardHeader>
          <CardContent class="h-[300px]">
            <BarChart :data="weeklyOrdersData" />
          </CardContent>
        </Card>

        <Card class="p-4">
          <CardHeader>
            <CardTitle>Faturamento Diário</CardTitle>
          </CardHeader>
          <CardContent class="h-[300px]">
            <LineChart :data="revenueData" />
          </CardContent>
        </Card>
      </div>

      <!-- Últimos Pedidos -->
      <Card>
        <CardHeader>
          <CardTitle>Últimos Pedidos</CardTitle>
        </CardHeader>
        <CardContent>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead class="w-[100px]">Pedido</TableHead>
                <TableHead>Cliente</TableHead>
                <TableHead>Data</TableHead>
                <TableHead>Status</TableHead>
                <TableHead class="text-right">Valor</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="order in recentOrders" :key="order.id">
                <TableCell class="font-medium">#{{ order.id }}</TableCell>
                <TableCell>{{ order.customer_name }}</TableCell>
                <TableCell>{{ formatDate(order.created_at) }}</TableCell>
                <TableCell>
                  <span
                    class="rounded-full px-3 py-1 text-xs font-medium"
                    :class="getStatusColor(order.status)"
                  >
                    {{ getStatusText(order.status) }}
                  </span>
                </TableCell>
                <TableCell class="text-right">{{
                  formatCurrency((order.total/100))
                }}</TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
