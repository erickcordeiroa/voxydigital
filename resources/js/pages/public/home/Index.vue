<template>
  <Toaster />
  <div>
    <StoreHeader
      :logo-url="`/storage/${tenant.logo}`"
      :store-name="tenant.name"
      :contact-phone="tenant.whatsapp"
    />

    <CartButton
      v-if="!isCartOpen"
      :item-count="cart.length"
      @open-cart="isCartOpen = true"
    />

    <div class="p-4 md:p-6 lg:p-10">

      <!-- Verificação para exibir mensagem caso não haja produtos nem categorias -->
      <div
        v-if="categories.length == 0 && products.length == 0"
        class="text-center text-gray-500"
      >

        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-16 w-16 text-gray-400 mb-4 mx-auto"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M9.75 9.75h4.5m-2.25-2.25v4.5m-7.5 9h15a2.25 2.25 0 002.25-2.25v-15A2.25 2.25 0 0019.5 3h-15A2.25 2.25 0 002.25 5.25v15A2.25 2.25 0 004.5 22.5z"
          />
        </svg>
        <p class="text-lg font-semibold">Nenhuma informação disponível</p>
        <p class="text-sm">
          Esta empresa ainda não cadastrou produtos ou categorias. Volte mais tarde para conferir as novidades!
        </p>
      </div>

      <div v-else>
        <CategorySlider
          :categories="categories"
          :selected-category="selectedCategory"
          @category-select="filterByCategory"
        />

        <ProductSection
          v-for="category in filteredCategories"
          :key="category.id"
          :category="category"
          :products="products"
          @add-to-cart="addToCart"
        />
      </div>
    </div>

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

    <OrderConfirmationModal
      v-if="showOrderConfirmation"
      @close="showOrderConfirmation = false"
      @submit="submitOrder"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import StoreHeader from "@/components/store/StoreHeader.vue";
import CartButton from "@/components/cart/CartButton.vue";
import CartSidebar from "@/components/cart/CartSidebar.vue";
import OrderConfirmationModal from "@/components/order/OrderConfirmationModal.vue";
import ProductSection from "@/components/products/ProductSection.vue";
import CategorySlider from "@/components/home/CategorySlider.vue";
import { Toaster } from "@/components/ui/sonner";
import { toast } from "vue-sonner";

const { props } = usePage();
const categories = ref(props.categories);
const products = ref(props.products);
const tenant = ref(props.tenant);

const selectedCategory = ref(null);
const cart = ref(JSON.parse(localStorage.getItem("cart") || "[]")); // Carregar carrinho do localStorage
const isCartOpen = ref(false);
const showOrderConfirmation = ref(false);

// Computed properties
const filteredCategories = computed(() => {
  return selectedCategory.value
    ? categories.value.filter((c) => c.id === selectedCategory.value)
    : categories.value;
});

const cartWithCategoryNames = computed(() => {
  return cart.value.map((item) => ({
    ...item,
    categoryName: getCategoryName(item.category_id),
    imageUrl: `/storage/${item.uri}`,
  }));
});

const cartTotal = computed(() => {
  return cart.value.reduce(
    (total, item) => total + ((item.sale !== null && item.sale !== undefined ? item.sale : item.price) * item.quantity),
    0
  );
});

// Watcher para salvar o carrinho no localStorage sempre que ele for atualizado
watch(cart, (newCart) => {
  localStorage.setItem("cart", JSON.stringify(newCart));
}, { deep: true });

// Methods
const filterByCategory = (categoryId) => {
  selectedCategory.value = categoryId;
};

const addToCart = (product) => {
  const existing = cart.value.find((item) => item.id === product.id);
  if (existing) {
    existing.quantity++;
    toast.info("Quantidade atualizada", {
      description: `${product.name} (${existing.quantity}x)`,
    });
  } else {
    cart.value.push({ ...product, quantity: 1 });
    toast.success("Adicionado ao carrinho", {
      description: product.name,
      action: {
        label: "Ver carrinho",
        onClick: () => (isCartOpen.value = true),
      },
    });
  }
};

const removeFromCart = (productId) => {
  cart.value = cart.value.filter((item) => item.id !== productId);
};

const increaseQuantity = (productId) => {
  const item = cart.value.find((item) => item.id === productId);
  if (item) item.quantity++;
};

const decreaseQuantity = (productId) => {
  const item = cart.value.find((item) => item.id === productId);
  if (item && item.quantity > 1) item.quantity--;
};

const getCategoryName = (categoryId) => {
  const category = categories.value.find((cat) => cat.id === categoryId);
  return category ? category.name : "Desconhecida";
};

const finalizarPedido = () => {
  if (!cart.value.length) return;
  showOrderConfirmation.value = true;
};

const submitOrder = async (customerData) => {
  try {
    await router.post(
      route("orders.store"),
      {
        tenant_id: tenant.value.id,
        customer_name: customerData.name,
        customer_phone: customerData.phone,
        delivery_address: customerData.address,
        note: customerData.note,
        total: cartTotal.value,
        items: cart.value.map((item) => ({
          product_id: item.id,
          quantity: item.quantity,
          price: item.sale ?? item.price,
        })),
      },
      {
        preserveScroll: true,
        onSuccess: () => {
          cart.value = [];
          localStorage.removeItem("cart"); // Limpar o carrinho do localStorage
          showOrderConfirmation.value = false;
          isCartOpen.value = false;

          toast.success("Pedido realizado com sucesso!", {
            description:
              "Enviaremos todas as informações do seu pedido via whatsapp informado!",
          });
        },
        onError: (errors) => {
          toast.error("Erro ao enviar pedido", {
            description: Object.values(errors).join("\n"),
          });
        },
      }
    );
  } catch (error) {
    console.error("Erro ao enviar pedido:", error);
    toast.error("Erro inesperado", {
      description: "Ocorreu um erro ao processar seu pedido. Tente novamente.",
    });
  }
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
