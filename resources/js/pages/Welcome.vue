<template>
  <Toaster />
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

    <!-- Hero Section -->
    <section class="bg-black text-white pt-20 pb-32 px-4">
      <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center gap-16">
        <!-- Texts on the left -->
        <div class="flex-1 flex flex-col items-start md:pr-12">
          <img src="/images/logo-voxy.png" alt="Voxy Digital" class="h-16 md:h-20 mb-8" />
          <span
            class="mb-4 text-[#E1FF00] font-semibold text-base tracking-wide bg-white/10 px-4 py-1 rounded-full"
          >
            Mais de 100 empresas já usam
          </span>
          <h1 class="text-4xl md:text-5xl font-extrabold mb-4 text-left leading-tight">
            Uma solução para todas as necessidades do seu catálogo digital
          </h1>
          <p class="text-lg text-gray-200 mb-10 text-left max-w-xl">
            Transforme catálogos físicos em experiências digitais inteligentes,
            interativas e mensuráveis. Atualize, compartilhe e venda de forma simples e
            moderna.
          </p>
          <a
            href="#pre-cadastro"
            class="inline-block bg-[#E1FF00] text-black font-bold rounded-lg px-10 py-4 mb-8 shadow-xl hover:bg-yellow-300 transition text-lg"
          >
            Quero fazer o pré-cadastro
          </a>
        </div>
        <!-- Desktop image on the right -->
        <div class="flex-1 flex justify-center relative w-full">
          <div
            class="z-20 bg-white rounded-2xl shadow-2xl border-4 border-white p-2 md:p-2"
            style="max-width: 360px"
          >
            <img
              src="/images/desktop.png"
              alt="Catálogo Digital na Web"
              class="rounded-2xl w-full h-auto"
              style="display: block"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Problema e Solução -->
    <section class="py-20 bg-gray-50 pt-12">
      <div class="max-w-5xl mx-auto px-4 text-center">
        <div class="grid md:grid-cols-3 gap-8 w-full mb-24">
          <div
            class="bg-white text-black rounded-2xl shadow-lg p-8 flex flex-col items-center"
          >
            <span
              class="mb-4 bg-[#E1FF00] rounded-full p-4 flex items-center justify-center"
            >
              <svg
                class="h-10 w-10 text-black"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                />
              </svg>
            </span>
            <h3 class="font-bold text-xl mb-2">Catálogo Digital</h3>
            <p class="text-gray-700 text-center">
              Visualização fácil, atualização instantânea e integração com WhatsApp.
            </p>
          </div>
          <div
            class="bg-white text-black rounded-2xl shadow-lg p-8 flex flex-col items-center"
          >
            <span
              class="mb-4 bg-[#E1FF00] rounded-full p-4 flex items-center justify-center"
            >
              <svg
                class="h-10 w-10 text-black"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                />
              </svg>
            </span>
            <h3 class="font-bold text-xl mb-2">Analytics Avançado</h3>
            <p class="text-gray-700 text-center">
              Descubra o que mais interessa seus clientes e otimize suas vendas.
            </p>
          </div>
          <div
            class="bg-white text-black rounded-2xl shadow-lg p-8 flex flex-col items-center"
          >
            <span
              class="mb-4 bg-[#E1FF00] rounded-full p-4 flex items-center justify-center"
            >
              <svg
                class="h-10 w-10 text-black"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"
                />
              </svg>
            </span>
            <h3 class="font-bold text-xl mb-2">Integrações</h3>
            <p class="text-gray-700 text-center">
              Conecte seu catálogo a sistemas de pedidos, ERP e CRM.
            </p>
          </div>
        </div>

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
            <div class="flex items-center mb-4">
              <div
                class="bg-[#E1FF00] rounded-full p-3 mr-3 flex items-center justify-center"
              >
                <svg
                  class="h-6 w-6 text-black"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M18 6L6 18M6 6l12 12"
                  />
                </svg>
              </div>
              <h3 class="font-bold text-xl text-left">Problemas do Catálogo Físico</h3>
            </div>
            <ul class="text-gray-700 text-left list-disc ml-5 space-y-2">
              <li>Alto custo de impressão e distribuição</li>
              <li>Dificuldade para atualizar informações</li>
              <li>Sem dados sobre o interesse dos clientes</li>
              <li>Alcance limitado e pouca interatividade</li>
            </ul>
          </div>
          <div class="bg-black text-white rounded-xl shadow-md p-8 flex-1">
            <div class="flex items-center mb-4">
              <div
                class="bg-[#E1FF00] rounded-full p-3 mr-3 flex items-center justify-center"
              >
                <svg
                  class="h-6 w-6 text-black"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
              </div>
              <h3 class="font-bold text-xl text-left">Solução Voxy Digital</h3>
            </div>
            <ul class="text-white text-left list-disc ml-5 space-y-2">
              <li>Atualização instantânea e sem custos extras</li>
              <li>Distribuição ilimitada: alcance clientes em qualquer lugar</li>
              <li>Analytics: saiba o que realmente interessa ao seu público</li>
              <li>Integração com canais de venda e atendimento</li>
            </ul>
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

    <!-- Sobre a Empresa -->
    <section class="py-20 bg-white">
      <div class="max-w-5xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold text-black mb-8">Sobre a Voxy Digital</h2>
        <p class="text-lg text-gray-700 leading-relaxed max-w-3xl mx-auto mb-12">
          Especialistas em transformação digital para o varejo e indústria, a
          <span class="font-semibold text-black">Voxy Digital</span> desenvolve soluções
          que <strong>conectam empresas e clientes</strong> através de experiências
          digitais intuitivas e ricas em dados.
        </p>
        <div class="grid md:grid-cols-3 gap-10 text-left">
          <div class="bg-gray-50 rounded-2xl shadow-lg p-8 flex flex-col items-center">
            <span
              class="mb-4 bg-[#E1FF00] rounded-full p-4 flex items-center justify-center"
            >
              <svg
                class="h-10 w-10 text-black"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7a2 2 0 002 2z"
                />
              </svg>
            </span>
            <h3 class="text-xl font-bold text-black mb-3">5+ Anos de Experiência</h3>
            <p class="text-base leading-relaxed text-gray-700 text-center">
              Já ajudamos dezenas de empresas a migrarem seus catálogos para o digital,
              com resultados comprovados.
            </p>
          </div>
          <div class="bg-gray-50 rounded-2xl shadow-lg p-8 flex flex-col items-center">
            <span
              class="mb-4 bg-[#E1FF00] rounded-full p-4 flex items-center justify-center"
            >
              <svg
                class="h-10 w-10 text-black"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M13 16h-1v-4h-1m1-4h.01M12 20c4.418 0 8-3.582 8-8s-3.582-8-8-8-8 3.582-8 8 3.582 8 8 8z"
                />
              </svg>
            </span>
            <h3 class="text-xl font-bold text-black mb-3">Tecnologia Própria</h3>
            <p class="text-base leading-relaxed text-gray-700 text-center">
              Desenvolvemos nossa plataforma específica para catálogos digitais, com foco
              em usabilidade e performance.
            </p>
          </div>
          <div class="bg-gray-50 rounded-2xl shadow-lg p-8 flex flex-col items-center">
            <span
              class="mb-4 bg-[#E1FF00] rounded-full p-4 flex items-center justify-center"
            >
              <svg
                class="h-10 w-10 text-black"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <rect
                  width="12"
                  height="20"
                  x="6"
                  y="2"
                  rx="2"
                  ry="2"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
                <circle
                  cx="12"
                  cy="18"
                  r="1"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"
                />
              </svg>
            </span>
            <h3 class="text-xl font-bold text-black mb-3">Mobile First</h3>
            <p class="text-base leading-relaxed text-gray-700 text-center">
              Nossas soluções são desenvolvidas pensando primeiro na experiência mobile,
              onde a maioria dos acessos acontece.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Formulário de Pré-cadastro -->
    <section id="pre-cadastro" class="py-20 bg-gray-100 flex justify-center">
      <div
        class="bg-white rounded-2xl shadow-xl p-0 md:p-0 flex flex-col md:flex-row max-w-5xl w-full overflow-hidden"
      >
        <!-- Texto chamativo -->
        <div
          class="flex-1 bg-[#000] flex flex-col justify-center items-start p-8 md:p-12"
        >
          <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4 text-left">
            Dê o próximo passo para o digital!
          </h2>
          <p class="text-lg text-white mb-6 text-left">
            Cadastre sua empresa e seja um dos primeiros a transformar seu catálogo físico
            em uma experiência digital inteligente, interativa e mensurável.
          </p>
          <ul class="text-white text-base space-y-2 mb-4">
            <li class="flex items-center">
              <span
                class="bg-[#E1FF00] rounded-full p-1 mr-2 flex items-center justify-center"
              >
                <svg
                  class="h-5 w-5 text-black"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
              </span>
              Atualização instantânea
            </li>
            <li class="flex items-center">
              <span
                class="bg-[#E1FF00] rounded-full p-1 mr-2 flex items-center justify-center"
              >
                <svg
                  class="h-5 w-5 text-black"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
              </span>
              Integração com WhatsApp
            </li>
            <li class="flex items-center">
              <span
                class="bg-[#E1FF00] rounded-full p-1 mr-2 flex items-center justify-center"
              >
                <svg
                  class="h-5 w-5 text-black"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
              </span>
              Analytics avançado
            </li>
            <li class="flex items-center">
              <span
                class="bg-[#E1FF00] rounded-full p-1 mr-2 flex items-center justify-center"
              >
                <svg
                  class="h-5 w-5 text-black"
                  fill="none"
                  stroke="currentColor"
                  stroke-width="2"
                  viewBox="0 0 24 24"
                >
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M5 13l4 4L19 7"
                  />
                </svg>
              </span>
              Suporte especializado
            </li>
          </ul>
          <span class="text-sm text-white text-left">
            Pré-cadastro sem compromisso. Nossa equipe entrará em contato!
          </span>
        </div>
        <!-- Formulário -->
        <div class="flex-1 p-8 md:p-10">
          <h2 class="text-2xl font-bold mb-4 text-left text-black">
            Faça o pré-cadastro da sua empresa
          </h2>
          <form class="space-y-4" @submit="sendForm">
            <div>
              <label class="block text-gray-700 mb-1 font-semibold"
                >Nome da Empresa</label
              >
              <input
                :disabled="loading"
                v-model="name"
                type="text"
                class="w-full text-black border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#E1FF00]"
                placeholder="Ex: Loja Exemplo"
                required
              />
              <span v-if="errors.name" class="text-red-600 text-sm">{{
                errors.name
              }}</span>
            </div>
            <div>
              <label class="block text-gray-700 mb-1 font-semibold"
                >Nome do Responsável</label
              >
              <input
                :disabled="loading"
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
                :disabled="loading"
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
                :disabled="loading"
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
            <div>
              <label class="block text-gray-700 mb-1 font-semibold">CPF/CNPJ</label>
              <input
                :disabled="loading"
                v-model="document"
                type="tel"
                class="w-full text-black border border-gray-300 rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#E1FF00]"
                placeholder="CPF/CNPJ"
                required
              />
              <span v-if="errors.document" class="text-red-600 text-sm">{{
                errors.document
              }}</span>
            </div>
            <button
              type="submit"
              class="w-full py-3 mt-4 rounded font-bold text-black flex items-center justify-center cursor-pointer"
              style="background: #e1ff00"
            >
              <span v-if="loading" class="animate-spin mr-2">&#9696;</span>
              Realizar Pré-Cadastro
            </button>
          </form>
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

<script lang="ts" setup>
import { Head } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import { toast } from "vue-sonner";
import { isValidDocument } from "@/lib/utils";
import { Toaster } from "@/components/ui/sonner";

const name = ref("");
const responsible = ref("");
const email = ref("");
const whatsapp = ref("");
const document = ref("");
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

  if (!name.value) errors.value.name = "Informe o nome da empresa.";
  if (!responsible.value) errors.value.responsible = "Informe o responsável.";
  if (!email.value) errors.value.email = "Informe o e-mail.";
  else if (!validateEmail(email.value)) errors.value.email = "E-mail inválido.";
  if (!whatsapp.value) errors.value.whatsapp = "Informe o WhatsApp.";
  else if (!validateWhatsapp(whatsapp.value))
    errors.value.whatsapp = "WhatsApp inválido. Preencha no formato (00) 00000-0000.";

  if (!document.value) errors.value.document = "Informe o CPF ou CNPJ.";
  else if (!isValidDocument(document.value))
    errors.value.document = "CPF ou CNPJ inválido.";

  if (Object.keys(errors.value).length > 0) {
    toast.error("Preencha corretamente os campos obrigatórios.");
    loading.value = false;
    return;
  }

  loading.value = true;
  try {
    router.post(
      route("tenant.register"),
      {
        name: name.value,
        responsible: responsible.value,
        email: email.value,
        whatsapp: whatsapp.value.replace(/\D/g, ""),
        document: document.value.replace(/\D/g, ""),
      },
      {
        onSuccess: () => {
          toast.success(
            "Pré-cadastro enviado com sucesso! Um agente entrará em contato para finalizar o cadastro da sua loja."
          );
          name.value = "";
          responsible.value = "";
          email.value = "";
          whatsapp.value = "";
          document.value = "";
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
