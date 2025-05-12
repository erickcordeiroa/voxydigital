<template>
  <div>
    <!-- Cover Section -->
    <div
      class="relative h-64 w-full bg-cover bg-center"
      style="background-image: url('https://placehold.co/1200x300')"
    >
      <div
        class="absolute inset-0 bg-black bg-opacity-50 flex flex-col items-center justify-center text-white"
      >
        <img
          :src="`/storage/${tenant.logo}`"
          alt="Logo da Loja"
          class="rounded-full mb-2 w-26 h-26"
        />
        <h1 class="text-3xl font-bold">{{ tenant.name }}</h1>
        <p class="text-sm">
          Contato: {{ formatPhoneNumber(tenant.whatsapp) }} | Seg a Sex: 08h - 18h
        </p>
      </div>
    </div>

    <!-- Botão Carrinho Fixo (só mostra quando o carrinho estiver fechado) -->
    <div class="fixed bottom-4 right-4 z-50" v-if="!isCartOpen">
      <button
        @click="isCartOpen = true"
        class="bg-primary text-white px-4 py-2 rounded-full shadow-lg cursor-pointer"
      >
        🛒 Carrinho ({{ cart.length }})
      </button>
    </div>

    <!-- Main Content -->
    <div class="p-4 md:p-6 lg:p-10">
      <!-- Categorias com slider -->
      <CategorySlider
        :categories="categories"
        :selectedCategory="selectedCategory"
        @categorySelect="filterByCategory"
      />

      <!-- Produtos por categoria com slider -->
      <div class="mb-8" v-for="category in categories" :key="category.id">
        <template v-if="selectedCategory === null || selectedCategory === category.id">
          <h3 class="text-lg font-bold mb-3">{{ category.name }}</h3>
          <div class="flex overflow-x-auto gap-4">
            <ProductCard
              v-for="product in filteredProductsByCategory(category.id)"
              :key="product.id"
              :product="product"
              :addToCart="addToCart"
            />
          </div>
        </template>
      </div>
    </div>

    <!-- Sidebar Carrinho -->
    <div
      v-if="isCartOpen"
      class="fixed inset-0 bg-black bg-opacity-40 z-40"
      @click.self="isCartOpen = false"
    >
      <div
        class="absolute right-0 top-0 w-full max-w-lg bg-white h-full shadow-xl p-6 overflow-y-auto"
      >
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold">Seu Carrinho</h2>
          <button @click="isCartOpen = false" class="text-gray-500 hover:text-gray-700">
            ✖
          </button>
        </div>

        <div v-if="cart.length">
          <ul
            class="divide-y divide-gray-200 mb-4 max-h-[calc(100vh-400px)] overflow-y-auto"
          >
            <li v-for="item in cart" :key="item.id" class="py-4 flex gap-4">
              <img
                :src="`/storage/${item.uri}`"
                alt="imagem do produto"
                class="w-16 h-16 rounded object-cover"
              />
              <div class="flex-1 min-w-0">
                <div class="flex justify-between items-start gap-2">
                  <div class="min-w-0">
                    <h4 class="font-semibold text-md truncate">{{ item.name }}</h4>
                    <p class="text-sm text-muted-foreground truncate">
                      {{ getCategoryName(item.category) }}
                    </p>
                    <p>
                      <button
                        class="text-red-400 text-sm cursor-pointer whitespace-nowrap"
                        @click="removeFromCart(item.id)"
                      >
                        Remover
                      </button>
                    </p>
                  </div>
                  <div class="flex items-center gap-2 mt-2 flex-shrink-0">
                    <button
                      @click="item.quantity--"
                      :disabled="item.quantity <= 1"
                      class="px-2 py-1 bg-gray-200 rounded"
                    >
                      -
                    </button>
                    <span class="whitespace-nowrap">{{ item.quantity }}</span>
                    <button
                      @click="item.quantity++"
                      class="px-2 py-1 bg-gray-200 rounded"
                    >
                      +
                    </button>
                  </div>
                  <p class="mt-2 font-semibold whitespace-nowrap">
                    R$
                    {{
                      (
                        ((item.sale ? item.sale : item.price) * item.quantity) /
                        100
                      ).toLocaleString("pt-BR", {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                      })
                    }}
                  </p>
                </div>
              </div>
            </li>
          </ul>

          <div class="mb-4 text-right text-lg">
            <p>
              Subtotal: R$
              {{
                (cartTotal / 100).toLocaleString("pt-BR", {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2,
                })
              }}
            </p>
            <p class="font-bold text-primary mt-2">
              Total: R$
              {{
                (cartTotal / 100).toLocaleString("pt-BR", {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2,
                })
              }}
            </p>
          </div>

          <Button variant="default" class="w-full" @click="finalizarPedido">
            Realizar Pedido
          </Button>
        </div>

        <div v-else class="text-muted-foreground">Seu carrinho está vazio.</div>
      </div>
    </div>
  </div>

  <!-- Modal de Confirmação de Pedido -->
  <div
    v-if="showOrderConfirmation"
    class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center"
  >
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
      <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Confirmar Pedido</h2>
        <button
          @click="showOrderConfirmation = false"
          class="text-gray-500 hover:text-gray-700"
        >
          ✖
        </button>
      </div>

      <div class="space-y-4">
        <div>
          <label for="customer_name" class="block text-sm font-medium mb-1"
            >Nome para contato</label
          >
          <input
            id="customer_name"
            v-model="customerInfo.name"
            type="text"
            class="w-full border border-gray-300 rounded px-3 py-2"
            required
          />
        </div>

        <div>
          <label for="customer_phone" class="block text-sm font-medium mb-1"
            >Telefone</label
          >
          <input
            id="customer_phone"
            v-model="customerInfo.phone"
            type="tel"
            class="w-full border border-gray-300 rounded px-3 py-2"
            required
          />
        </div>

        <div>
          <label for="delivery_address" class="block text-sm font-medium mb-1"
            >Endereço de entrega</label
          >
          <textarea
            id="delivery_address"
            v-model="customerInfo.address"
            rows="3"
            class="w-full border border-gray-300 rounded px-3 py-2"
            required
          ></textarea>
        </div>

        <div>
          <label for="delivery_note" class="block text-sm font-medium mb-1"
            >Observações adicionais</label
          >
          <textarea
            id="delivery_note"
            v-model="customerInfo.note"
            rows="2"
            class="w-full border border-gray-300 rounded px-3 py-2"
          ></textarea>
        </div>

        <div class="flex justify-end gap-2 pt-4">
          <Button variant="outline" @click="showOrderConfirmation = false"
            >Cancelar</Button
          >
          <Button @click="submitOrder">Confirmar Pedido</Button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import CategorySlider from "@/components/home/CategorySlider.vue";
import ProductCard from "@/components/home/ProductCard.vue";
import { Button } from "@/components/ui/button";
import { router } from "@inertiajs/vue3";

const { props } = usePage();
const categories = ref(props.categories);
const products = ref(props.products);
const tenant = ref(props.tenant);

const selectedCategory = ref(null);
const cart = ref([]);
const isCartOpen = ref(false);
const orderNote = ref("");

const filterByCategory = (categoryId) => {
  selectedCategory.value = categoryId;
};

const filteredProductsByCategory = (categoryId) => {
  return products.value.filter(
    (product) =>
      product.category_id === categoryId &&
      (!selectedCategory.value || product.category_id === selectedCategory.value)
  );
};

const addToCart = (product) => {
  const existing = cart.value.find((item) => item.id === product.id);
  if (existing) {
    existing.quantity++;
  } else {
    cart.value.push({ ...product, quantity: 1 });
  }
};

const formatPhoneNumber = (whatsapp) => {
  const cleaned = whatsapp.replace(/\D/g, "");
  const match = cleaned.match(/^(\d{2})(\d{4,5})(\d{4})$/);
  if (match) {
    return `(${match[1]}) ${match[2]}-${match[3]}`;
  }
  return whatsapp;
};

const removeFromCart = (productId) => {
  cart.value = cart.value.filter((item) => item.id !== productId);
};

const getCategoryName = (categoryId) => {
  const category = categories.value.find((cat) => cat.id === categoryId);
  return category ? category.name : "Desconhecida";
};

const cartTotal = computed(() => {
  return cart.value.reduce(
    (total, item) => total + (item.sale ? item.sale : item.price) * item.quantity,
    0
  );
});

// Finalização do pedido
const showOrderConfirmation = ref(false);
const customerInfo = ref({
  name: "",
  phone: "",
  address: "",
  note: "",
});

const finalizarPedido = () => {
  if (!cart.value.length) return;
  showOrderConfirmation.value = true;
};

const submitOrder = async () => {
  const orderData = {
    tenant_id: tenant.value.id,
    customer_name: customerInfo.value.name,
    customer_phone: customerInfo.value.phone,
    delivery_address: customerInfo.value.address,
    note: customerInfo.value.note || orderNote.value,
    items: cart.value.map((item) => ({
      product_id: item.id,
      quantity: item.quantity,
      price: item.sale ? item.sale : item.price,
    })),
    total: cartTotal.value,
  };

  router.post("/orders", orderData);

  // Limpar carrinho e mostrar mensagem de sucesso
  cart.value = [];
  orderNote.value = "";
  customerInfo.value = {
    name: "",
    phone: "",
    address: "",
    note: "",
  };
  isCartOpen.value = false;
  showOrderConfirmation.value = false;
};
</script>

<style scoped>
::-webkit-scrollbar {
  height: 6px;
  width: 6px;
}
::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.2);
  border-radius: 4px;
}
</style>
