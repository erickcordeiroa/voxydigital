<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import {defineProps, ref} from 'vue';

import AppearanceTabs from '@/components/AppearanceTabs.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import { type BreadcrumbItem } from '@/types';

import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';

const breadcrumbItems: BreadcrumbItem[] = [
    {
        title: 'Aparência',
        href: '/settings/appearance',
    },
];

const props = defineProps<{
  tenant: Object
}>();

const tenant = ref(props.tenant);
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbItems">
  <Head title="Aparência" />

    <SettingsLayout>
      <div class="space-y-6">
        <HeadingSmall
          title="Aparência"
          description="Atualize as informações da sua empresa"
        />
        <AppearanceTabs :tenant="tenant" />
      </div>
    </SettingsLayout>
  </AppLayout>
</template>
