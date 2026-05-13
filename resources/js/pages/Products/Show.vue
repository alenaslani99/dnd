<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

const props = defineProps<{
    product: {
        id: number
        slug: string
        name: string
        description: string
        brand: string
        images: { path: string; alt: string | null; is_primary: boolean }[]
        variants: {
            id: number
            size_label: string | null
            sku: string
            price: number | null
            sale_price: number | null
        }[]
    }
}>()

const selectedVariant = ref(props.product.variants[0]?.id ?? null)

const activeVariant = computed(() => {
    return props.product.variants.find(v => v.id === selectedVariant.value) ?? props.product.variants[0]
})

const activeImage = ref(props.product.images.find(img => img.is_primary)?.path ?? props.product.images[0]?.path)

function formatPrice(price: number | null): string {
    if (!price) return ''
    return new Intl.NumberFormat('sr-RS').format(price) + ' RSD'
}

function hasPromotion(variant: typeof props.product.variants[0]): boolean {
    return !!variant.sale_price
}

const cartForm = useForm({
    product_variant_id: selectedVariant.value ?? 0,
    quantity: 1,
})

function addToCart() {
    if (!selectedVariant.value) return
    cartForm.product_variant_id = selectedVariant.value
    cartForm.post('/korpa')
}
</script>

<template>
    <Head :title="product.name" />

    <section class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
        <div class="grid grid-cols-1 gap-12 lg:grid-cols-2 lg:gap-20">
            <!-- Images -->
            <div class="space-y-4">
                <div class="aspect-[3/4] overflow-hidden bg-gray-100">
                    <img
                        :src="activeImage"
                        :alt="product.name"
                        class="h-full w-full object-cover"
                    />
                </div>
                <div v-if="product.images.length > 1" class="flex gap-3">
                    <button
                        v-for="image in product.images"
                        :key="image.path"
                        @click="activeImage = image.path"
                        class="aspect-square w-20 overflow-hidden border-2 transition-colors"
                        :class="activeImage === image.path ? 'border-gray-900' : 'border-transparent'"
                    >
                        <img
                            :src="image.path"
                            :alt="image.alt ?? product.name"
                            class="h-full w-full object-cover"
                        />
                    </button>
                </div>
            </div>

            <!-- Info -->
            <div class="flex flex-col justify-center">
                <p class="text-[11px] font-medium tracking-[0.2em] text-gray-500 uppercase">
                    {{ product.brand }}
                </p>
                <h1 class="mt-3 font-serif text-3xl font-medium tracking-wide text-gray-900 lg:text-4xl">
                    {{ product.name }}
                </h1>

                <div class="mt-6 flex items-center gap-3">
                    <p
                        v-if="activeVariant?.sale_price"
                        class="text-2xl font-medium text-red-600"
                    >
                        {{ formatPrice(activeVariant.sale_price) }}
                    </p>
                    <p
                        class="text-2xl font-medium"
                        :class="activeVariant?.sale_price ? 'text-gray-400 line-through text-lg' : 'text-gray-900'"
                    >
                        {{ formatPrice(activeVariant?.price) }}
                    </p>
                </div>

                <!-- Size Selector -->
                <div class="mt-10">
                    <p class="mb-3 text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                        Veličina
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <button
                            v-for="variant in product.variants"
                            :key="variant.id"
                            @click="selectedVariant = variant.id"
                            class="border px-6 py-3 text-sm font-medium tracking-wider uppercase transition-all"
                            :class="selectedVariant === variant.id
                                ? 'border-gray-900 bg-gray-900 text-white'
                                : 'border-gray-300 text-gray-700 hover:border-gray-900'
                            "
                        >
                            {{ variant.size_label }}
                            <span
                                v-if="hasPromotion(variant)"
                                class="ml-1 text-[10px] font-bold tracking-wider uppercase"
                                :class="selectedVariant === variant.id ? 'text-red-300' : 'text-red-600'"
                            >
                                -Akcija
                            </span>
                        </button>
                    </div>
                </div>

                <!-- SKU -->
                <p class="mt-4 text-xs text-gray-400">
                    Šifra: {{ activeVariant?.sku }}
                </p>

                <!-- Add to Cart -->
                <button
                    type="button"
                    :disabled="!selectedVariant || cartForm.processing"
                    @click="addToCart"
                    class="mt-10 w-full border border-gray-900 bg-gray-900 px-8 py-4 text-sm font-medium tracking-[0.2em] text-white uppercase transition-all hover:bg-white hover:text-gray-900 disabled:opacity-50"
                >
                    {{ cartForm.processing ? 'Dodavanje...' : 'Dodaj u korpu' }}
                </button>

                <!-- Description -->
                <div class="mt-14 border-t border-gray-100 pt-10">
                    <h2 class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                        Opis
                    </h2>
                    <p class="mt-4 text-sm leading-relaxed text-gray-600">
                        {{ product.description }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
