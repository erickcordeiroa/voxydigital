<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

interface Banner {
    id: number;
    title: string;
    description?: string;
    image: string;
    link_url?: string;
    link_text?: string;
}

interface Props {
    banners: Banner[];
}

const props = defineProps<Props>();

const currentSlide = ref(0);
let intervalId: NodeJS.Timeout | null = null;

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % props.banners.length;
};

const prevSlide = () => {
    currentSlide.value = currentSlide.value === 0 
        ? props.banners.length - 1 
        : currentSlide.value - 1;
};

const goToSlide = (index: number) => {
    currentSlide.value = index;
};

onMounted(() => {
    if (props.banners.length > 1) {
        intervalId = setInterval(nextSlide, 5000);
    }
});

onUnmounted(() => {
    if (intervalId) {
        clearInterval(intervalId);
    }
});
</script>

<template>
    <div v-if="banners.length > 0" class="relative mb-6 rounded-lg overflow-hidden">
        <div class="relative h-32 md:h-48 lg:h-80">
            <div
                v-for="(banner, index) in banners"
                :key="banner.id"
                :class="[
                    'absolute inset-0 transition-opacity duration-500',
                    index === currentSlide ? 'opacity-100' : 'opacity-0'
                ]"
            >
                <img
                    :src="`/storage/${banner.image}`"
                    :alt="banner.title"
                    class="w-full h-full object-cover"
                />
                <!-- <div class="absolute inset-0 bg-gradient-to-r from-black/50 to-transparent">
                    <div class="absolute bottom-4 left-4 text-white">
                        <h3 class="text-xl md:text-2xl font-bold mb-2">{{ banner.title }}</h3>
                        <p v-if="banner.description" class="text-sm md:text-base mb-3 max-w-md">
                            {{ banner.description }}
                        </p>
                        <a
                            v-if="banner.link_url"
                            :href="banner.link_url"
                            class="inline-block bg-[var(--custom-button)] text-[var(--custom-button-text)] px-4 py-2 rounded font-semibold hover:opacity-90 transition"
                        >
                            {{ banner.link_text || 'Ver mais' }}
                        </a>
                    </div>
                </div> -->
            </div>
        </div>

        <!-- Controles -->
        <div v-if="banners.length > 1">
            <!-- Setas -->
            <button
                @click="prevSlide"
                class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/30 text-white rounded-full p-2 transition"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            
            <button
                @click="nextSlide"
                class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/30 text-white rounded-full p-2 transition"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>

            <!-- Indicadores -->
            <div class="absolute bottom-2 left-1/2 -translate-x-1/2 flex space-x-2">
                <button
                    v-for="(banner, index) in banners"
                    :key="index"
                    @click="goToSlide(index)"
                    :class="[
                        'w-2 h-2 rounded-full transition',
                        index === currentSlide ? 'bg-white' : 'bg-white/50'
                    ]"
                />
            </div>
        </div>
    </div>
</template>