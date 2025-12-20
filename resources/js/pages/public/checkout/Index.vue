<template>
  <div class="min-h-screen bg-gray-50">
    <Toaster />
    
    <!-- Header -->
    <div class="bg-white shadow-sm">
      <div class="max-w-4xl mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
          <button
            @click="goBack"
            class="flex items-center text-gray-600 hover:text-gray-900"
          >
            <svg
              class="w-5 h-5 mr-2"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M10 19l-7-7m0 0l7-7m-7 7h18"
              />
            </svg>
            Voltar
          </button>
          <h1 class="text-xl font-bold">Finalizar Pedido</h1>
          <div class="w-20"></div>
        </div>
      </div>
    </div>

    <!-- Stepper -->
    <div class="max-w-4xl mx-auto px-4 py-6">
      <div class="flex items-center justify-center mb-8 gap-2 sm:gap-4">
        <template v-for="(step, index) in steps" :key="index">
          <!-- Step Item -->
          <div class="flex flex-col items-center">
            <div
              class="w-10 h-10 sm:w-12 sm:h-12 rounded-full flex items-center justify-center font-semibold transition-all shadow-sm"
              :class="
                currentStep > index
                  ? 'bg-green-500 text-white'
                  : currentStep === index
                  ? 'bg-primary text-white'
                  : 'bg-gray-200 text-gray-500'
              "
            >
              <svg
                v-if="currentStep > index"
                class="w-5 h-5 sm:w-6 sm:h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M5 13l4 4L19 7"
                />
              </svg>
              <span v-else class="text-sm sm:text-base">{{ index + 1 }}</span>
            </div>
            <span
              class="text-xs sm:text-sm mt-2 text-center max-w-[80px] sm:max-w-none"
              :class="currentStep >= index ? 'text-gray-900 font-medium' : 'text-gray-500'"
            >
              {{ step }}
            </span>
          </div>
          
          <!-- Connecting Line -->
          <div
            v-if="index < steps.length - 1"
            class="w-12 sm:w-24 md:w-32 h-1 mb-8"
            :class="currentStep > index ? 'bg-green-500' : 'bg-gray-200'"
          ></div>
        </template>
      </div>

      <!-- Step Content -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <!-- Step 1: Revisar Pedido -->
        <div v-if="currentStep === 0">
          <h2 class="text-2xl font-bold mb-6">Revise seu Pedido</h2>
          
          <div v-if="cartItems.length === 0" class="text-center py-8">
            <p class="text-gray-500">Seu carrinho está vazio</p>
            <Button @click="goBack" class="mt-4">Voltar às compras</Button>
          </div>

          <div v-else>
            <div class="space-y-4 mb-6">
              <div
                v-for="item in cartItems"
                :key="`${item.id}-${item.variation?.id || 'no-variation'}`"
                class="flex gap-4 p-4 border rounded-lg"
              >
                <img
                  :src="item.imageUrl != '/storage/null' ? item.imageUrl : '/storage/not_found.jpg'"
                  :alt="item.name"
                  class="w-20 h-20 object-cover rounded"
                />
                <div class="flex-1">
                  <h3 class="font-semibold">{{ item.name }}</h3>
                  <p v-if="item.variation" class="text-sm text-gray-600">
                    Tamanho: {{ item.variation.size }}
                  </p>
                  <p class="text-sm text-gray-600">
                    Quantidade: {{ item.quantity }}
                  </p>
                  <p class="font-semibold text-primary mt-2">
                    {{ formatCurrency((item.sale || item.price) * item.quantity) }}
                  </p>
                </div>
              </div>
            </div>

            <div class="border-t pt-4 space-y-2">
              <div class="flex justify-between text-gray-600">
                <span>Subtotal:</span>
                <span>{{ formatCurrency(subtotal) }}</span>
              </div>
              <div v-if="taxFixed > 0" class="flex justify-between text-gray-600">
                <span>Taxa de Entrega:</span>
                <span>{{ formatCurrency(taxFixed) }}</span>
              </div>
              <div class="flex justify-between text-lg font-bold text-primary">
                <span>Total:</span>
                <span>{{ formatCurrency(total) }}</span>
              </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
              <Button variant="outline" @click="goBack">Cancelar</Button>
              <Button @click="nextStep">Continuar</Button>
            </div>
          </div>
        </div>

        <!-- Step 2: Informações do Comprador -->
        <div v-if="currentStep === 1">
          <h2 class="text-2xl font-bold mb-6">Informações do Comprador</h2>

          <form @submit.prevent="nextStep" class="space-y-4">
            <div>
              <label for="customer_name" class="block text-sm font-medium mb-1">
                Nome Completo*
              </label>
              <input
                id="customer_name"
                v-model="customerInfo.name"
                type="text"
                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                :class="{ 'border-red-500': errors.name }"
                placeholder="Digite seu nome completo"
              />
              <p v-if="errors.name" class="text-red-500 text-xs mt-1">
                {{ errors.name }}
              </p>
            </div>

            <div>
              <label for="customer_email" class="block text-sm font-medium mb-1">
                Email*
              </label>
              <input
                id="customer_email"
                v-model="customerInfo.email"
                type="email"
                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                :class="{ 'border-red-500': errors.email }"
                placeholder="email@example.com"
              />
              <p v-if="errors.phone" class="text-red-500 text-xs mt-1">
                {{ errors.email }}
              </p>
            </div>

            <div>
              <label for="customer_phone" class="block text-sm font-medium mb-1">
                Telefone/WhatsApp*
              </label>
              <input
                id="customer_phone"
                v-model="customerInfo.phone"
                type="tel"
                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                :class="{ 'border-red-500': errors.phone }"
                placeholder="(00) 00000-0000"
              />
              <p v-if="errors.phone" class="text-red-500 text-xs mt-1">
                {{ errors.phone }}
              </p>
            </div>

            <div>
              <label for="delivery_address" class="block text-sm font-medium mb-1">
                Endereço de Entrega*
              </label>
              <textarea
                id="delivery_address"
                v-model="customerInfo.address"
                rows="3"
                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                :class="{ 'border-red-500': errors.address }"
                placeholder="Rua, número, bairro, cidade, CEP"
              ></textarea>
              <p v-if="errors.address" class="text-red-500 text-xs mt-1">
                {{ errors.address }}
              </p>
            </div>

            <div>
              <label for="delivery_note" class="block text-sm font-medium mb-1">
                Observações (opcional)
              </label>
              <textarea
                id="delivery_note"
                v-model="customerInfo.note"
                rows="2"
                class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent"
                placeholder="Ponto de referência, complemento, etc."
              ></textarea>
            </div>

            <div class="flex justify-between gap-3 mt-6">
              <Button type="button" variant="outline" @click="previousStep">
                Voltar
              </Button>
              <Button type="submit">
                {{ hasActivePaymentGateways ? 'Continuar' : 'Finalizar Pedido' }}
              </Button>
            </div>
          </form>
        </div>

        <!-- Step 3: Forma de Pagamento -->
        <div v-if="currentStep === 2 && hasActivePaymentGateways && !pixData">
          <h2 class="text-2xl font-bold mb-6">Forma de Pagamento</h2>

          <!-- Informação sobre PIX -->
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
              <svg class="w-12 h-12 text-blue-600 flex-shrink-0" viewBox="0 0 512 512" fill="currentColor">
                <path d="M242.4 292.5C247.8 287.1 257.1 287.1 262.5 292.5L339.5 369.5C353.7 383.7 372.6 391.5 392.6 391.5H407.7L310.6 488.6C280.3 518.1 231.1 518.1 200.8 488.6L103.3 391.5H112.6C132.6 391.5 151.5 383.7 165.7 369.5L242.4 292.5zM262.5 218.9C257.1 224.3 247.8 224.3 242.4 218.9L165.7 142.1C151.5 127.9 132.6 120.1 112.6 120.1H103.3L200.7 22.73C231.1-7.58 280.3-7.58 310.6 22.73L407.7 120.1H392.6C372.6 120.1 353.7 127.9 339.5 142.1L262.5 218.9zM112.6 142.1C126.4 142.1 139.1 148.3 149.7 158.1L226.4 236.1C239.9 250.1 261.9 250.1 275.4 236.1L352.1 158.1C362.7 148.3 375.4 142.1 389.2 142.1H431.5C441.1 142.1 450.1 148.3 455.1 157.5C460.9 166.7 460.9 178.1 455.1 187.3L350.5 330.1C340.9 339.7 328.2 344.1 314.4 344.1C300.6 344.1 287.9 339.7 278.3 330.1L201.6 252.1C188.1 238.1 166.1 238.1 152.6 252.1L75.88 330.1C66.24 339.7 53.57 344.1 39.76 344.1C25.95 344.1 13.28 339.7 3.645 330.1L56.95 187.3C51.05 178.1 51.05 166.7 56.95 157.5C61.95 148.3 70.95 142.1 80.55 142.1H112.6zM314.4 369.1C328.2 369.1 340.9 375.1 350.5 384.7L455.1 527.5C460.9 536.7 460.9 548.1 455.1 557.3C450.1 566.5 441.1 572.7 431.5 572.7H389.2C375.4 572.7 362.7 567.5 352.1 557.7L275.4 479.7C261.9 465.7 239.9 465.7 226.4 479.7L149.7 557.7C139.1 567.5 126.4 572.7 112.6 572.7H80.55C70.95 572.7 61.95 566.5 56.95 557.3C51.05 548.1 51.05 536.7 56.95 527.5L152.6 384.7C162.2 375.1 174.9 369.1 188.7 369.1C202.5 369.1 215.2 375.1 224.8 384.7L301.5 462.7C315 476.7 337 476.7 350.5 462.7L427.2 384.7C436.8 375.1 449.5 369.1 463.3 369.1H505.6C515.2 369.1 524.2 375.3 529.2 384.5C534.2 393.7 534.2 405.1 529.2 414.3L424.8 557.7C415.2 567.5 402.5 572.7 388.7 572.7H314.4z"/>
              </svg>
              <div class="flex-1">
                <p class="font-medium text-blue-900 text-lg">Pagamento via PIX</p>
                <p class="text-sm text-blue-700 mt-1">
                  Após confirmar o pedido, você receberá o QR Code do PIX para realizar o pagamento.
                  O pagamento é processado instantaneamente.
                </p>
              </div>
            </div>
          </div>

          <!-- Mensagem de erro do pagamento -->
          <div v-if="paymentError" class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-red-800">
              <strong>Erro:</strong> {{ paymentError }}
            </p>
          </div>

          <!-- Resumo do pedido -->
          <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-semibold mb-3">Resumo do Pedido</h3>
            <div class="space-y-2 text-sm">
              <div class="flex justify-between">
                <span>Subtotal:</span>
                <span>{{ formatCurrency(subtotal) }}</span>
              </div>
              <div v-if="taxFixed > 0" class="flex justify-between">
                <span>Taxa de Entrega:</span>
                <span>{{ formatCurrency(taxFixed) }}</span>
              </div>
              <div class="flex justify-between font-bold text-base text-primary pt-2 border-t">
                <span>Total a Pagar:</span>
                <span>{{ formatCurrency(total) }}</span>
              </div>
            </div>
          </div>

          <div class="flex justify-between gap-3 mt-6">
            <Button type="button" variant="outline" @click="previousStep">
              Voltar
            </Button>
            <Button 
              @click="submitOrder" 
              :disabled="isSubmitting"
            >
              <span v-if="isSubmitting">Processando...</span>
              <span v-else>Gerar PIX</span>
            </Button>
          </div>
        </div>

        <!-- Step 3.5: Tela do QR Code PIX -->
        <div v-if="currentStep === 2 && hasActivePaymentGateways && pixData">
          <div class="text-center">
            <div v-if="pixData.payment_status === 'approved'" class="mb-6">
              <div class="w-20 h-20 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
              </div>
              <h2 class="text-2xl font-bold text-green-600 mb-2">Pagamento Confirmado!</h2>
              <p class="text-gray-600 mb-4">
                Seu pagamento foi aprovado com sucesso. Em instantes você será redirecionado...
              </p>
            </div>

            <div v-else>
              <h2 class="text-2xl font-bold mb-2">Pague com PIX</h2>
              <p class="text-gray-600 mb-6">
                Escaneie o QR Code abaixo ou copie o código PIX para realizar o pagamento
              </p>

              <!-- QR Code -->
              <div class="bg-white p-6 rounded-lg shadow-sm border mb-6 inline-block">
                <img 
                  v-if="pixData.qr_code_base64" 
                  :src="`data:image/png;base64,${pixData.qr_code_base64}`" 
                  alt="QR Code PIX"
                  class="w-64 h-64 mx-auto"
                />
                <div v-else class="w-64 h-64 bg-gray-200 animate-pulse rounded"></div>
              </div>

              <!-- Código PIX Copia e Cola -->
              <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <label class="block text-sm font-medium mb-2 text-left">
                  Ou copie o código PIX:
                </label>
                <div class="flex gap-2">
                  <input
                    :value="pixData.qr_code"
                    readonly
                    class="flex-1 border rounded-lg px-4 py-2 text-sm font-mono bg-white"
                  />
                  <Button 
                    @click="copyPixCode" 
                    variant="outline"
                    class="whitespace-nowrap"
                  >
                    <svg v-if="!codeCopied" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    <svg v-else class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ codeCopied ? 'Copiado!' : 'Copiar' }}
                  </Button>
                </div>
              </div>

              <!-- Valor a pagar -->
              <div class="bg-primary/10 rounded-lg p-4 mb-6">
                <p class="text-sm text-gray-600 mb-1">Valor a pagar</p>
                <p class="text-3xl font-bold text-primary">{{ formatCurrency(total) }}</p>
              </div>

              <!-- Status do pagamento -->
              <div class="flex items-center justify-center gap-2 text-gray-600 mb-4">
                <div class="animate-spin w-5 h-5 border-2 border-primary border-t-transparent rounded-full"></div>
                <span>Aguardando pagamento...</span>
              </div>

              <p class="text-sm text-gray-500 mb-6">
                Assim que o pagamento for confirmado, você será notificado automaticamente.
              </p>
            </div>

            <Button 
              @click="goBack" 
              variant="outline"
              class="mt-4"
            >
              Voltar para a loja
            </Button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Toaster } from '@/components/ui/sonner';
import { toast } from 'vue-sonner';
import axios from 'axios';

const { props } = usePage();
const tenant = computed(() => props.tenant);
const paymentGateways = computed(() => props.paymentGateways);
const taxFixed = computed(() => Number(tenant.value?.tax_fixed || 0));

// Verificar se há paymentGateways ativos
const hasActivePaymentGateways = computed(() => {
  const gateways = paymentGateways.value;
  if (!gateways) return false;
  if (Array.isArray(gateways)) {
    return gateways.length > 0;
  }
  // Se for um objeto/coleção, verificar se tem itens
  return Object.keys(gateways).length > 0;
});

// Steps dinâmicos baseados na presença de paymentGateways
const steps = computed(() => {
  const baseSteps = ['Pedido', 'Seus Dados'];
  if (hasActivePaymentGateways.value) {
    baseSteps.push('Pagamento');
  }
  return baseSteps;
});
const currentStep = ref(0);

const cartItems = ref<any[]>([]);
const customerInfo = ref({
  name: '',
  phone: '',
  email: '',
  address: '',
  note: ''
});

const errors = ref({
  name: '',
  phone: '',
  email: '',
  address: '',
  note: ''
});

const isSubmitting = ref(false);
const paymentError = ref('');
const paymentMethod = ref<'pix'>('pix'); // Apenas PIX
const pixData = ref<any>(null);
const codeCopied = ref(false);
const pollingInterval = ref<any>(null);

// Carregar carrinho do localStorage
onMounted(async () => {
  const savedCart = localStorage.getItem('cart');
  if (savedCart) {
    const parsedCart = JSON.parse(savedCart);
    cartItems.value = parsedCart.map((item: any) => ({
      ...item,
      imageUrl: `/storage/${item.uri}`
    }));
  }

  // Se o carrinho estiver vazio, redireciona de volta
  if (cartItems.value.length === 0) {
    toast.error('Seu carrinho está vazio');
    router.visit(route('home'));
  }
});

// Limpar polling quando componente for desmontado
onUnmounted(() => {
  if (pollingInterval.value) {
    clearInterval(pollingInterval.value);
  }
});

const subtotal = computed(() => {
  return cartItems.value.reduce((total, item) => {
    const price = item.sale && item.sale > 0 ? item.sale : item.price;
    return total + (Number(price) * Number(item.quantity || 1));
  }, 0);
});

const total = computed(() => subtotal.value + taxFixed.value);

const formatCurrency = (value: number) => {
  return `R$ ${(value / 100).toLocaleString('pt-BR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  })}`;
};

const goBack = () => {
  router.visit(route('home'));
};

const nextStep = async () => {
  if (currentStep.value === 1) {
    if (!validateCustomerInfo()) {
      return;
    }
    
    // Se não houver paymentGateways ativos, finalizar pedido no step 2
    if (!hasActivePaymentGateways.value) {
      await submitOrder();
      return;
    }
  }
  
  if (currentStep.value < steps.value.length - 1) {
    currentStep.value++;
  }
};

const previousStep = () => {
  if (currentStep.value > 0) {
    currentStep.value--;
  }
};

const clearCartAndRedirect = () => {
  // Limpar localStorage
  localStorage.removeItem('cart');
  
  // Limpar estado local
  cartItems.value = [];
  
  // Redirecionar para home
  setTimeout(() => {
    router.visit(route('home'));
  }, 2000);
};

const validateCustomerInfo = () => {
  let valid = true;
  errors.value = { name: '', phone: '', email: '', address: '', note: '' };

  if (!customerInfo.value.name.trim()) {
    errors.value.name = 'Por favor, insira seu nome';
    valid = false;
  }

  if (!customerInfo.value.email.trim()) {
    errors.value.email = 'Por favor, insira seu email';
    valid = false;
  } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(customerInfo.value.email)) {
    errors.value.email = 'Email inválido';
    valid = false;
  }

  if (!customerInfo.value.phone.trim()) {
    errors.value.phone = 'Por favor, insira um telefone válido';
    valid = false;
  } else if (!/^\d{10,11}$/.test(customerInfo.value.phone.replace(/\D/g, ''))) {
    errors.value.phone = 'Telefone inválido (DDD + número)';
    valid = false;
  }

  if (!customerInfo.value.address.trim()) {
    errors.value.address = 'Por favor, insira o endereço de entrega';
    valid = false;
  }

  return valid;
};


const copyPixCode = async () => {
  if (pixData.value?.qr_code) {
    try {
      await navigator.clipboard.writeText(pixData.value.qr_code);
      codeCopied.value = true;
      toast.success('Código PIX copiado!');
      setTimeout(() => {
        codeCopied.value = false;
      }, 3000);
    } catch (error) {
      toast.error('Erro ao copiar código');
    }
  }
};

const startPollingPaymentStatus = (orderId: number) => {
  // Verificar status a cada 3 segundos
  pollingInterval.value = setInterval(async () => {
    try {
      const response = await axios.get(route('orders.check-payment', { order: orderId }));
      
      if (response.data.payment_status === 'approved') {
        clearInterval(pollingInterval.value);
        pixData.value.payment_status = 'approved';
        
        toast.success('Pagamento confirmado!', {
          description: 'Seu pedido foi aprovado com sucesso.',
          duration: 3000,
        });
        
        // Limpar carrinho e redirecionar após 2 segundos
        setTimeout(() => {
          clearCartAndRedirect();
        }, 2000);
      }
    } catch (error) {
      console.error('Erro ao verificar status do pagamento:', error);
    }
  }, 3000);
};

const submitOrder = async () => {
  if (!validateCustomerInfo()) {
    currentStep.value = 1;
    toast.error('Por favor, preencha todos os dados obrigatórios');
    return;
  }

  isSubmitting.value = true;
  paymentError.value = '';

  try {
    // Enviar para o backend
    const payload: any = {
      tenant_id: tenant.value?.id,
      customer_name: customerInfo.value.name,
      customer_phone: customerInfo.value.phone,
      customer_email: customerInfo.value.email,
      delivery_address: customerInfo.value.address,
      note: customerInfo.value.note,
      tax_fixed: taxFixed.value,
      total: total.value,
      payment_method: paymentMethod.value,
      items: cartItems.value.map((item) => ({
        product_id: item.id,
        quantity: item.quantity,
        price: item.sale ?? item.price,
        variation_id: item.variation?.id
      }))
    };

    console.log('Enviando pedido para o backend:', payload);

    const response = await axios.post(route('orders.store'), payload);

    // Verificar se o pagamento foi processado
    const paymentProcessed = response.data.payment_processed ?? true;
    
    if (paymentProcessed && response.data.data.qr_code) {
      // Pagamento processado - armazenar dados do PIX
      pixData.value = response.data.data;
      
      // Iniciar polling para verificar pagamento
      startPollingPaymentStatus(response.data.data.order_id);

      toast.success('QR Code gerado com sucesso!', {
        description: 'Escaneie o código ou copie para realizar o pagamento.',
      });
    } else {
      // Pagamento não processado - pedido criado mas sem processar pagamento
      toast.success('Pedido criado com sucesso!', {
        description: response.data.message || 'Seu pedido foi registrado e será processado em breve.',
      });
      
      // Limpar carrinho e redirecionar
      setTimeout(() => {
        clearCartAndRedirect();
      }, 2000);
    }

    isSubmitting.value = false;
  } catch (error: any) {
    isSubmitting.value = false;
    console.error('Erro ao processar pedido:', error);
    
    // Extrair mensagem de erro do axios
    const errorMessage = error.response?.data?.message || error.message || 'Erro ao processar pedido';
    
    paymentError.value = errorMessage;
    toast.error('Erro ao processar pedido', {
      description: errorMessage
    });
  }
};
</script>

<style scoped>
/* Custom styles if needed */
</style>
