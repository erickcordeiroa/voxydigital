<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import LeadForm from "../components/LeadForm.vue";
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
      <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-10 items-center">
        <!-- CTA Content -->
        <div>
          <img src="/images/logo-voxy.png" alt="Voxy Digital" class="h-20 mb-6" />
          <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
            Revolucione seus Catálogos com a <span class="text-[#E1FF00]">Voxy Digital</span>
          </h1>
          <p class="text-lg text-gray-300 mb-6">
            Transforme seus catálogos físicos em experiências digitais interativas, com análise de dados e integração completa com seu CRM.
          </p>
          <ul class="text-md text-gray-400 list-disc ml-6 space-y-1">
            <li>Catálogos digitais responsivos e personalizados</li>
            <li>Análise de comportamento dos clientes</li>
            <li>Integração com sistemas de gestão</li>
            <li>Atualização em tempo real</li>
          </ul>
        </div>

        <!-- Lead Form Component -->
        <LeadForm />
      </div>
    </section>

    <!-- Serviços Section -->
    <section class="py-24 bg-gray-100">
      <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold mb-6 text-black">
          Solução Completa para Catálogos Digitais
        </h2>
        <p class="text-lg text-gray-600 mb-12 max-w-3xl mx-auto">
          Oferecemos uma plataforma completa para criação, distribuição e análise de catálogos digitais, com foco em experiência do usuário e resultados mensuráveis.
        </p>
        <div class="grid md:grid-cols-3 gap-8">
          <div
            class="bg-white border border-gray-200 p-8 rounded-2xl shadow-sm hover:shadow-md transition"
          >
            <h3 class="text-xl font-semibold text-black mb-3">
              📱 Catálogo Digital Interativo
            </h3>
            <p class="text-gray-600 text-base leading-relaxed">
              Transforme seu catálogo físico em uma experiência digital rica, com zoom em produtos, links diretos para compra e integração com WhatsApp.
            </p>
          </div>
          <div
            class="bg-white border border-gray-200 p-8 rounded-2xl shadow-sm hover:shadow-md transition"
          >
            <h3 class="text-xl font-semibold text-black mb-3">📊 Analytics Avançado</h3>
            <p class="text-gray-600 text-base leading-relaxed">
              Saiba exatamente quais produtos seus clientes mais visualizam, quanto tempo passam em cada página e quando estão prontos para comprar.
            </p>
          </div>
          <div
            class="bg-white border border-gray-200 p-8 rounded-2xl shadow-sm hover:shadow-md transition"
          >
            <h3 class="text-xl font-semibold text-black mb-3">🔄 Atualização em Tempo Real</h3>
            <p class="text-gray-600 text-base leading-relaxed">
              Altere preços, adicione novos produtos ou promova itens específicos instantaneamente, sem precisar reimprimir catálogos.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Benefícios -->
    <section class="py-24 bg-white">
      <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-black mb-12 text-center">Vantagens do Catálogo Digital</h2>
        <div class="grid md:grid-cols-2 gap-10">
          <div class="flex items-start">
            <div class="bg-[#E1FF00] rounded-full p-3 mr-4 flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-black mb-2">Redução de Custos</h3>
              <p class="text-gray-700">
                Elimine os altos custos de impressão, distribuição e atualização de catálogos físicos.
              </p>
            </div>
          </div>
          <div class="flex items-start">
            <div class="bg-[#E1FF00] rounded-full p-3 mr-4 flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-black mb-2">Dados em Tempo Real</h3>
              <p class="text-gray-700">
                Tenha acesso imediato a dados valiosos sobre o comportamento de seus clientes.
              </p>
            </div>
          </div>
          <div class="flex items-start">
            <div class="bg-[#E1FF00] rounded-full p-3 mr-4 flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-black mb-2">Distribuição Ilimitada</h3>
              <p class="text-gray-700">
                Seu catálogo pode ser acessado de qualquer lugar, a qualquer momento, sem limites geográficos.
              </p>
            </div>
          </div>
          <div class="flex items-start">
            <div class="bg-[#E1FF00] rounded-full p-3 mr-4 flex-shrink-0">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
              </svg>
            </div>
            <div>
              <h3 class="text-xl font-bold text-black mb-2">Integração Completa</h3>
              <p class="text-gray-700">
                Conecte seu catálogo digital ao seu ERP, CRM ou sistema de pedidos para um fluxo completo.
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Sobre a Empresa -->
    <section class="py-24 bg-gray-100">
      <div class="max-w-5xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold text-black mb-6">Sobre a Voxy Digital</h2>
        <p class="text-lg text-gray-700 leading-relaxed max-w-3xl mx-auto mb-10">
          Especialistas em transformação digital para o varejo e indústria, a <span class="text-[#E1FF00] font-semibold">Voxy Digital</span> desenvolve soluções que
          <strong>conectam empresas e clientes</strong> através de experiências digitais intuitivas e ricas em dados.
        </p>
        <div class="grid md:grid-cols-3 gap-8 text-left text-gray-700">
          <div>
            <h3 class="text-xl font-bold text-black mb-2">📅 5+ Anos de Experiência</h3>
            <p class="text-base leading-relaxed">
              Já ajudamos dezenas de empresas a migrarem seus catálogos para o digital, com resultados comprovados.
            </p>
          </div>
          <div>
            <h3 class="text-xl font-bold text-black mb-2">🛠️ Tecnologia Própria</h3>
            <p class="text-base leading-relaxed">
              Desenvolvemos nossa plataforma específica para catálogos digitais, com foco em usabilidade e performance.
            </p>
          </div>
          <div>
            <h3 class="text-xl font-bold text-black mb-2">📱 Mobile First</h3>
            <p class="text-base leading-relaxed">
              Nossas soluções são desenvolvidas pensando primeiro na experiência mobile, onde a maioria dos acessos acontece.
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
          <img src="/images/logo-voxy.png" alt="Voxy Digital" class="h-20 mb-4" />
          <p class="text-gray-400 text-sm">
            Transformando catálogos físicos em experiências digitais inteligentes desde 2020.
          </p>
        </div>

        <!-- Links rápidos -->
        <div>
          <h4 class="font-bold mb-4 text-white">Nossas Soluções</h4>
          <ul class="space-y-2 text-sm text-gray-300">
            <li><a href="#" class="hover:text-[#E1FF00]">Catálogo Digital</a></li>
            <li><a href="#" class="hover:text-[#E1FF00]">Analytics Avançado</a></li>
            <li><a href="#" class="hover:text-[#E1FF00]">Integrações</a></li>
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
              class="text-[#E1FF00] hover:text-white"
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