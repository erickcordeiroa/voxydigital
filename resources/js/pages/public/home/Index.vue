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

<script setup>
import { ref, computed } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import StoreHeader from "@/components/store/StoreHeader.vue";
import CartButton from "@/components/cart/CartButton.vue";
import CartSidebar from "@/components/cart/CartSidebar.vue";
import OrderConfirmationModal from "@/components/order/OrderConfirmationModal.vue";
import ProductSection from "@/components/products/ProductSection.vue";
import CategorySlider from "@/components/home/CategorySlider.vue";
import { Toaster } from "@/components/ui/sonner";
import { toast } from 'vue-sonner'

const { props } = usePage();
const categories = ref(props.categories);
const products = ref(props.products);
const tenant = ref(props.tenant);

const selectedCategory = ref(null);
const cart = ref([]);
const isCartOpen = ref(false);
const showOrderConfirmation = ref(false);

// Computed properties
const filteredCategories = computed(() => {
  return selectedCategory.value 
    ? categories.value.filter(c => c.id === selectedCategory.value)
    : categories.value;
});

const cartWithCategoryNames = computed(() => {
  return cart.value.map(item => ({
    ...item,
    categoryName: getCategoryName(item.category_id),
    imageUrl: `/storage/${item.uri}`
  }));
});

const cartTotal = computed(() => {
  return cart.value.reduce(
    (total, item) => total + (item.sale ? item.sale : item.price) * item.quantity,
    0
  );
});

// Methods
const filterByCategory = (categoryId) => {
  selectedCategory.value = categoryId;
};

const addToCart = (product) => {
  const existing = cart.value.find((item) => item.id === product.id);
  if (existing) {
    existing.quantity++;
    toast.info('Quantidade atualizada', {
      description: `${product.name} (${existing.quantity}x)`,
    });
  } else {
    cart.value.push({ ...product, quantity: 1 });
    toast.success('Adicionado ao carrinho', {
      description: product.name,
      action: {
        label: 'Ver carrinho',
        onClick: () => isCartOpen.value = true
      },
    });
  }
};

const removeFromCart = (productId) => {
  cart.value = cart.value.filter((item) => item.id !== productId);
};

const increaseQuantity = (productId) => {
  const item = cart.value.find(item => item.id === productId);
  if (item) item.quantity++;
};

const decreaseQuantity = (productId) => {
  const item = cart.value.find(item => item.id === productId);
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
          showOrderConfirmation.value = false;
          isCartOpen.value = false;
          
          toast.success('Pedido realizado com sucesso!', {
            description: 'Enviaremos todas as informações do seu pedido via whatsapp informado!',
          });
        },
        onError: (errors) => {
          toast.error('Erro ao enviar pedido', {
            description: Object.values(errors).join('\n'),
          });
        }
      }
    );
  } catch (error) {
    console.error("Erro ao enviar pedido:", error);
    toast.error('Erro inesperado', {
      description: 'Ocorreu um erro ao processar seu pedido. Tente novamente.',
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