<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import AppImage from '@/components/AppImage.vue'
import PageContainer from '@/components/PageContainer.vue'
import PriceDisplay from '@/components/PriceDisplay.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
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

const productDescription = computed(() => {
    return props.product.description.length > 160
        ? props.product.description.substring(0, 157).trimEnd() + '...'
        : props.product.description
})

const schemaString = computed(() => {
    const schema: Record<string, unknown> = {
        '@context': 'https://schema.org',
        '@type': 'Product',
        name: props.product.name,
        description: props.product.description,
        brand: {
            '@type': 'Brand',
            name: props.product.brand,
        },
    }

    const primaryImage = props.product.images.find(img => img.is_primary) ?? props.product.images[0]
    if (primaryImage) {
        schema.image = primaryImage.path
    }

    const offers = props.product.variants
        .filter(v => v.is_available)
        .map(variant => {
            const priceRaw = variant.sale_price || variant.price || ''
            const priceNum = parseFloat(priceRaw.replace(/[^\d,]/g, '').replace(',', '.'))

            const offer: Record<string, unknown> = {
                '@type': 'Offer',
                url: productRoutes.show.url(props.product.slug),
                priceCurrency: 'RSD',
                price: isNaN(priceNum) ? 0 : priceNum,
                availability: variant.is_available ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                itemCondition: 'https://schema.org/NewCondition',
            }

            if (variant.sku) {
                offer.sku = variant.sku
            }

            if (variant.size_label) {
                offer.name = variant.size_label
            }

            return offer
        })

    if (offers.length > 0) {
        schema.offers = offers.length === 1 ? offers[0] : offers
    }

    return JSON.stringify(schema)
})

const faqSchemaString = computed(() => {
    const questions = [
        {
            '@type': 'Question',
            name: 'Da li je parfem ' + props.product.name + ' originalan?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Da, svi proizvodi na dndparfems su 100% originalni. Uvozimo ih isključivo od ovlašćenih distributera i brendova.',
            },
        },
        {
            '@type': 'Question',
            name: 'Koja je razlika između EDT i EDP kod ovog parfema?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'EDT (Eau de Toilette) ima 5-15% mirisnog ulja i traje 3-5 sati. EDP (Eau de Parfum) ima 15-20% mirisnog ulja i traje 6-8 sati. EDP ima intenzivniji miris i dužu postojanost.',
            },
        },
        {
            '@type': 'Question',
            name: 'Koji su dostupni formati (veličine) za ' + props.product.name + '?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Dostupne veličine su: ' + props.product.variants.map(v => v.size_label).filter(Boolean).join(', ') + '. Na lageru su samo navedene varijante.',
            },
        },
        {
            '@type': 'Question',
            name: 'Koliko košta dostava za parfem?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Dostava za celu Srbiju iznosi 500 RSD. Besplatna je za porudžbine preko 5.000 RSD. Isporuka traje 2-5 radnih dana.',
            },
        },
        {
            '@type': 'Question',
            name: 'Kako mogu da platim?',
            acceptedAnswer: {
                '@type': 'Answer',
                text: 'Plaćanje se vrši pouzećem (pouzeće) prilikom preuzimanja pošiljke. Plaćate u gotovini kuriru prilikom dostave.',
            },
        },
    ]

    return JSON.stringify({
        '@context': 'https://schema.org',
        '@type': 'FAQPage',
        mainEntity: questions,
    })
})

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
    <Head :title="`${product.name} — dndparfems`">
        <meta name="description" :content="productDescription" />
    </Head>

    <component :is="'script'" type="application/ld+json" :key="`${product.slug}-product`" v-text="schemaString" />
    <component :is="'script'" type="application/ld+json" :key="`${product.slug}-faq`" v-text="faqSchemaString" />

    <PageContainer padding="product">
        <div class="grid grid-cols-1 gap-6 sm:gap-12 lg:grid-cols-2 lg:gap-20">
            <!-- Images -->
            <div class="space-y-3 sm:space-y-4">
                <div class="aspect-[3/4] overflow-hidden bg-gray-100">
                    <AppImage
                        :src="activeImage"
                        :alt="product.name"
                        sizes="(max-width: 1024px) 100vw, 50vw"
                        priority
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
                        <AppImage
                            :src="image.path"
                            :alt="image.alt ?? product.name"
                            sizes="80px"
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

                <PriceDisplay
                    class="mt-4 sm:mt-6"
                    :price="activeVariant?.price"
                    :sale-price="activeVariant?.sale_price"
                />

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
                <PrimaryButton
                    type="button"
                    variant="full"
                    size="small"
                    class="mt-8 sm:mt-10"
                    :disabled="!selectedVariant || !activeVariant?.is_available || cartForm.processing"
                    :loading="cartForm.processing"
                    loading-text="Dodavanje..."
                    @click="addToCart"
                >
                    Dodaj u korpu
                </PrimaryButton>

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
    </PageContainer>
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
