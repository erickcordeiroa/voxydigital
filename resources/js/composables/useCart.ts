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
    const showOrderConfirmation = ref(false);

    const cartWithCategoryNames = computed(() =>
        cart.value.map((item) => ({
            ...item,
            categoryName: getCategoryName(item.category_id),
            imageUrl: `/storage/${item.uri}`,
        }))
    );

    const cartTotal = computed(() => {
       return cart.value.reduce(
            (total, item) =>
                total +
                (Number(item.sale ?? item.price) * Number(item.quantity || 1)),
            0
        );
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
        showOrderConfirmation.value = true;
    }

    async function submitOrder(customerData: {
        name: string;
        phone: string;
        address: string;
        note?: string;
    }) {
        try {
            router.post(
                route("orders.store"),
                {
                    tenant_id: tenant.value.id,
                    customer_name: customerData.name,
                    customer_phone: customerData.phone,
                    delivery_address: customerData.address,
                    note: customerData.note,
                    tax_fixed: tenant.value.tax_fixed,
                    total: cartTotal.value + tenant.value.tax_fixed,
                    items: cart.value.map((item) => ({
                        product_id: item.id,
                        quantity: item.quantity,
                        price: item.sale ?? item.price,
                        variation_id: item.variation?.id,
                    })),
                },
                {
                    preserveScroll: true,
                    onSuccess: () => {
                        cart.value = [];
                        localStorage.removeItem("cart");
                        showOrderConfirmation.value = false;
                        isCartOpen.value = false;
                        toast.success("Pedido enviado com sucesso!", {
                            description: "Recebemos seu pedido! Em breve você receberá todos os detalhes e o acompanhamento pelo WhatsApp informado.",
                        });
                    },
                    onError: (errors: Record<string, string>) => {
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
        showOrderConfirmation,
        cartWithCategoryNames,
        cartTotal,
        addToCart,
        removeFromCart,
        increaseQuantity,
        decreaseQuantity,
        finalizarPedido,
        submitOrder,
    };
}