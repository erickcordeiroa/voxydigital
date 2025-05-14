<template>
  <div
    class="relative h-64 w-full bg-cover bg-center"
    :style="`background-image: url('${coverImage}')`"
  >
    <div
      class="absolute inset-0 bg-black bg-opacity-50 flex flex-col items-center justify-center text-white"
    >
      <img
        :src="logoUrl"
        alt="Logo da Loja"
        class="rounded-full mb-2 w-26 h-26"
      />
      <h1 class="text-3xl font-bold">{{ storeName }}</h1>
      <p class="text-sm">
        Contato: {{ formatPhoneNumber(contactPhone) }} | {{ businessHours }}
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps({
  coverImage: {
    type: String,
    default: 'https://placehold.co/1200x300'
  },
  logoUrl: String,
  storeName: String,
  contactPhone: String,
  businessHours: {
    type: String,
    default: 'Seg a Sex: 08h - 18h'
  }
});

const formatPhoneNumber = (whatsapp) => {
  const cleaned = whatsapp?.replace(/\D/g, "") || '';
  const match = cleaned.match(/^(\d{2})(\d{4,5})(\d{4})$/);
  return match ? `(${match[1]}) ${match[2]}-${match[3]}` : whatsapp;
};
</script>