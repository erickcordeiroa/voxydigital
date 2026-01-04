<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem } from '@/types';
import { AlertCircle, Power, Settings } from 'lucide-vue-next';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Switch } from '@/components/ui/switch';

interface PaymentGateway {
    id: number | null;
    provider: string;
    name: string;
    logo: string;
    is_active: boolean;
    has_credentials: boolean;
    credentials: {
        access_token?: string;
        public_key?: string;
        api_key?: string;
        api_secret?: string;
        base_url?: string;
    } | null;
}

interface Props {
    gateways: PaymentGateway[];
    status?: string;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Métodos de Pagamento',
        href: '/settings/payment-methods',
    },
];

const showCredentialsDialog = ref(false);
const selectedGateway = ref<PaymentGateway | null>(null);

const credentialsForm = useForm<{
    name: string;
    credentials: Record<string, string>;
}>({
    name: '',
    credentials: {},
});

const toggleForm = useForm({});

const openCredentialsDialog = (gateway: PaymentGateway) => {
    selectedGateway.value = gateway;
    credentialsForm.name = gateway.name || '';
    
    if (gateway.provider === 'mercadopago') {
        credentialsForm.credentials = {
            access_token: gateway.credentials?.access_token || '',
            public_key: gateway.credentials?.public_key || '',
        } as Record<string, string>;
    } else if (gateway.provider === 'abacatepay') {
        credentialsForm.credentials = {
            api_key: gateway.credentials?.api_key || '',
            api_secret: gateway.credentials?.api_secret || '',
            base_url: gateway.credentials?.base_url || '',
        } as Record<string, string>;
    }
    
    showCredentialsDialog.value = true;
};

const closeCredentialsDialog = () => {
    showCredentialsDialog.value = false;
    selectedGateway.value = null;
    credentialsForm.reset();
    credentialsForm.clearErrors();
};

const handleToggle = (gateway: PaymentGateway, newValue: boolean) => {
    if (!gateway.id) {
        // Se não tem ID, precisa configurar credenciais primeiro
        openCredentialsDialog(gateway);
        return;
    }
    
    if (newValue && !gateway.has_credentials) {
        // Tentando ativar mas não tem credenciais
        openCredentialsDialog(gateway);
        return;
    }
    
    // Fazer o toggle
    toggleForm.post(route('payment-methods.toggle', gateway.id), {
        preserveScroll: true,
    });
};

const handleSaveCredentials = () => {
    if (!selectedGateway.value) return;
    
    const formData = {
        name: credentialsForm.name || selectedGateway.value.name,
        credentials: {},
    };
    
    if (selectedGateway.value.provider === 'mercadopago') {
        formData.credentials = {
            access_token: credentialsForm.credentials.access_token,
            public_key: credentialsForm.credentials.public_key || '',
        };
    } else if (selectedGateway.value.provider === 'abacatepay') {
        formData.credentials = {
            api_key: credentialsForm.credentials.api_key,
            api_secret: credentialsForm.credentials.api_secret,
            base_url: credentialsForm.credentials.base_url || '',
        };
    }
    
    if (selectedGateway.value.id) {
        // Atualizar gateway existente
        credentialsForm.transform(() => formData).put(route('payment-methods.update', selectedGateway.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                closeCredentialsDialog();
            },
        });
    } else {
        // Criar novo gateway
        credentialsForm.transform(() => ({
            ...formData,
            provider: selectedGateway.value!.provider,
        })).post(route('payment-methods.store'), {
            preserveScroll: true,
            onSuccess: () => {
                closeCredentialsDialog();
            },
        });
    }
};

const getProviderFields = computed(() => {
    if (!selectedGateway.value) return [];
    
    if (selectedGateway.value.provider === 'mercadopago') {
        return [
            {
                key: 'access_token',
                label: 'Access Token',
                type: 'password',
                required: true,
            },
            {
                key: 'public_key',
                label: 'Chave Pública',
                type: 'text',
                required: false,
            },
        ];
    } else if (selectedGateway.value.provider === 'abacatepay') {
        return [
            {
                key: 'api_key',
                label: 'API Key',
                type: 'password',
                required: true,
            },
            {
                key: 'api_secret',
                label: 'API Secret',
                type: 'password',
                required: true,
            },
            {
                key: 'base_url',
                label: 'URL Base',
                type: 'text',
                required: false,
            },
        ];
    }
    
    return [];
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Métodos de Pagamento" />

        <SettingsLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall 
                    title="Métodos de Pagamento" 
                    description="Gerencie suas formas de pagamento e credenciais" 
                />

                <Alert v-if="props.status" class="mb-4">
                    <AlertCircle class="h-4 w-4" />
                    <AlertTitle>Notificação</AlertTitle>
                    <AlertDescription>{{ props.status }}</AlertDescription>
                </Alert>

                <div v-if="props.gateways.length === 0" class="rounded-lg border border-dashed p-8 text-center">
                    <AlertCircle class="mx-auto h-12 w-12 text-muted-foreground" />
                    <h3 class="mt-4 text-lg font-semibold">Nenhum gateway disponível</h3>
                    <p class="mt-2 text-sm text-muted-foreground">
                        Não há gateways de pagamento disponíveis no momento.
                    </p>
                </div>

                <div v-else class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <Card 
                        v-for="gateway in props.gateways" 
                        :key="gateway.provider"
                        class="relative overflow-hidden transition-all hover:shadow-lg"
                        :class="{
                            'ring-2 ring-primary': gateway.is_active,
                            'opacity-75': !gateway.is_active && !gateway.has_credentials,
                        }"
                    >
                        <CardContent class="p-6">
                            <div class="flex flex-col items-center space-y-4">
                                <!-- Logo -->
                                <div class="flex h-20 w-20 items-center justify-center rounded-lg bg-muted p-4">
                                    <img 
                                        v-if="gateway.logo" 
                                        :src="gateway.logo" 
                                        :alt="gateway.name"
                                        class="h-full w-full object-contain"
                                    />
                                    <div v-else class="flex h-full w-full items-center justify-center text-2xl font-bold text-muted-foreground">
                                        {{ gateway.name.charAt(0) }}
                                    </div>
                                </div>

                                <!-- Nome -->
                                <div class="text-center">
                                    <h3 class="text-lg font-semibold">{{ gateway.name }}</h3>
                                    <p class="text-xs text-muted-foreground mt-1">
                                        {{ gateway.provider }}
                                    </p>
                                </div>

                                <!-- Status Badge -->
                                <Badge 
                                    :variant="gateway.is_active ? 'default' : 'secondary'"
                                    class="w-full justify-center"
                                >
                                    <Power 
                                        class="mr-2 h-3 w-3" 
                                        :class="gateway.is_active ? 'text-green-500' : 'text-gray-400'"
                                    />
                                    {{ gateway.is_active ? 'Ativo' : 'Inativo' }}
                                </Badge>

                                <!-- Toggle Switch -->
                                <div class="flex w-full items-center justify-between space-x-2 rounded-lg border p-3">
                                    <span class="text-sm font-medium">Status</span>
                                    <div class="flex items-center space-x-2">
                                        <span class="text-xs text-muted-foreground">
                                            {{ gateway.is_active ? 'Ativo' : 'Inativo' }}
                                        </span>
                                        <Switch
                                            :checked="gateway.is_active"
                                            @update:checked="(checked) => handleToggle(gateway, checked)"
                                            :disabled="toggleForm.processing"
                                        />
                                    </div>
                                </div>

                                <!-- Botão de Configuração -->
                                <Button
                                    variant="outline"
                                    class="w-full"
                                    @click="openCredentialsDialog(gateway)"
                                    :disabled="toggleForm.processing"
                                >
                                    <Settings class="mr-2 h-4 w-4" />
                                    {{ gateway.has_credentials ? 'Editar Credenciais' : 'Configurar' }}
                                </Button>

                                <!-- Aviso se não tem credenciais -->
                                <Alert 
                                    v-if="!gateway.has_credentials && !gateway.is_active" 
                                    class="w-full"
                                >
                                    <AlertCircle class="h-4 w-4" />
                                    <AlertDescription class="text-xs">
                                        Configure as credenciais para ativar este gateway.
                                    </AlertDescription>
                                </Alert>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Dialog de Credenciais -->
                <Dialog :open="showCredentialsDialog" @update:open="showCredentialsDialog = $event">
                    <DialogContent class="max-w-2xl max-h-[90vh] overflow-y-auto">
                        <DialogHeader>
                            <DialogTitle>
                                Configurar {{ selectedGateway?.name }}
                            </DialogTitle>
                            <DialogDescription>
                                Preencha as credenciais necessárias para ativar este gateway de pagamento
                            </DialogDescription>
                        </DialogHeader>

                        <form @submit.prevent="handleSaveCredentials" class="space-y-4">
                            <div 
                                v-for="field in getProviderFields" 
                                :key="field.key"
                                class="grid gap-2"
                            >
                                <Label :for="field.key">
                                    {{ field.label }}
                                    <span v-if="field.required" class="text-destructive">*</span>
                                </Label>
                                <Input
                                    :id="field.key"
                                    v-model="credentialsForm.credentials[field.key]"
                                    :type="field.type"
                                    :placeholder="`Digite ${field.label.toLowerCase()}`"
                                    :required="field.required"
                                />
                                <InputError :message="(credentialsForm.errors as any)[`credentials.${field.key}`]" />
                            </div>

                            <Alert v-if="selectedGateway?.provider === 'mercadopago'" class="mt-4">
                                <AlertCircle class="h-4 w-4" />
                                <AlertDescription class="text-xs">
                                    Você pode encontrar suas credenciais do Mercado Pago no painel do Mercado Pago.
                                </AlertDescription>
                            </Alert>

                            <div class="flex justify-end space-x-2 pt-4">
                                <Button
                                    type="button"
                                    variant="outline"
                                    @click="closeCredentialsDialog"
                                    :disabled="credentialsForm.processing"
                                >
                                    Cancelar
                                </Button>
                                <Button
                                    type="submit"
                                    :disabled="credentialsForm.processing"
                                >
                                    {{ credentialsForm.processing ? 'Salvando...' : 'Salvar Credenciais' }}
                                </Button>
                            </div>
                        </form>
                    </DialogContent>
                </Dialog>
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
