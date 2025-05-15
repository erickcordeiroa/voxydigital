<template>
  <Toaster />
  <div class="min-h-screen bg-white flex flex-col">
    <!-- Header com botão voltar e botão do carrinho -->
    <header class="sticky top-0 z-10 bg-white shadow-sm">
      <div class="flex items-center justify-between px-4 py-3">
        <div class="flex items-center">
          <button @click="$inertia.visit('/')" class="mr-2 text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>
          <h1 class="text-lg font-bold text-gray-800 truncate">{{ product.name }}</h1>
        </div>
        <!-- Botão do Carrinho -->
        <CartButton
          v-if="!isCartOpen"
          :item-count="cart.length"
          @open-cart="isCartOpen = true"
        />
      </div>
    </header>

    <!-- Imagem do Produto -->
    <div class="w-full">
      <img :src="`/storage/${product.uri}`" :alt="product.name" class="w-full h-auto object-cover" />
    </div>

    <!-- Detalhes do Produto -->
    <div class="flex-1 px-4 py-4">
      <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ product.name }}</h2>
      <p class="text-gray-600 mb-4">{{ product.description }}</p>

      <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
        <div v-if="product.sale">
        <p class="text-gray-500 line-through text-xs">
          R$ {{ (product.price / 100).toLocaleString("pt-BR", { minimumFractionDigits: 2 }) }}
        </p>
        <p class="text-black font-bold text-2xl">
          R$ {{ (product.sale / 100).toLocaleString("pt-BR", { minimumFractionDigits: 2 }) }}
        </p>
      </div>

        <Button 
          @click="handleAddToCart(product)" 
          variant="default" 
          class="w-full cursor-pointer" 
        >
          Adicionar ao Carrinho
        </Button>
      </div>
    </div>

    <!-- Sidebar do Carrinho -->
    <CartSidebar
      v-if="isCartOpen"
      :items="cartWithCategoryNames"
      :total="cartTotal"
      @close-cart="isCartOpen = false"
      @remove-item="removeFromCart"
      @increase-quantity="increaseQuantity"
      @decrease-quantity="decreaseQuantity"
      @checkout="finalizarPedido"
    />

    <!-- Footer fixo (opcional) -->
    <footer class="bg-white border-t py-4 text-center text-sm text-gray-600">
      <p>{{tenant.name}}</p>
      <p class="text-sm">
        Contato: {{ formatPhoneNumber(tenant.whatsapp) }}
      </p>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { Button } from "@/components/ui/button";
import { Toaster } from '@/components/ui/sonner';
import CartButton from "@/components/cart/CartButton.vue";
import CartSidebar from "@/components/cart/CartSidebar.vue";
import { toast } from "vue-sonner"; // Import do toast

const props = defineProps({
  product: Object,
  tenant: Object
})

const product = ref(props.product)
const tenant = ref(props.tenant)
const cart = ref(JSON.parse(localStorage.getItem("cart") || "[]")) // Carregar carrinho do localStorage
const isCartOpen = ref(false)

// Computed properties
const cartWithCategoryNames = computed(() => {
  return cart.value.map((item) => ({
    ...item,
    categoryName: "Categoria não especificada", // Ajuste conforme necessário
    imageUrl: `/storage/${item.uri}`,
  }))
})

const cartTotal = computed(() => {
  return cart.value.reduce(
    (total, item) => total + (item.sale ? item.sale : item.price) * item.quantity,
    0
  )
})

// Watcher para salvar o carrinho no localStorage sempre que ele for atualizado
watch(cart, (newCart) => {
  localStorage.setItem("cart", JSON.stringify(newCart))
}, { deep: true })


const formatPhoneNumber = (whatsapp) => {
  const cleaned = whatsapp?.replace(/\D/g, "") || '';
  const match = cleaned.match(/^(\d{2})(\d{4,5})(\d{4})$/);
  return match ? `(${match[1]}) ${match[2]}-${match[3]}` : whatsapp;
};

// Adicionar produto ao carrinho
const handleAddToCart = (product) => {
  const existing = cart.value.find((item) => item.id === product.id)
  if (existing) {
    existing.quantity++
    toast.info("Quantidade atualizada", {
      description: `${product.name} (${existing.quantity}x)`,
    })
  } else {
    cart.value.push({ ...product, quantity: 1 })
    toast.success("Adicionado ao carrinho", {
      description: product.name,
      action: {
        label: "Ver carrinho",
        onClick: () => (isCartOpen.value = true),
      },
    })
  }
}

// Remover item do carrinho
const removeFromCart = (productId) => {
  cart.value = cart.value.filter((item) => item.id !== productId)
}

// Aumentar quantidade de um item no carrinho
const increaseQuantity = (productId) => {
  const item = cart.value.find((item) => item.id === productId)
  if (item) item.quantity++
}

// Diminuir quantidade de um item no carrinho
const decreaseQuantity = (productId) => {
  const item = cart.value.find((item) => item.id === productId)
  if (item && item.quantity > 1) item.quantity--
}

// Finalizar pedido
const finalizarPedido = () => {
  if (!cart.value.length) return
  toast.success("Pedido finalizado com sucesso!")
}
</script>

<style scoped>
/* Mobile first, sem estilos extras aqui para manter limpo */
</style>