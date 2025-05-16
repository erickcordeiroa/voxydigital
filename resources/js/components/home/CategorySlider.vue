<template>
  <div class="overflow-x-auto mb-6">
    <ul class="flex gap-4">
      <li
        @click="onCategorySelect(null)"
        :class="{
          'bg-primary text-white': selectedCategory === null,
          'bg-gray-200 text-gray-800': selectedCategory !== null,
        }"
        class="px-4 py-2 rounded-md cursor-pointer whitespace-nowrap hover:bg-primary hover:text-white transition"
      >
        Todos os Itens

      </li>
      <li
        v-for="category in categories"
        :key="category.id"
        @click="onCategorySelect(category.id)"
        :class="{
          'bg-primary text-white': category.id === selectedCategory,
          'bg-gray-200 text-gray-800': category.id !== selectedCategory,
        }"
        class="px-4 py-2 rounded-md cursor-pointer whitespace-nowrap hover:bg-primary hover:text-white transition"
      >
        {{ category.name }}
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { defineProps, defineEmits } from "vue";

const props = defineProps({
  categories: Array,
  selectedCategory: Number,
});

const emit = defineEmits(["categorySelect"]);

const onCategorySelect = (categoryId) => {
  emit("categorySelect", categoryId);
};
</script>