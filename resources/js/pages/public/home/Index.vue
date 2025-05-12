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
          src="https://placehold.co/100"
          alt="Logo da Loja"
          class="rounded-full mb-2"
        />
        <h1 class="text-3xl font-bold">Loja Exemplo</h1>
        <p class="text-sm">Contato: (11) 99999-9999 | Seg a Sex: 08h - 18h</p>
      </div>
    </div>

    <!-- Botão Carrinho Fixo (só mostra quando o carrinho estiver fechado) -->
    <div class="fixed bottom-4 right-4 z-50" v-if="!isCartOpen">
      <button
        @click="isCartOpen = true"
        class="bg-primary text-white px-4 py-2 rounded-full shadow-lg"
      >
        🛒 Carrinho ({{ cart.length }})
      </button>
    </div>

    <!-- Main Content -->
    <div class="p-4 md:p-6 lg:p-10">
      <!-- Categorias com slider -->
      <div class="mb-6">
        <h2 class="text-xl font-semibold mb-2">Categorias</h2>
        <div class="flex overflow-x-auto gap-4">
          <div
            @click="filterByCategory(null)"
            :class="{
              'bg-primary text-white': selectedCategory === null,
              'bg-muted hover:bg-primary/10 text-muted-foreground':
                selectedCategory !== null,
            }"
            class="flex-shrink-0 px-4 py-2 rounded-lg cursor-pointer transition-colors whitespace-nowrap"
          >
            Todos os Itens
          </div>
          <div
            v-for="category in categories"
            :key="category.id"
            @click="filterByCategory(category.id)"
            :class="{
              'bg-primary text-white': category.id === selectedCategory,
              'bg-muted hover:bg-primary/10 text-muted-foreground':
                category.id !== selectedCategory,
            }"
            class="flex-shrink-0 px-4 py-2 rounded-lg cursor-pointer transition-colors whitespace-nowrap"
          >
            {{ category.name }}
          </div>
        </div>
      </div>

      <!-- Produtos por categoria com slider -->
      <div class="mb-10" v-for="category in categories" :key="category.id">
        <template v-if="selectedCategory === null || selectedCategory === category.id">
          <h3 class="text-lg font-bold mb-3">{{ category.name }}</h3>
          <div class="flex overflow-x-auto gap-4">
            <Card
              v-for="product in products.filter((p) => p.category === category.id)"
              :key="product.id"
              class="w-64 flex-shrink-0 hover:shadow-lg transition-shadow"
            >
              <CardHeader>
                <img
                  :src="product.image"
                  :alt="product.name"
                  class="rounded-md w-full h-40 object-cover"
                />
              </CardHeader>
              <CardContent>
                <h3 class="text-md font-semibold truncate">{{ product.name }}</h3>
                <p class="text-primary font-bold">R$ {{ product.price.toFixed(2) }}</p>
              </CardContent>
              <CardFooter>
                <Button variant="default" class="w-full" @click="addToCart(product)">
                  Adicionar ao Carrinho
                </Button>
              </CardFooter>
            </Card>
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
                :src="item.image"
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
                      (item.price * item.quantity).toLocaleString("pt-BR", {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2,
                      })
                    }}
                  </p>
                </div>
              </div>
            </li>
          </ul>

          <div class="mb-4">
            <label for="obs" class="block text-sm font-medium mb-1"
              >Observações do Pedido</label
            >
            <textarea
              id="obs"
              v-model="orderNote"
              rows="3"
              placeholder="Ex: Entregar após às 18h"
              class="w-full border border-gray-300 rounded px-3 py-2"
            ></textarea>
          </div>

          <div class="mb-4 text-right text-lg">
            <p>
              Subtotal: R$
              {{
                cartTotal.toLocaleString("pt-BR", {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2,
                })
              }}
            </p>
            <p v-if="discountValue > 0" class="text-green-600">
              Desconto: - R$
              {{
                discountValue.toLocaleString("pt-BR", {
                  minimumFractionDigits: 2,
                  maximumFractionDigits: 2,
                })
              }}
            </p>
            <p class="font-bold text-primary mt-2">
              Total: R$
              {{
                (cartTotal - discountValue).toLocaleString("pt-BR", {
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
</template>

<script setup lang="ts">
import { Card, CardHeader, CardContent, CardFooter } from "@/components/ui/card";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { ref, computed } from "vue";

const selectedCategory = ref(null);
const cart = ref([]);
const isCartOpen = ref(false);
const discountCode = ref("");
const orderNote = ref("");

const categories = ref([
  { id: 1, name: "Eletrônicos" },
  { id: 2, name: "Roupas" },
  { id: 3, name: "Livros" },
]);

const products = ref([
  {
    id: 1,
    name: "Smartphone",
    price: 1999.99,
    category: 1,
    image: "https://placehold.co/400",
  },
  {
    id: 2,
    name: "Camiseta",
    price: 49.99,
    category: 2,
    image: "https://placehold.co/400",
  },
  {
    id: 3,
    name: "Livro de Programação",
    price: 89.99,
    category: 3,
    image: "https://placehold.co/400",
  },
  {
    id: 4,
    name: "Notebook",
    price: 3299.99,
    category: 1,
    image: "https://placehold.co/400",
  },
  {
    id: 5,
    name: "Fone Bluetooth",
    price: 199.99,
    category: 1,
    image: "https://placehold.co/400",
  },
  {
    id: 6,
    name: "Calça Jeans",
    price: 129.99,
    category: 2,
    image: "https://placehold.co/400",
  },
  {
    id: 7,
    name: "Vestido Floral",
    price: 159.99,
    category: 2,
    image: "https://placehold.co/400",
  },
  {
    id: 8,
    name: "Livro de Marketing",
    price: 99.9,
    category: 3,
    image: "https://placehold.co/400",
  },
  {
    id: 9,
    name: "Tablet Android",
    price: 1499.99,
    category: 1,
    image: "https://placehold.co/400",
  },
  {
    id: 10,
    name: "Camisa Polo",
    price: 89.99,
    category: 2,
    image: "https://placehold.co/400",
  },
]);

const filterByCategory = (categoryId) => {
  selectedCategory.value = categoryId;
};

const addToCart = (product) => {
  const existing = cart.value.find((item) => item.id === product.id);
  if (existing) {
    existing.quantity++;
  } else {
    cart.value.push({ ...product, quantity: 1 });
  }
};

const removeFromCart = (productId) => {
  cart.value = cart.value.filter((item) => item.id !== productId);
};

const getCategoryName = (categoryId) => {
  const category = categories.value.find((cat) => cat.id === categoryId);
  return category ? category.name : "Desconhecida";
};

const cartTotal = computed(() => {
  return cart.value.reduce((total, item) => total + item.price * item.quantity, 0);
});

const discountValue = computed(() => {
  if (discountCode.value === "DESCONTO10") {
    return cartTotal.value * 0.1;
  }
  return 0;
});

const finalizarPedido = () => {
  const resumo = cart.value.map((item) => `${item.name} (x${item.quantity})`).join(", ");
  const mensagem = `Olá, gostaria de fazer um pedido: ${resumo}. Total: R$ ${(
    cartTotal.value - discountValue.value
  ).toFixed(2)}. Observações: ${orderNote.value}`;
  const url = `https://wa.me/5511999999999?text=${encodeURIComponent(mensagem)}`;
  window.open(url, "_blank");

  cart.value = [];
  discountCode.value = "";
  orderNote.value = "";
  isCartOpen.value = false;
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
