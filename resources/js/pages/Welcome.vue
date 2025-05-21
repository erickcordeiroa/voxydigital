<script lang="ts" setup>
import { Head } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import { toast } from "vue-sonner";

const company = ref("");
const responsible = ref("");
const email = ref("");
const whatsapp = ref("");
const loading = ref(false);

const errors = ref<{ [key: string]: string }>({});

function validateEmail(email: string) {
  return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

function validateWhatsapp(whatsapp: string) {
  return /^\(?\d{2}\)?\s?\d{4,5}-?\d{4}$/.test(whatsapp);
}

const sendForm = async (e: Event) => {
  e.preventDefault();
  errors.value = {};

  if (!company.value) errors.value.company = "Informe o nome da empresa.";
  if (!responsible.value) errors.value.responsible = "Informe o responsável.";
  if (!email.value) errors.value.email = "Informe o e-mail.";
  else if (!validateEmail(email.value)) errors.value.email = "E-mail inválido.";
  if (!whatsapp.value) errors.value.whatsapp = "Informe o WhatsApp.";
  else if (!validateWhatsapp(whatsapp.value))
    errors.value.whatsapp = "WhatsApp inválido. Preencha no formato (00) 00000-0000.";

  if (Object.keys(errors.value).length > 0) {
    toast.error("Preencha corretamente os campos obrigatórios.");
    return;
  }

  loading.value = true;
  try {
    router.post(
      route("pre-register"),
      {
        company: company.value,
        responsible: responsible.value,
        email: email.value,
        whatsapp: whatsapp.value,
      },
      {
        onSuccess: () => {
          toast.success("Pré-cadastro enviado com sucesso!");
          company.value = "";
          responsible.value = "";
          email.value = "";
          whatsapp.value = "";
        },
        onError: () => {
          toast.error("Erro ao enviar pré-cadastro. Tente novamente.");
        },
        onFinish: () => {
          loading.value = false;
        },
      }
    );
  } catch {
    toast.error("Erro inesperado. Tente novamente.");
    loading.value = false;
  }
};
</script>
<template>
  <div class="font-sans text-gray-800 bg-white">
    <Head>
      <title>Voxy Digital | Catálogo Digital Inteligente</title>
      <meta
        name="description"
        content="Transforme seus catálogos físicos em experiências digitais interativas e mensuráveis."
      />
      <link rel="icon" href="/images/favicon.png" type="image/x-icon" />
      <meta property="og:title" content="Voxy Digital - Catálogo Digital" />
      <meta
        property="og:description"
        content="Solução completa para criação, gestão e análise de catálogos digitais."
      />
      <meta property="og:image" content="/images/social-card.png" />
      <meta property="og:url" content="https://www.voxydigital.com" />
      <meta name="twitter:card" content="summary_large_image" />
    </Head>

    <!-- Header + CTA Section -->
    <section class="bg-black text-white py-20 px-4">
      <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-16 items-center">
        <!-- Chamada -->
        <div class="flex flex-col items-center md:items-start text-center md:text-left">
          <img src="/images/logo-voxy.png" alt="Voxy Digital" class="h-16 md:h-20 mb-8" />
          <h1 class="text-4xl md:text-4xl font-extrabold mb-6 leading-tight">
            Seu catálogo ainda é físico?
            <span class="block" style="color: #e1ff00">Chegou a hora de evoluir!</span>
          </h1>
          <p class="text-xl md:text-1xl mb-8 font-medium max-w-2xl">
            Pare de perder vendas e oportunidades por falta de presença digital.
          </p>
          <p class="text-lg text-gray-200 mb-8 font-medium max-w-2xl">
            Com a <strong class="text-[#E1FF00] font-bold">Voxy Digital</strong>,
            transforme seu catálogo em uma experiência online interativa, acessível de
            qualquer lugar e pronta para converter mais clientes.
          </p>
          <ul
            class="text-lg list-disc list-inside space-y-2 font-normal max-w-xl text-left"
          >
            <li>Visualização fácil em qualquer dispositivo</li>
            <li>Atualização instantânea de produtos e preços</li>
            <li>Dados em tempo real sobre o interesse dos clientes</li>
            <li>Integração com WhatsApp, CRM e sistemas de pedidos</li>
          </ul>
        </div>
        <!-- Formulário -->
        <div class="mt-12 md:mt-0">
          <div class="bg-gray-50 rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold mb-4 text-center text-black">
              Faça o pré-cadastro da sua empresa
            </h2>
            <form class="space-y-4" >
              <div>
                <label class="block text-gray-700 mb-1 font-semibold"
                  >Nome da Empresa</label
                >
                <input
                disabled
                  v-model="company"
                  type="text"
                  class="w-full text-black border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#E1FF00]"
                  placeholder="Ex: Loja Exemplo"
                  required
                />
                <span v-if="errors.company" class="text-red-600 text-sm">{{
                  errors.company
                }}</span>
              </div>
              <div>
                <label class="block text-gray-700 mb-1 font-semibold"
                  >Nome do Responsável</label
                >
                <input
                disabled
                  v-model="responsible"
                  type="text"
                  class="w-full text-black border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#E1FF00]"
                  placeholder="Seu nome"
                  required
                />
                <span v-if="errors.responsible" class="text-red-600 text-sm">{{
                  errors.responsible
                }}</span>
              </div>
              <div>
                <label class="block text-gray-700 mb-1 font-semibold">E-mail</label>
                <input
                disabled
                  v-model="email"
                  type="email"
                  class="w-full text-black border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#E1FF00]"
                  placeholder="email@empresa.com"
                  required
                />
                <span v-if="errors.email" class="text-red-600 text-sm">{{
                  errors.email
                }}</span>
              </div>
              <div>
                <label class="block text-gray-700 mb-1 font-semibold">WhatsApp</label>
                <input
                disabled
                  v-model="whatsapp"
                  type="tel"
                  class="w-full text-black border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#E1FF00]"
                  placeholder="(00) 00000-0000"
                  required
                />
                <span v-if="errors.whatsapp" class="text-red-600 text-sm">{{
                  errors.whatsapp
                }}</span>
              </div>
              <button
                type="submit"
                class="w-full py-3 mt-4 rounded font-bold text-black flex items-center justify-center"
                style="background: #e1ff00"
                :disabled="true"
              >
                <span v-if="loading" class="animate-spin mr-2">&#9696;</span>
                Pré-cadastro desativado por momento
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Problema e Solução -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-5xl mx-auto px-4 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-8">
          Seus clientes querem agilidade. Seu catálogo está pronto para isso?
        </h2>
        <p class="text-lg mb-12 max-w-2xl mx-auto">
          Catálogos físicos são caros, difíceis de atualizar e limitam seu alcance. Com o
          <strong>catálogo digital Voxy</strong>, você elimina barreiras, reduz custos e
          conquista mais clientes com uma experiência moderna e eficiente.
        </p>
        <div class="flex flex-col md:flex-row justify-center gap-8">
          <div class="bg-white rounded-xl shadow-md p-8 flex-1 mb-8 md:mb-0">
            <h3 class="font-bold text-xl mb-4">❌ Problemas do Catálogo Físico</h3>
            <ul class="text-gray-700 text-left list-disc ml-5 space-y-2">
              <li>Alto custo de impressão e distribuição</li>
              <li>Dificuldade para atualizar informações</li>
              <li>Sem dados sobre o interesse dos clientes</li>
              <li>Alcance limitado e pouca interatividade</li>
            </ul>
          </div>
          <div class="bg-gray-900 text-white rounded-xl shadow-md p-8 flex-1">
            <h3 class="font-bold text-xl mb-4">✅ Solução Voxy Digital</h3>
            <ul class="text-primary-300 text-left list-disc ml-5 space-y-2">
              <li>Atualização instantânea e sem custos extras</li>
              <li>Distribuição ilimitada: alcance clientes em qualquer lugar</li>
              <li>Analytics: saiba o que realmente interessa ao seu público</li>
              <li>Integração com canais de venda e atendimento</li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- Benefícios -->
    <section class="py-20 bg-white">
      <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-black mb-12 text-center">
          Vantagens do Catálogo Digital
        </h2>
        <div class="grid md:grid-cols-2 gap-12">
          <div class="flex items-start mb-8 md:mb-0">
            <div class="bg-gray-200 rounded-full p-4 mr-6 flex-shrink-0">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-7 w-7 text-black"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                />
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-black mb-2">Redução de Custos</h3>
              <p class="text-gray-700">
                Elimine os altos custos de impressão, distribuição e atualização de
                catálogos físicos.
              </p>
            </div>
          </div>
          <div class="flex items-start mb-8 md:mb-0">
            <div class="bg-gray-200 rounded-full p-4 mr-6 flex-shrink-0">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-7 w-7 text-black"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                />
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-black mb-2">Dados em Tempo Real</h3>
              <p class="text-gray-700">
                Tenha acesso imediato a dados valiosos sobre o comportamento de seus
                clientes.
              </p>
            </div>
          </div>
          <div class="flex items-start">
            <div class="bg-gray-200 rounded-full p-4 mr-6 flex-shrink-0">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-7 w-7 text-black"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"
                />
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-black mb-2">Distribuição Ilimitada</h3>
              <p class="text-gray-700">
                Seu catálogo pode ser acessado de qualquer lugar, a qualquer momento, sem
                limites geográficos.
              </p>
            </div>
          </div>
          <div class="flex items-start">
            <div class="bg-gray-200 rounded-full p-4 mr-6 flex-shrink-0">
              <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-7 w-7 text-black"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"
                />
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-black mb-2">Integração Completa</h3>
              <p class="text-gray-700">
                Conecte seu catálogo digital ao seu ERP, CRM ou sistema de pedidos para um
                fluxo completo.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Integração com WhatsApp -->
    <section class="py-20 bg-gray-100">
      <div class="max-w-5xl mx-auto px-4 flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1 mb-8 md:mb-0">
          <h2 class="text-3xl font-bold mb-6 text-black">
            Integração inteligente com WhatsApp
          </h2>
          <p class="text-lg text-gray-700 mb-6">
            Receba os pedidos do seu catálogo digital diretamente no WhatsApp da sua
            empresa. Além disso, seus clientes recebem notificações automáticas sobre o
            andamento de cada pedido, trazendo mais agilidade e transparência para o
            processo.
          </p>
          <ul class="list-disc ml-5 text-gray-600 space-y-2">
            <li>Pedidos enviados automaticamente para o WhatsApp da empresa</li>
            <li>Notificações de status de pedido para o cliente via WhatsApp</li>
            <li>Facilite o acompanhamento e aumente a confiança do seu cliente</li>
          </ul>
        </div>
        <div class="flex-1 flex justify-center">
          <img
            src="/images/whatsapp.png"
            alt="Integração WhatsApp"
            class="w-72 md:w-96 rounded-xl shadow-md"
          />
        </div>
      </div>
    </section>

    <!-- Serviços Section -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-8 text-black">
          O que a Voxy Digital faz por você?
        </h2>
        <p class="text-lg text-gray-600 mb-14 max-w-3xl mx-auto">
          Plataforma completa para criar, distribuir e analisar catálogos digitais. Foco
          total em experiência do usuário, automação e resultados mensuráveis para seu
          negócio crescer.
        </p>
        <div class="grid md:grid-cols-3 gap-10">
          <div
            class="bg-gray-50 border border-gray-200 p-8 rounded-2xl shadow-sm hover:shadow-md transition"
          >
            <h3 class="text-xl font-semibold text-black mb-4">
              📱 Catálogo Digital Interativo
            </h3>
            <p class="text-gray-600 text-base leading-relaxed">
              Experiência digital rica, com zoom em produtos, links diretos para compra e
              integração com WhatsApp.
            </p>
          </div>
          <div
            class="bg-gray-50 border border-gray-200 p-8 rounded-2xl shadow-sm hover:shadow-md transition"
          >
            <h3 class="text-xl font-semibold text-black mb-4">📊 Analytics Avançado</h3>
            <p class="text-gray-600 text-base leading-relaxed">
              Descubra quais produtos mais atraem seus clientes e quando estão prontos
              para comprar.
            </p>
          </div>
          <div
            class="bg-gray-50 border border-gray-200 p-8 rounded-2xl shadow-sm hover:shadow-md transition"
          >
            <h3 class="text-xl font-semibold text-black mb-4">
              🔄 Atualização em Tempo Real
            </h3>
            <p class="text-gray-600 text-base leading-relaxed">
              Altere preços, adicione produtos ou promova itens instantaneamente, sem
              reimpressão.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Sobre a Empresa -->
    <section class="py-20 bg-gray-100">
      <div class="max-w-5xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold text-black mb-8">Sobre a Voxy Digital</h2>
        <p class="text-lg text-gray-700 leading-relaxed max-w-3xl mx-auto mb-12">
          Especialistas em transformação digital para o varejo e indústria, a
          <span class="font-semibold text-black">Voxy Digital</span> desenvolve soluções
          que <strong>conectam empresas e clientes</strong> através de experiências
          digitais intuitivas e ricas em dados.
        </p>
        <div class="grid md:grid-cols-3 gap-10 text-left text-gray-700">
          <div>
            <h3 class="text-xl font-bold text-black mb-3">📅 5+ Anos de Experiência</h3>
            <p class="text-base leading-relaxed">
              Já ajudamos dezenas de empresas a migrarem seus catálogos para o digital,
              com resultados comprovados.
            </p>
          </div>
          <div>
            <h3 class="text-xl font-bold text-black mb-3">🛠️ Tecnologia Própria</h3>
            <p class="text-base leading-relaxed">
              Desenvolvemos nossa plataforma específica para catálogos digitais, com foco
              em usabilidade e performance.
            </p>
          </div>
          <div>
            <h3 class="text-xl font-bold text-black mb-3">📱 Mobile First</h3>
            <p class="text-base leading-relaxed">
              Nossas soluções são desenvolvidas pensando primeiro na experiência mobile,
              onde a maioria dos acessos acontece.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Rodapé -->
    <footer class="bg-black text-white py-12">
      <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-3 gap-8">
        <!-- Logo e descrição -->
        <div>
          <img src="/images/logo-voxy.png" alt="Voxy Digital" class="h-16 md:h-20 mb-4" />
          <p class="text-gray-400 text-sm">
            Transformando catálogos físicos em experiências digitais inteligentes desde
            2020.
          </p>
        </div>

        <!-- Links rápidos -->
        <div>
          <h4 class="font-bold mb-4 text-white">Nossas Soluções</h4>
          <ul class="space-y-2 text-sm text-gray-300">
            <li><a href="#" class="hover:text-gray-100">Catálogo Digital</a></li>
            <li><a href="#" class="hover:text-gray-100">Analytics Avançado</a></li>
            <li><a href="#" class="hover:text-gray-100">Integrações</a></li>
          </ul>
        </div>

        <!-- Contato -->
        <div>
          <h4 class="font-bold mb-4 text-white">Fale Conosco</h4>
          <p class="text-sm text-gray-300">Whatsapp: (13) 99663-1713</p>
          <p class="text-sm text-gray-300">Email: contato@voxydigital.com</p>
          <div class="flex space-x-4 mt-4">
            <a
              href="https://instagram.com/voxy.digital"
              class="text-gray-300 hover:text-white"
              >Instagram</a
            >
          </div>
        </div>
      </div>

      <div class="mt-12 text-center text-gray-500 text-sm">
        &copy; 2025 Voxy Digital. Todos os direitos reservados.
      </div>
    </footer>
  </div>
</template>
