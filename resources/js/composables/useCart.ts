import { ref, computed, watch, Ref } from "vue";
import { router } from "@inertiajs/vue3";
import { toast } from "vue-sonner";
import type { Product, Category, Tenant } from "@/types/cart.ts";

export function useCart(
    categories: Ref<Category[]>,
    tenant: Ref<Tenant>
) {
    const cart = ref<Product[]>(JSON.parse(localStorage.getItem("cart") || "[]"));
    const isCartOpen = ref(false);

    const cartWithCategoryNames = computed(() =>
        cart.value.map((item) => ({
            ...item,
            categoryName: getCategoryName(item.category_id),
            imageUrl: `/storage/${item.uri}`,
        }))
    );

    const cartTotal = computed(() => {
    return cart.value.reduce((total, item) => {
        const price = item.sale && item.sale > 0 ? item.sale : item.price;
        return total + (Number(price) * Number(item.quantity || 1));
    }, 0);
});

    function getCategoryName(categoryId?: number) {
        const category = categories.value.find((cat) => cat.id === categoryId);
        return category ? category.name : "Desconhecida";
    }

    function addToCart(product: Product, variation: any) {
        const existing = cart.value.find(
            (item) => item.id === product.id && item.variation?.id === variation?.id
        );
        if (existing) {
            existing.quantity = (existing.quantity || 1) + 1;
            toast.info("Quantidade atualizada", {
                description: `${product.name} (${existing.quantity}x)`,
            });
        } else {
            cart.value.push({ ...product, quantity: 1, variation });
            toast.success("Adicionado ao carrinho", {
                description: variation?.size
                    ? `${product.name} (${variation.size})`
                    : product.name,
                action: {
                    label: "Ver carrinho",
                    onClick: () => (isCartOpen.value = true),
                },
            });
        }
    }

    function removeFromCart(productId: number, variationId?: number) {
        cart.value = cart.value.filter((item) => {
            // Se o produto tem variação, remove apenas se id e variation.id batem
            if (variationId !== null) {
                return !(item.id === productId && item.variation?.id === variationId);
            }
            // Se não tem variação, remove pelo id e item sem variation
            return !(item.id === productId && !item.variation);
        });
    }

    function increaseQuantity(productId: number, variationId?: number) {
        const item = cart.value.find((item) =>
            variationId !== null
                ? item.id === productId && item.variation?.id === variationId
                : item.id === productId && !item.variation
        );

        if (item) item.quantity = (item.quantity || 1) + 1;
    }

    function decreaseQuantity(productId: number, variationId?: number) {
        const item = cart.value.find((item) => 
            variationId !== null
                ? item.id === productId && item.variation?.id === variationId
                : item.id === productId && !item.variation
        );

        if (item && (item.quantity || 1) > 1) item.quantity!--;
    }

    function finalizarPedido() {
        if (!cart.value.length) return;
        // Redireciona para a página de checkout
        router.visit(route('checkout'));
    }

    watch(
        cart,
        (newCart) => {
            localStorage.setItem("cart", JSON.stringify(newCart));
        },
        { deep: true }
    );

    return {
        cart,
        isCartOpen,
        cartWithCategoryNames,
        cartTotal,
        addToCart,
        removeFromCart,
        increaseQuantity,
        decreaseQuantity,
        finalizarPedido,
    };
}