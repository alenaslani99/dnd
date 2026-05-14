<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { formatPrice } from '@/lib/utils'
import type { ProductDetail } from '@/types'
import productRoutes from '@/routes/products'
import cartRoutes from '@/routes/cart'

defineOptions({ layout: AppLayout })

const props = defineProps<{
    product: ProductDetail
}>()

const selectedVariant = ref(
    props.product.variants.find(v => v.is_available)?.id ?? null
)

const activeVariant = computed(() => {
    return props.product.variants.find(v => v.id === selectedVariant.value) ?? props.product.variants[0]
})

const activeImage = ref(props.product.images.find(img => img.is_primary)?.path ?? props.product.images[0]?.path)

function hasPromotion(variant: ProductDetail['variants'][0]): boolean {
    return !!variant.sale_price
}

const cartForm = useForm({
    product_variant_id: selectedVariant.value ?? 0,
    quantity: 1,
})

function selectVariant(variantId: number) {
    const variant = props.product.variants.find(v => v.id === variantId)
    if (variant && !variant.is_available) return
    selectedVariant.value = variantId
}

function addToCart() {
    if (!selectedVariant.value) return
    const variant = props.product.variants.find(v => v.id === selectedVariant.value)
    if (!variant?.is_available) return

    cartForm.product_variant_id = selectedVariant.value
    cartForm.post(cartRoutes.store.url(), {
        preserveScroll: true,
        onSuccess: () => {
            cartForm.reset()
        },
    })
}
</script>

<template>
    <Head :title="product.name" />

    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 sm:py-16 lg:px-8">
        <div class="grid grid-cols-1 gap-6 sm:gap-12 lg:grid-cols-2 lg:gap-20">
            <!-- Images -->
            <div class="space-y-3 sm:space-y-4">
                <div class="aspect-[3/4] overflow-hidden bg-gray-100">
                    <img
                        :src="activeImage"
                        :alt="product.name"
                        class="h-full w-full object-cover"
                    />
                </div>
                <div
                    v-if="product.images.length > 1"
                    class="flex gap-2 overflow-x-auto pb-1 scrollbar-hide sm:gap-3"
                >
                    <button
                        v-for="image in product.images"
                        :key="image.path"
                        @click="activeImage = image.path"
                        class="aspect-square h-16 shrink-0 overflow-hidden border-2 transition-colors sm:h-20"
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
                <h1 class="mt-2 font-serif text-2xl font-medium tracking-wide text-gray-900 sm:text-3xl lg:text-4xl">
                    {{ product.name }}
                </h1>

                <div class="mt-4 flex items-center gap-3 sm:mt-6">
                    <p
                        v-if="activeVariant?.sale_price"
                        class="text-xl font-medium text-red-600 sm:text-2xl"
                    >
                        {{ formatPrice(activeVariant.sale_price) }}
                    </p>
                    <p
                        class="text-xl font-medium sm:text-2xl"
                        :class="activeVariant?.sale_price ? 'text-gray-400 line-through text-base sm:text-lg' : 'text-gray-900'"
                    >
                        {{ formatPrice(activeVariant?.price) }}
                    </p>
                </div>

                <!-- Size Selector -->
                <div class="mt-8 sm:mt-10">
                    <p class="mb-3 text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                        Veličina
                    </p>
                    <div class="flex flex-wrap gap-2 sm:gap-3">
                        <button
                            v-for="variant in product.variants"
                            :key="variant.id"
                            :disabled="!variant.is_available"
                            @click="selectVariant(variant.id)"
                            class="border px-4 py-2.5 text-xs font-medium tracking-wider uppercase transition-all sm:px-6 sm:py-3 sm:text-sm"
                            :class="[
                                selectedVariant === variant.id
                                    ? 'border-gray-900 bg-gray-900 text-white'
                                    : variant.is_available
                                        ? 'border-gray-300 text-gray-700 hover:border-gray-900'
                                        : 'border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed',
                            ]"
                        >
                            {{ variant.size_label }}
                            <span
                                v-if="!variant.is_available"
                                class="ml-1 text-[10px] tracking-wider uppercase opacity-70"
                            >
                                Rasprodato
                            </span>
                            <span
                                v-else-if="hasPromotion(variant)"
                                class="ml-1 text-[10px] font-bold tracking-wider uppercase"
                                :class="selectedVariant === variant.id ? 'text-red-300' : 'text-red-600'"
                            >
                                -Akcija
                            </span>
                        </button>
                    </div>
                </div>

                <!-- SKU -->
                <p class="mt-3 text-xs text-gray-400 sm:mt-4">
                    Šifra: {{ activeVariant?.sku }}
                </p>

                <!-- Add to Cart -->
                <button
                    type="button"
                    :disabled="!selectedVariant || !activeVariant?.is_available || cartForm.processing"
                    @click="addToCart"
                    class="mt-8 w-full border border-gray-900 bg-gray-900 px-6 py-3.5 text-xs font-medium tracking-[0.2em] text-white uppercase transition-all hover:bg-white hover:text-gray-900 disabled:opacity-50 sm:mt-10 sm:px-8 sm:py-4 sm:text-sm"
                >
                    {{ cartForm.processing ? 'Dodavanje...' : 'Dodaj u korpu' }}
                </button>

                <!-- Description -->
                <div class="mt-10 border-t border-gray-100 pt-8 sm:mt-14 sm:pt-10">
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

<style scoped>
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
</style>
