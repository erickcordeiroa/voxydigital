<template>
  <!-- Versão com trigger slot (modo original) -->
  <AlertDialog v-if="!show">
    <AlertDialogTrigger asChild>
      <slot name="trigger"></slot>
    </AlertDialogTrigger>
    <AlertDialogContent>
      <AlertDialogHeader>
        <AlertDialogTitle>{{ title }}</AlertDialogTitle>
        <AlertDialogDescription>{{ description || message }}</AlertDialogDescription>
      </AlertDialogHeader>
      <AlertDialogFooter>
        <button @click="onCancel" class="btn px-4 py-2 rounded-md bg-gray-500 text-white hover:bg-gray-600">Cancelar</button>
        <button @click="onConfirm" class="btn px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700">Confirmar</button>
      </AlertDialogFooter>
    </AlertDialogContent>
  </AlertDialog>

  <!-- Versão com prop show (modo programático) -->
  <AlertDialog v-else :open="show" @update:open="(value) => !value && onCancel()">
    <AlertDialogContent>
      <AlertDialogHeader>
        <AlertDialogTitle>{{ title }}</AlertDialogTitle>
        <AlertDialogDescription>{{ description || message }}</AlertDialogDescription>
      </AlertDialogHeader>
      <AlertDialogFooter>
        <button @click="onCancel" class="px-4 py-2 rounded-md bg-gray-500 text-white hover:bg-gray-600 transition-colors">
          Cancelar
        </button>
        <button @click="onConfirm" class="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 transition-colors">
          Confirmar
        </button>
      </AlertDialogFooter>
    </AlertDialogContent>
  </AlertDialog>
</template>

<script setup lang="ts">
import {
  AlertDialog,
  AlertDialogTrigger,
  AlertDialogContent,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogDescription,
  AlertDialogFooter,
} from "@/components/ui/alert-dialog";

defineProps({
  title: String,
  description: String,
  message: String, // Suporte alternativo para compatibilidade
  show: { type: Boolean, default: false }, // Suporte para modo programático
  open: { type: Boolean, default: false }, // Suporte para modo open (categorias)
});

const emit = defineEmits(["confirm", "cancel"]);

function onConfirm() {
  emit("confirm");
}

function onCancel() {
  emit("cancel");
}
</script>