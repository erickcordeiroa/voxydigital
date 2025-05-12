<template>
  <canvas ref="chart" style="width: 100%; height: 300px;"></canvas>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Chart, BarElement, CategoryScale, LinearScale } from 'chart.js';

Chart.register(BarElement, CategoryScale, LinearScale);

const props = defineProps({
  data: {
    type: Array,
    required: true,
  },
});

const chart = ref(null);

onMounted(() => {
  if (!chart.value) return; // Verifica se o canvas está disponível
  const ctx = chart.value.getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: props.data.map((item) => item.name),
      datasets: [
        {
          label: 'Valores',
          data: props.data.map((item) => item.value),
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
    },
  });
});
</script>