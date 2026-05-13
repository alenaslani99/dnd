<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

defineProps<{
    image: string
    brand: string
    name: string
    price: string
    href?: string
    originalPrice?: string
    badge?: string
}>()
</script>

<template>
    <Link :href="href || '#'" class="group block">
        <div class="relative aspect-[3/4] overflow-hidden bg-gray-100">
            <img
                :src="image"
                :alt="name"
                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
            />
            <div
                v-if="badge"
                class="absolute top-3 left-3"
            >
                <span class="bg-white/90 px-3 py-1.5 text-[10px] font-medium tracking-[0.2em] text-gray-900 uppercase backdrop-blur-sm">
                    {{ badge }}
                </span>
            </div>
            <div class="absolute inset-0 flex items-center justify-center bg-black/0 transition-colors duration-500 group-hover:bg-black/10">
                <span class="translate-y-4 border border-white/40 bg-white/20 px-6 py-2.5 text-xs font-medium tracking-[0.15em] text-white uppercase opacity-0 backdrop-blur-md transition-all duration-500 group-hover:translate-y-0 group-hover:opacity-100">
                    Pogledaj
                </span>
            </div>
        </div>
        <div class="mt-5">
            <p class="text-[11px] font-medium tracking-[0.2em] text-gray-500 uppercase">
                {{ brand }}
            </p>
            <h3 class="mt-1.5 text-sm font-medium leading-snug text-gray-900">
                {{ name }}
            </h3>
            <div class="mt-2 flex items-center gap-2.5">
                <p
                    v-if="originalPrice"
                    class="text-sm text-gray-400 line-through"
                >
                    {{ originalPrice }}
                </p>
                <p class="text-sm font-medium text-gray-900">
                    {{ price }}
                </p>
            </div>
        </div>
    </Link>
</template>
