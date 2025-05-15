<template>
  <canvas ref="chart" style="width: 100%; height: 300px;"></canvas>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { Chart, LineElement, PointElement, CategoryScale, LinearScale, Tooltip, Filler, LineController } from 'chart.js';

// Registra os componentes necessários do Chart.js
Chart.register(
  LineElement,
  PointElement,
  CategoryScale,
  LinearScale,
  Tooltip,
  Filler,
  LineController
);

const props = defineProps({
  data: {
    type: Array as () => Array<{ name: string; value: number }>,
    required: true,
  },
});

const chart = ref<HTMLCanvasElement | null>(null);
let chartInstance: Chart | null = null;

const renderChart = () => {
  // Destrói o gráfico existente antes de criar um novo
  if (chartInstance) {
    chartInstance.destroy();
    chartInstance = null;
  }

  if (!chart.value || !props.data || !props.data.length) return;

  const ctx = chart.value.getContext('2d');
  if (ctx) {
    chartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels: props.data.map(item => item.name),
        datasets: [
          {
            label: 'Faturamento (R$)',
            data: props.data.map(item => (item.value / 100)),
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            borderColor: 'rgba(16, 185, 129, 1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: 'rgba(16, 185, 129, 1)',
            pointBorderColor: '#fff',
            pointHoverRadius: 5,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: false,
            ticks: {
              callback: function(value) {
                return 'R$ ' + value.toLocaleString('pt-BR');
              }
            }
          }
        },
        plugins: {
          tooltip: {
            callbacks: {
              label: function(context) {
                return 'R$ ' + context.raw.toLocaleString('pt-BR');
              }
            }
          }
        }
      }
    });
  }
};

// Renderiza o gráfico quando o componente é montado
onMounted(renderChart);

// Observa mudanças nos dados e atualiza o gráfico
watch(() => props.data, renderChart, { deep: true });
</script>