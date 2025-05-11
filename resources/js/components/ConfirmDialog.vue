<template>
  <AlertDialog>
    <AlertDialogTrigger asChild>
      <slot name="trigger"></slot>
    </AlertDialogTrigger>
    <AlertDialogContent>
      <AlertDialogHeader>
        <AlertDialogTitle>{{ title }}</AlertDialogTitle>
        <AlertDialogDescription>{{ description }}</AlertDialogDescription>
      </AlertDialogHeader>
      <AlertDialogFooter>
        <button @click="onCancel" class="btn px-4 py-2 rounded-md bg-red-700 text-white">Cancelar</button>
        <button @click="onConfirm" class="btn px-4 py-2 rounded-md bg-green-700 text-white">Confirmar</button>
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
});

const emit = defineEmits(["confirm", "cancel"]);

function onConfirm() {
  emit("confirm");
}

function onCancel() {
  emit("cancel");
}
</script>