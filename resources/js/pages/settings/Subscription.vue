<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';
import { AlertCircle, CheckCircle2, XCircle, Calendar, CreditCard, DollarSign, Plus } from 'lucide-vue-next';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Subscription {
    id: number;
    plan_name: string;
    amount: number;
    currency: string;
    status: string;
    billing_cycle: string;
    starts_at: string | null;
    ends_at: string | null;
    next_billing_date: string | null;
    can_cancel: boolean;
}

interface PaymentGateway {
    id: number;
    name: string;
    provider: string;
}

interface Props {
    subscription: Subscription | null;
    paymentGateways: PaymentGateway[];
    status?: string;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Assinatura',
        href: '/settings/subscription',
    },
];

const showCancelDialog = ref(false);
const showCreateDialog = ref(false);

const cancelForm = useForm({});
const reactivateForm = useForm({});

const createForm = useForm({
    plan_name: '',
    amount: '',
    currency: 'BRL',
    billing_cycle: 'monthly',
    status: 'active',
    payment_gateway_id: props.paymentGateways[0]?.id || null,
});

const formatCurrency = (amount: number, currency: string = 'BRL') => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: currency,
    }).format(amount / 100);
};

const getStatusBadge = (status: string) => {
    const statusMap: Record<string, { label: string; variant: 'default' | 'secondary' | 'destructive' | 'outline' }> = {
        active: { label: 'Ativa', variant: 'default' },
        cancelled: { label: 'Cancelada', variant: 'secondary' },
        expired: { label: 'Expirada', variant: 'destructive' },
        pending: { label: 'Pendente', variant: 'outline' },
    };
    return statusMap[status] || { label: status, variant: 'outline' };
};

const getBillingCycleLabel = (cycle: string) => {
    return cycle === 'monthly' ? 'Mensal' : 'Anual';
};

const handleCancel = () => {
    cancelForm.post(route('subscription.cancel'), {
        preserveScroll: true,
        onSuccess: () => {
            showCancelDialog.value = false;
        },
    });
};

const handleReactivate = () => {
    if (!props.subscription) return;
    
    reactivateForm.post(route('subscription.reactivate', props.subscription.id), {
        preserveScroll: true,
    });
};

const handleCreate = () => {
    // Converter amount para número
    const formData = {
        ...createForm.data(),
        amount: parseInt(createForm.amount) || 0,
    };
    
    createForm.transform(() => formData).post(route('subscription.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showCreateDialog.value = false;
            createForm.reset();
        },
    });
};

const formatAmountDisplay = (amount: string | number) => {
    if (!amount) return '0,00';
    const num = typeof amount === 'string' ? parseInt(amount) : amount;
    return (num / 100).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Assinatura" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall 
                    title="Informações da Assinatura" 
                    description="Visualize e gerencie sua assinatura" 
                />

                <Alert v-if="status" class="mb-4">
                    <AlertCircle class="h-4 w-4" />
                    <AlertTitle>Notificação</AlertTitle>
                    <AlertDescription>{{ status }}</AlertDescription>
                </Alert>

                <div v-if="!subscription" class="rounded-lg border border-dashed p-8 text-center">
                    <AlertCircle class="mx-auto h-12 w-12 text-muted-foreground" />
                    <h3 class="mt-4 text-lg font-semibold">Nenhuma assinatura encontrada</h3>
                    <p class="mt-2 text-sm text-muted-foreground mb-4">
                        Você não possui uma assinatura ativa no momento.
                    </p>
                    <Button @click="showCreateDialog = true">
                        <Plus class="mr-2 h-4 w-4" />
                        Cadastrar Assinatura
                    </Button>
                </div>

                <Card v-else>
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <div>
                                <CardTitle>{{ subscription.plan_name }}</CardTitle>
                                <CardDescription>Detalhes da sua assinatura</CardDescription>
                            </div>
                            <Badge :variant="getStatusBadge(subscription.status).variant">
                                {{ getStatusBadge(subscription.status).label }}
                            </Badge>
                        </div>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="grid gap-4 md:grid-cols-2">
                            <div class="flex items-start space-x-3">
                                <DollarSign class="mt-1 h-5 w-5 text-muted-foreground" />
                                <div>
                                    <p class="text-sm font-medium">Valor</p>
                                    <p class="text-2xl font-bold">
                                        {{ formatCurrency(subscription.amount, subscription.currency) }}
                                    </p>
                                    <p class="text-xs text-muted-foreground">
                                        {{ getBillingCycleLabel(subscription.billing_cycle) }}
                                    </p>
                                </div>
                            </div>

                            <div v-if="subscription.starts_at" class="flex items-start space-x-3">
                                <Calendar class="mt-1 h-5 w-5 text-muted-foreground" />
                                <div>
                                    <p class="text-sm font-medium">Data de Início</p>
                                    <p class="text-sm font-semibold">{{ subscription.starts_at }}</p>
                                </div>
                            </div>

                            <div v-if="subscription.next_billing_date && subscription.status === 'active'" class="flex items-start space-x-3">
                                <Calendar class="mt-1 h-5 w-5 text-muted-foreground" />
                                <div>
                                    <p class="text-sm font-medium">Próxima Cobrança</p>
                                    <p class="text-sm font-semibold">{{ subscription.next_billing_date }}</p>
                                </div>
                            </div>

                            <div v-if="subscription.ends_at" class="flex items-start space-x-3">
                                <Calendar class="mt-1 h-5 w-5 text-muted-foreground" />
                                <div>
                                    <p class="text-sm font-medium">
                                        {{ subscription.status === 'cancelled' ? 'Data de Término' : 'Data de Expiração' }}
                                    </p>
                                    <p class="text-sm font-semibold">{{ subscription.ends_at }}</p>
                                    <p v-if="subscription.status === 'cancelled'" class="text-xs text-muted-foreground">
                                        Você terá acesso até esta data
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div v-if="subscription.status === 'cancelled'" class="rounded-lg bg-muted p-4">
                            <div class="flex items-start space-x-2">
                                <XCircle class="mt-0.5 h-5 w-5 text-muted-foreground" />
                                <div>
                                    <p class="text-sm font-medium">Assinatura Cancelada</p>
                                    <p class="text-xs text-muted-foreground">
                                        Sua assinatura foi cancelada. Você continuará com acesso até o final do período já pago.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 border-t pt-4">
                            <Button
                                v-if="subscription.status === 'cancelled'"
                                @click="showCreateDialog = true"
                                :disabled="cancelForm.processing"
                            >
                                <Plus class="mr-2 h-4 w-4" />
                                Criar Nova Assinatura
                            </Button>
                            <Button
                                v-if="subscription.status === 'cancelled'"
                                variant="default"
                                @click="handleReactivate"
                                :disabled="reactivateForm.processing"
                            >
                                Reativar Assinatura
                            </Button>
                            <Button
                                v-if="subscription.can_cancel"
                                variant="destructive"
                                @click="showCancelDialog = true"
                                :disabled="cancelForm.processing"
                            >
                                Cancelar Assinatura
                            </Button>
                        </div>
                    </CardContent>
                </Card>

                <!-- Dialog de Confirmação de Cancelamento -->
                <div
                    v-if="showCancelDialog"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                    @click.self="showCancelDialog = false"
                >
                    <Card class="w-full max-w-md">
                        <CardHeader>
                            <CardTitle>Confirmar Cancelamento</CardTitle>
                            <CardDescription>
                                Tem certeza que deseja cancelar sua assinatura?
                            </CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <Alert>
                                <AlertCircle class="h-4 w-4" />
                                <AlertDescription>
                                    Ao cancelar, você continuará com acesso até o final do período já pago.
                                    Após essa data, sua assinatura será desativada.
                                </AlertDescription>
                            </Alert>
                            <div class="flex justify-end space-x-2">
                                <Button
                                    variant="outline"
                                    @click="showCancelDialog = false"
                                    :disabled="cancelForm.processing"
                                >
                                    Não, manter assinatura
                                </Button>
                                <Button
                                    variant="destructive"
                                    @click="handleCancel"
                                    :disabled="cancelForm.processing"
                                >
                                    Sim, cancelar assinatura
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Dialog de Cadastro de Assinatura -->
                <Dialog :open="showCreateDialog" @update:open="showCreateDialog = $event">
                    <DialogContent class="max-w-2xl max-h-[90vh] overflow-y-auto">
                        <DialogHeader>
                            <DialogTitle>Cadastrar Nova Assinatura</DialogTitle>
                            <DialogDescription>
                                Preencha os dados para criar uma nova assinatura
                            </DialogDescription>
                        </DialogHeader>

                        <form @submit.prevent="handleCreate" class="space-y-4">
                            <div class="grid gap-2">
                                <Label for="plan_name">Nome do Plano *</Label>
                                <Input
                                    id="plan_name"
                                    v-model="createForm.plan_name"
                                    placeholder="Ex: Plano Premium Mensal"
                                    required
                                />
                                <InputError :message="createForm.errors.plan_name" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="grid gap-2">
                                    <Label for="amount">Valor (em centavos) *</Label>
                                    <Input
                                        id="amount"
                                        v-model="createForm.amount"
                                        type="number"
                                        placeholder="9900"
                                        min="1"
                                        required
                                    />
                                    <InputError :message="createForm.errors.amount" />
                                    <p class="text-xs text-muted-foreground">
                                        Valor: R$ {{ formatAmountDisplay(createForm.amount) }} ({{ createForm.amount || 0 }} centavos)
                                    </p>
                                </div>

                                <div class="grid gap-2">
                                    <Label for="currency">Moeda</Label>
                                    <Input
                                        id="currency"
                                        v-model="createForm.currency"
                                        maxlength="3"
                                        placeholder="BRL"
                                    />
                                    <InputError :message="createForm.errors.currency" />
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="grid gap-2">
                                    <Label for="billing_cycle">Ciclo de Cobrança *</Label>
                                    <select
                                        id="billing_cycle"
                                        v-model="createForm.billing_cycle"
                                        class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                        required
                                    >
                                        <option value="monthly">Mensal</option>
                                        <option value="yearly">Anual</option>
                                    </select>
                                    <InputError :message="createForm.errors.billing_cycle" />
                                </div>

                                <div class="grid gap-2">
                                    <Label for="status">Status *</Label>
                                    <select
                                        id="status"
                                        v-model="createForm.status"
                                        class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                        required
                                    >
                                        <option value="active">Ativa</option>
                                        <option value="pending">Pendente</option>
                                        <option value="cancelled">Cancelada</option>
                                        <option value="expired">Expirada</option>
                                    </select>
                                    <InputError :message="createForm.errors.status" />
                                </div>
                            </div>

                            <div v-if="paymentGateways.length > 0" class="grid gap-2">
                                <Label for="payment_gateway_id">Gateway de Pagamento</Label>
                                <select
                                    id="payment_gateway_id"
                                    v-model="createForm.payment_gateway_id"
                                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    <option :value="null">Selecione um gateway (opcional)</option>
                                    <option
                                        v-for="gateway in paymentGateways"
                                        :key="gateway.id"
                                        :value="gateway.id"
                                    >
                                        {{ gateway.name }} ({{ gateway.provider }})
                                    </option>
                                </select>
                                <InputError :message="createForm.errors.payment_gateway_id" />
                            </div>

                            <div class="flex justify-end space-x-2 pt-4">
                                <Button
                                    type="button"
                                    variant="outline"
                                    @click="showCreateDialog = false"
                                    :disabled="createForm.processing"
                                >
                                    Cancelar
                                </Button>
                                <Button
                                    type="submit"
                                    :disabled="createForm.processing"
                                >
                                    {{ createForm.processing ? 'Salvando...' : 'Cadastrar' }}
                                </Button>
                            </div>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>

