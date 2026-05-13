<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

defineProps<{
    brands: {
        id: number
        name: string
        slug: string
        logo: string | null
        products_count: number
    }[]
}>()
</script>

<template>
    <Head title="Brendovi" />

    <section class="mx-auto max-w-7xl px-6 py-24 lg:px-8">
        <div class="mb-16 text-center">
            <h1 class="font-serif text-4xl font-medium tracking-wide text-gray-900 lg:text-5xl">
                Brendovi
            </h1>
            <p class="mt-4 text-base text-gray-500">
                Otkrij luksuzne brendove u našoj kolekciji
            </p>
        </div>

        <div class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4">
            <a
                v-for="brand in brands"
                :key="brand.id"
                :href="`/brendovi/${brand.slug}`"
                class="group flex flex-col items-center justify-center gap-6 border border-gray-100 bg-white px-8 py-14 transition-all duration-300 hover:border-gray-300 hover:shadow-sm"
            >
                <img
                    v-if="brand.logo"
                    :src="brand.logo"
                    :alt="brand.name"
                    class="h-12 max-w-[160px] opacity-60 grayscale transition-all duration-300 group-hover:opacity-100 group-hover:grayscale-0"
                />
                <span v-else class="font-serif text-2xl font-medium text-gray-900">
                    {{ brand.name }}
                </span>
                <span class="text-[11px] font-medium tracking-[0.2em] text-gray-400 uppercase transition-colors group-hover:text-gray-900">
                    {{ brand.products_count }} proizvoda
                </span>
            </a>
        </div>
    </section>
</template>
