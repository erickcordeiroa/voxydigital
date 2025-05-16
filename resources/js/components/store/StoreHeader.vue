<template>
  <div>
    <!-- Cover -->
    <div
      class="relative h-40 w-full bg-no-repeat bg-center md:h-64 md:bg-cover"
      :style="`background-image: url('${coverImage}')`"
    >
      <!-- Logo sobreposta ao bottom do cover -->
      <div class="absolute left-1/2 bottom-0 transform -translate-x-1/2 translate-y-1/2 z-20">
        <img
          :src="logoUrl"
          alt="Logo da Loja"
          class="rounded-full border-4 border-white shadow-lg w-28 h-28 md:w-32 md:h-32 object-cover"
        />
      </div>
    </div>
    <!-- Informações da empresa -->
    <div class="flex flex-col items-center mt-20">
      <h1 class="text-3xl font-bold mb-1">{{ storeName }}</h1>
      <p class="text-sm text-gray-600">
        Contato: {{ formatPhoneNumber(contactPhone) }} | {{ businessHours }}
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps({
  coverImage: String,
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