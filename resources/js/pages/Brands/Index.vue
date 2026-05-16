<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import PageContainer from '@/components/PageContainer.vue'
import SectionHeader from '@/components/SectionHeader.vue'
import type { BrandWithCount } from '@/types'
import productRoutes from '@/routes/products'

defineOptions({ layout: AppLayout })

defineProps<{
    brands: BrandWithCount[]
}>()
</script>

<template>
    <Head title="Brendovi" />

    <PageContainer>
        <SectionHeader
            title="Brendovi"
            subtitle="Otkrij luksuzne brendove u našoj kolekciji"
            margin-bottom="large"
        />

        <div class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4">
            <Link
                v-for="brand in brands"
                :key="brand.id"
                :href="productRoutes.index.url({ query: { brands: [brand.slug] } })"
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
            </Link>
        </div>
    </PageContainer>
</template>
