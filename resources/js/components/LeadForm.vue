<script setup lang="ts">
import { onMounted, ref } from "vue";
import axios from "axios";

const nome = ref("");
const email = ref("");
const telefone = ref("");
const servico = ref("");
const mensagem = ref("");
const enviado = ref(false);
const carregando = ref(false);
const servicos = ref([]);

const getServices = async () => {
  try {
    const response = await axios.get("/api/services");
    servicos.value = response.data.services;
  } catch (error) {
    console.error("Erro ao carregar serviços:", error);
  }
};

onMounted(() => {
  getServices();
});

const send = async () => {
  carregando.value = true;
  try {
    await axios.post("/api/leads", {
      name: nome.value,
      email: email.value,
      phone: telefone.value,
      service: servico.value,
      message: mensagem.value,
    });
    enviado.value = true;
    nome.value = "";
    email.value = "";
    telefone.value = "";
    servico.value = "";
    mensagem.value = "";
  } catch (error: any) {
    alert("Erro ao enviar formulário.");
  }
  carregando.value = false;
};
</script>

<template>
  <div class="bg-white text-black rounded-xl p-6 md:p-8 shadow-lg w-full">
    <h2 class="text-2xl font-bold mb-4">Receba uma proposta personalizada</h2>
    <form
      @submit.prevent="send"
      class="space-y-4"
    >
      <input
        v-model="nome"
        placeholder="Nome completo"
        class="w-full border border-gray-300 px-4 py-3 rounded-md text-base"
      />
      <input
        v-model="email"
        type="email"
        placeholder="Email"
        class="w-full border border-gray-300 px-4 py-3 rounded-md text-base"
      />
      <input
        v-model="telefone"
        placeholder="Telefone/Whatsapp"
        class="w-full border border-gray-300 px-4 py-3 rounded-md text-base"
      />
      <select
        v-model="servico"
        class="w-full border border-gray-300 px-4 py-3 rounded-md text-base"
      >
        <option disabled value="">Selecione o serviço desejado</option>
        <option v-for="s in servicos" :key="s.id" :value="s.id">
          {{ s.name }}
        </option>
      </select>

      <textarea
        v-model="mensagem"
        placeholder="Mensagem"
        class="w-full border border-gray-300 px-4 py-3 rounded-md text-base"
      ></textarea>
      <button
        type="submit"
        class="bg-[#E1FF00] w-full py-2 font-bold rounded"
        :disabled="carregando"
      >
        {{ carregando ? "Enviando..." : "Solicitar Proposta" }}
      </button>
    </form>

    <div v-if="enviado" class="mt-4 text-green-600 font-semibold">
      Obrigado! Seu contato foi enviado com sucesso. Entraremos em contato em breve.
    </div>
  </div>
</template>

