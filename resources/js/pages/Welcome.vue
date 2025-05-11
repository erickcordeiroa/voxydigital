<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import LeadForm from "../components/LeadForm.vue";
</script>

<template>
  <div class="font-sans text-gray-800 bg-white">
    <Head>
      <title>Voxy Digital | Soluções em Tecnologia e Marketing</title>
      <meta
        name="description"
        content="Impulsione seu negócio com soluções personalizadas de tecnologia e marketing digital."
      />
      <link rel="icon" href="/images/favicon.png" type="image/x-icon" />
      <meta property="og:title" content="Voxy Digital" />
      <meta
        property="og:description"
        content="Especialistas em sistemas sob medida e tráfego pago com performance."
      />
      <meta property="og:image" content="/images/social-card.png" />
      <meta property="og:url" content="https://www.voxydigital.com" />
      <meta name="twitter:card" content="summary_large_image" />

      <!-- Google Analytics -->
      <script async src="https://www.googletagmanager.com/gtag/js?id=G-XXXXXXX"></script>
      <script>
        window.dataLayer = window.dataLayer || [];
        function gtag() {
          dataLayer.push(arguments);
        }
        gtag("js", new Date());
        gtag("config", "G-XXXXXXX");
      </script>

      <!-- Meta Pixel -->
      <script>
        !(function (f, b, e, v, n, t, s) {
          if (f.fbq) return;
          n = f.fbq = function () {
            n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
          };
          if (!f._fbq) f._fbq = n;
          n.push = n;
          n.loaded = !0;
          n.version = "2.0";
          n.queue = [];
          t = b.createElement(e);
          t.async = !0;
          t.src = v;
          s = b.getElementsByTagName(e)[0];
          s.parentNode.insertBefore(t, s);
        })(window, document, "script", "https://connect.facebook.net/en_US/fbevents.js");
        fbq("init", "XXXXXXXXXXXXXXX");
        fbq("track", "PageView");
      </script>
      <noscript
        ><img
          height="1"
          width="1"
          style="display: none"
          src="https://www.facebook.com/tr?id=XXXXXXXXXXXXXXX&ev=PageView&noscript=1"
      /></noscript>
    </Head>

    <!-- Header + CTA Section -->
    <section class="bg-black text-white py-20 px-4">
      <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-10 items-center">
        <!-- CTA Content -->
        <div>
          <img src="/images/logo-voxy.png" alt="Voxy Digital" class="h-20 mb-6" />
          <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
            Impulsione seu Negócio com a <span class="text-[#E1FF00]">Voxy Digital</span>
          </h1>
          <p class="text-lg text-gray-300 mb-6">
            Especialistas em sistemas sob medida e estratégias de tráfego para alavancar
            seus resultados digitais.
          </p>
          <ul class="text-md text-gray-400 list-disc ml-6 space-y-1">
            <li>Soluções escaláveis e personalizadas</li>
            <li>Campanhas de tráfego com foco em conversão</li>
            <li>Equipe experiente e comprometida</li>
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
          Soluções que Transformam Resultados
        </h2>
        <p class="text-lg text-gray-600 mb-12 max-w-3xl mx-auto">
          Conheça nossos serviços especializados que combinam estratégia, tecnologia e
          criatividade para alavancar seus resultados de forma real e mensurável.
        </p>
        <div class="grid md:grid-cols-3 gap-8">
          <div
            class="bg-white border border-gray-200 p-8 rounded-2xl shadow-sm hover:shadow-md transition"
          >
            <h3 class="text-xl font-semibold text-black mb-3">
              💻 Desenvolvimento de Sistemas
            </h3>
            <p class="text-gray-600 text-base leading-relaxed">
              Criamos sistemas personalizados que automatizam processos, melhoram a gestão
              e aumentam a performance operacional da sua empresa.
            </p>
          </div>
          <div
            class="bg-white border border-gray-200 p-8 rounded-2xl shadow-sm hover:shadow-md transition"
          >
            <h3 class="text-xl font-semibold text-black mb-3">📈 Tráfego Pago</h3>
            <p class="text-gray-600 text-base leading-relaxed">
              Estratégias de mídia paga no Google, Meta e outras plataformas para atrair
              leads qualificados e gerar mais vendas com alta performance.
            </p>
          </div>
          <div
            class="bg-white border border-gray-200 p-8 rounded-2xl shadow-sm hover:shadow-md transition"
          >
            <h3 class="text-xl font-semibold text-black mb-3">📋 Landing Pages</h3>
            <p class="text-gray-600 text-base leading-relaxed">
              Páginas focadas em conversão, com layout estratégico e textos persuasivos
              para capturar leads e potencializar seus resultados.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Sobre a Agência -->
    <section class="py-24 bg-white">
      <div class="max-w-5xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold text-black mb-6">Sobre a Voxy Digital</h2>
        <p class="text-lg text-gray-700 leading-relaxed max-w-3xl mx-auto mb-10">
          A <span class="text-[#E1FF00] font-semibold">Voxy Digital</span> é uma agência
          de tecnologia e marketing digital que entrega
          <strong>soluções sob medida</strong> para empresas que querem escalar com
          qualidade e <strong>foco em performance real</strong>.
        </p>
        <div class="grid md:grid-cols-3 gap-8 text-left text-gray-700">
          <div>
            <h3 class="text-xl font-bold text-black mb-2">🚀 Nossa Missão</h3>
            <p class="text-base leading-relaxed">
              Impulsionar o crescimento de negócios por meio da inovação tecnológica e
              estratégias digitais eficazes, gerando impacto real em resultados.
            </p>
          </div>
          <div>
            <h3 class="text-xl font-bold text-black mb-2">💡 Nosso Diferencial</h3>
            <p class="text-base leading-relaxed">
              Oferecemos um atendimento próximo e consultivo, entregando soluções
              exclusivas alinhadas às metas de cada cliente, com total transparência e
              foco em resultado.
            </p>
          </div>
          <div>
            <h3 class="text-xl font-bold text-black mb-2">🤝 Compromisso</h3>
            <p class="text-base leading-relaxed">
              Construímos parcerias duradouras baseadas na confiança, evolução contínua e
              geração de valor, com foco total na performance do seu negócio.
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
            Soluções em tecnologia e marketing digital para acelerar seu crescimento
            online.
          </p>
        </div>

        <!-- Links rápidos -->
        <div>
          <h4 class="font-bold mb-4 text-white">Links Rápidos</h4>
          <ul class="space-y-2 text-sm text-gray-300">
            <li><a href="#servicos" class="hover:text-[#E1FF00]">Serviços</a></li>
            <li><a href="#contato" class="hover:text-[#E1FF00]">Contato</a></li>
            <li><a href="#sobre" class="hover:text-[#E1FF00]">Quem Somos</a></li>
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
