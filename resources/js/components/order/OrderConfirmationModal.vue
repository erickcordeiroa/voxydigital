<template>
  <div class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
      <!-- ... cabeçalho ... -->
      
      <form @submit.prevent="submitForm" class="space-y-4">
        <div>
          <label for="customer_name" class="block text-sm font-medium mb-1">Nome para contato*</label>
          <input
            id="customer_name"
            v-model="customerInfo.name"
            type="text"
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': errors.name }"
          />
          <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name }}</p>
        </div>

        <div>
          <label for="customer_phone" class="block text-sm font-medium mb-1">Telefone*</label>
          <input
            id="customer_phone"
            v-model="customerInfo.phone"
            type="tel"
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': errors.phone }"
          />
          <p v-if="errors.phone" class="text-red-500 text-xs mt-1">{{ errors.phone }}</p>
        </div>

        <div>
          <label for="delivery_address" class="block text-sm font-medium mb-1">Endereço de entrega*</label>
          <textarea
            id="delivery_address"
            v-model="customerInfo.address"
            rows="3"
            class="w-full border rounded px-3 py-2"
            :class="{ 'border-red-500': errors.address }"
          ></textarea>
          <p v-if="errors.address" class="text-red-500 text-xs mt-1">{{ errors.address }}</p>
        </div>

        <div>
          <label for="delivery_note" class="block text-sm font-medium mb-1">Observações</label>
          <textarea
            id="delivery_note"
            v-model="customerInfo.note"
            rows="2"
            class="w-full border rounded px-3 py-2"
          ></textarea>
        </div>

        <div class="flex justify-end gap-2 pt-4">
          <Button variant="outline" @click="$emit('close')">Cancelar</Button>
          <Button type="submit" :disabled="isSubmitting">
            <span v-if="isSubmitting">Enviando...</span>
            <span v-else>Confirmar Pedido</span>
          </Button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Button } from '@/components/ui/button';

const emit = defineEmits(['close', 'submit']);

const customerInfo = ref({
  name: '',
  phone: '',
  address: '',
  note: ''
});

const errors = ref({
  name: '',
  phone: '',
  address: ''
});

const isSubmitting = ref(false);

const validateForm = () => {
  let valid = true;
  errors.value = { name: '', phone: '', address: '' };

  if (!customerInfo.value.name.trim()) {
    errors.value.name = 'Por favor, insira seu nome';
    valid = false;
  }

  if (!customerInfo.value.phone.trim()) {
    errors.value.phone = 'Por favor, insira um telefone válido';
    valid = false;
  } else if (!/^\d{10,11}$/.test(customerInfo.value.phone.replace(/\D/g, ''))) {
    errors.value.phone = 'Telefone inválido (DDD + número)';
    valid = false;
  }

  if (!customerInfo.value.address.trim()) {
    errors.value.address = 'Por favor, insira o endereço de entrega';
    valid = false;
  }

  return valid;
};

const submitForm = () => {
  if (!validateForm()) return;
  
  isSubmitting.value = true;
  emit('submit', customerInfo.value);
  isSubmitting.value = false;
};
</script>