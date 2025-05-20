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

    const cartTotal = computed(() =>
        cart.value.reduce(
            (total, item) =>
                total +
                ((item.sale !== null && item.sale !== undefined ? item.sale : item.price) * (item.quantity || 1)),
            0
        )
    );

    function getCategoryName(categoryId?: number) {
        const category = categories.value.find((cat) => cat.id === categoryId);
        return category ? category.name : "Desconhecida";
    }

    function addToCart(product: Product) {
        const existing = cart.value.find((item) => item.id === product.id);
        if (existing) {
            existing.quantity = (existing.quantity || 1) + 1;
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
    }

    function removeFromCart(productId: number) {
        cart.value = cart.value.filter((item) => item.id !== productId);
    }

    function increaseQuantity(productId: number) {
        const item = cart.value.find((item) => item.id === productId);
        if (item) item.quantity = (item.quantity || 1) + 1;
    }

    function decreaseQuantity(productId: number) {
        const item = cart.value.find((item) => item.id === productId);
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