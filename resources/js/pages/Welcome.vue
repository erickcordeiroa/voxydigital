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

    <!-- Números e Estatísticas (Social Proof) -->
    <section class="py-16 bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
          <div class="flex flex-col items-center">
            <div class="text-4xl md:text-5xl font-extrabold text-black mb-2">100+</div>
            <div class="text-gray-600 text-sm md:text-base">Empresas Ativas</div>
          </div>
          <div class="flex flex-col items-center">
            <div class="text-4xl md:text-5xl font-extrabold text-black mb-2">50K+</div>
            <div class="text-gray-600 text-sm md:text-base">Produtos Cadastrados</div>
          </div>
          <div class="flex flex-col items-center">
            <div class="text-4xl md:text-5xl font-extrabold text-black mb-2">98%</div>
            <div class="text-gray-600 text-sm md:text-base">Satisfação dos Clientes</div>
          </div>
          <div class="flex flex-col items-center">
            <div class="text-4xl md:text-5xl font-extrabold text-black mb-2">24/7</div>
            <div class="text-gray-600 text-sm md:text-base">Suporte Disponível</div>
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
        <!-- CTA intermediário -->
        <div class="mt-12 text-center">
          <a
            href="#pre-cadastro"
            class="inline-block bg-[#E1FF00] text-black font-bold rounded-lg px-10 py-4 shadow-xl hover:bg-yellow-300 transition text-lg"
          >
            Comece Agora - É Grátis!
          </a>
        </div>
      </div>
    </section>

    <!-- Benefícios Detalhados -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold text-black mb-4">
            Por que escolher a Voxy Digital?
          </h2>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Uma plataforma completa que transforma a forma como você gerencia e vende seus produtos
          </p>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition">
            <div class="bg-[#E1FF00] rounded-full p-3 w-fit mb-4">
              <svg class="h-6 w-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <h3 class="font-bold text-xl mb-2 text-black">Setup Rápido</h3>
            <p class="text-gray-600">
              Seu catálogo digital pronto em menos de 24 horas. Sem complicação, sem espera.
            </p>
          </div>
          <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition">
            <div class="bg-[#E1FF00] rounded-full p-3 w-fit mb-4">
              <svg class="h-6 w-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="font-bold text-xl mb-2 text-black">Economia Real</h3>
            <p class="text-gray-600">
              Elimine custos de impressão. Economize até 80% comparado a catálogos físicos.
            </p>
          </div>
          <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition">
            <div class="bg-[#E1FF00] rounded-full p-3 w-fit mb-4">
              <svg class="h-6 w-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
            <h3 class="font-bold text-xl mb-2 text-black">Analytics em Tempo Real</h3>
            <p class="text-gray-600">
              Veja quais produtos são mais visualizados e otimize suas vendas com dados reais.
            </p>
          </div>
          <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition">
            <div class="bg-[#E1FF00] rounded-full p-3 w-fit mb-4">
              <svg class="h-6 w-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
            </div>
            <h3 class="font-bold text-xl mb-2 text-black">100% Mobile</h3>
            <p class="text-gray-600">
              Seus clientes acessam de qualquer dispositivo. Interface otimizada para celular.
            </p>
          </div>
          <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition">
            <div class="bg-[#E1FF00] rounded-full p-3 w-fit mb-4">
              <svg class="h-6 w-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <h3 class="font-bold text-xl mb-2 text-black">Segurança Total</h3>
            <p class="text-gray-600">
              Seus dados protegidos com criptografia SSL e backups automáticos diários.
            </p>
          </div>
          <div class="bg-gray-50 rounded-xl p-6 hover:shadow-lg transition">
            <div class="bg-[#E1FF00] rounded-full p-3 w-fit mb-4">
              <svg class="h-6 w-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
            <h3 class="font-bold text-xl mb-2 text-black">Suporte Dedicado</h3>
            <p class="text-gray-600">
              Equipe especializada pronta para ajudar você a maximizar seus resultados.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Como Funciona -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16">
          <h2 class="text-3xl md:text-4xl font-bold text-black mb-4">
            Como Funciona
          </h2>
          <p class="text-lg text-gray-600 max-w-2xl mx-auto">
            Em 3 passos simples, seu catálogo digital estará no ar
          </p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
          <div class="text-center">
            <div class="bg-[#E1FF00] rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-black">
              1
            </div>
            <h3 class="font-bold text-xl mb-3 text-black">Cadastre-se</h3>
            <p class="text-gray-600">
              Preencha o formulário com os dados da sua empresa. É rápido e sem compromisso.
            </p>
          </div>
          <div class="text-center">
            <div class="bg-[#E1FF00] rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-black">
              2
            </div>
            <h3 class="font-bold text-xl mb-3 text-black">Configure seu Catálogo</h3>
            <p class="text-gray-600">
              Nossa equipe ajuda você a configurar produtos, categorias e personalizar o design.
            </p>
          </div>
          <div class="text-center">
            <div class="bg-[#E1FF00] rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6 text-3xl font-bold text-black">
              3
            </div>
            <h3 class="font-bold text-xl mb-3 text-black">Compartilhe e Venda</h3>
            <p class="text-gray-600">
              Seu catálogo fica online em menos de 24h. Compartilhe o link e comece a vender!
            </p>
          </div>
        </div>
        <div class="mt-12 text-center">
          <a
            href="#pre-cadastro"
            class="inline-block bg-[#E1FF00] text-black font-bold rounded-lg px-10 py-4 shadow-xl hover:bg-yellow-300 transition text-lg"
          >
            Começar Agora
          </a>
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
        <!-- CTA intermediário -->
        <div class="mt-8 text-center">
          <a
            href="#pre-cadastro"
            class="inline-block bg-[#E1FF00] text-black font-bold rounded-lg px-8 py-3 shadow-lg hover:bg-yellow-300 transition"
          >
            Quero Integrar com WhatsApp
          </a>
        </div>
      </div>
    </section>

    <!-- Depoimentos -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold text-black mb-4">
            O que nossos clientes dizem
          </h2>
          <p class="text-lg text-gray-600">
            Empresas que transformaram seus negócios com a Voxy Digital
          </p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
          <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4">
              <div class="flex text-[#E1FF00]">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </div>
            </div>
            <p class="text-gray-700 mb-4 italic">
              "Reduzimos 70% dos custos com impressão e nossos clientes adoram a facilidade de navegar pelo catálogo no celular. As vendas aumentaram 40%!"
            </p>
            <div class="flex items-center">
              <div class="w-12 h-12 bg-[#E1FF00] rounded-full flex items-center justify-center mr-3">
                <span class="text-black font-bold">MC</span>
              </div>
              <div>
                <div class="font-semibold text-black">Maria Costa</div>
                <div class="text-sm text-gray-600">Diretora Comercial</div>
              </div>
            </div>
          </div>
          <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4">
              <div class="flex text-[#E1FF00]">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </div>
            </div>
            <p class="text-gray-700 mb-4 italic">
              "A integração com WhatsApp foi um divisor de águas. Recebemos pedidos direto no celular e o atendimento ficou muito mais ágil. Recomendo!"
            </p>
            <div class="flex items-center">
              <div class="w-12 h-12 bg-[#E1FF00] rounded-full flex items-center justify-center mr-3">
                <span class="text-black font-bold">JS</span>
              </div>
              <div>
                <div class="font-semibold text-black">João Silva</div>
                <div class="text-sm text-gray-600">CEO, Distribuidora XYZ</div>
              </div>
            </div>
          </div>
          <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4">
              <div class="flex text-[#E1FF00]">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </div>
            </div>
            <p class="text-gray-700 mb-4 italic">
              "Os analytics nos mostraram produtos que não sabíamos que eram tão procurados. Ajustamos o estoque e as vendas explodiram!"
            </p>
            <div class="flex items-center">
              <div class="w-12 h-12 bg-[#E1FF00] rounded-full flex items-center justify-center mr-3">
                <span class="text-black font-bold">AS</span>
              </div>
              <div>
                <div class="font-semibold text-black">Ana Santos</div>
                <div class="text-sm text-gray-600">Gerente de Vendas</div>
              </div>
            </div>
          </div>
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

    <!-- FAQ -->
    <section class="py-20 bg-white">
      <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-bold text-black mb-4">
            Perguntas Frequentes
          </h2>
          <p class="text-lg text-gray-600">
            Tire suas dúvidas sobre a plataforma Voxy Digital
          </p>
        </div>
        <div class="space-y-4">
          <div class="bg-gray-50 rounded-xl p-6">
            <h3 class="font-bold text-lg text-black mb-2">
              Quanto tempo leva para ter meu catálogo pronto?
            </h3>
            <p class="text-gray-600">
              Após o cadastro, seu catálogo digital fica disponível em menos de 24 horas. Nossa equipe ajuda você a configurar tudo rapidamente.
            </p>
          </div>
          <div class="bg-gray-50 rounded-xl p-6">
            <h3 class="font-bold text-lg text-black mb-2">
              Preciso de conhecimento técnico para usar?
            </h3>
            <p class="text-gray-600">
              Não! Nossa plataforma foi desenvolvida para ser intuitiva. Qualquer pessoa consegue atualizar produtos, preços e informações facilmente.
            </p>
          </div>
          <div class="bg-gray-50 rounded-xl p-6">
            <h3 class="font-bold text-lg text-black mb-2">
              Posso personalizar o design do catálogo?
            </h3>
            <p class="text-gray-600">
              Sim! Você pode personalizar cores, logo, banners e muito mais. Temos templates prontos ou você pode criar seu próprio design.
            </p>
          </div>
          <div class="bg-gray-50 rounded-xl p-6">
            <h3 class="font-bold text-lg text-black mb-2">
              Como funciona a integração com WhatsApp?
            </h3>
            <p class="text-gray-600">
              Quando um cliente faz um pedido no catálogo, você recebe automaticamente uma mensagem no WhatsApp da sua empresa com todos os detalhes do pedido.
            </p>
          </div>
          <div class="bg-gray-50 rounded-xl p-6">
            <h3 class="font-bold text-lg text-black mb-2">
              Há limite de produtos no catálogo?
            </h3>
            <p class="text-gray-600">
              Não há limite! Você pode cadastrar quantos produtos precisar. Nossa plataforma é escalável e suporta desde pequenas lojas até grandes distribuidoras.
            </p>
          </div>
          <div class="bg-gray-50 rounded-xl p-6">
            <h3 class="font-bold text-lg text-black mb-2">
              O que acontece se eu precisar de suporte?
            </h3>
            <p class="text-gray-600">
              Oferecemos suporte via WhatsApp, e-mail e chat. Nossa equipe está sempre pronta para ajudar você a aproveitar ao máximo a plataforma.
            </p>
          </div>
        </div>
        <div class="mt-12 text-center">
          <p class="text-gray-600 mb-4">Ainda tem dúvidas?</p>
          <a
            href="https://wa.me/5513996631713"
            target="_blank"
            class="inline-block bg-[#E1FF00] text-black font-bold rounded-lg px-8 py-3 shadow-lg hover:bg-yellow-300 transition"
          >
            Fale Conosco no WhatsApp
          </a>
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
          <div class="bg-[#E1FF00] text-black px-4 py-2 rounded-lg mb-6 inline-block">
            <span class="font-bold">⚡ Setup em menos de 24h</span>
          </div>
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
          <div class="mt-6 space-y-2">
            <div class="flex items-center text-white text-sm">
              <svg class="w-5 h-5 text-[#E1FF00] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              Sem compromisso
            </div>
            <div class="flex items-center text-white text-sm">
              <svg class="w-5 h-5 text-[#E1FF00] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              Resposta em até 2 horas
            </div>
            <div class="flex items-center text-white text-sm">
              <svg class="w-5 h-5 text-[#E1FF00] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              Suporte especializado incluso
            </div>
          </div>
        </div>
        <!-- Formulário -->
        <div class="flex-1 p-8 md:p-10">
          <div class="mb-6">
            <h2 class="text-2xl font-bold mb-2 text-left text-black">
              Faça o pré-cadastro da sua empresa
            </h2>
            <p class="text-sm text-gray-600">
              Preencha o formulário e nossa equipe entrará em contato em até 2 horas
            </p>
          </div>
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
