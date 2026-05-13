<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import ProductCard from '@/components/ProductCard.vue'

defineOptions({ layout: AppLayout })

const props = defineProps<{
    featuredProducts: {
        slug: string
        name: string
        brand: string
        image: string
        price: number | null
        sale_price: number | null
        size_label: string | null
        badge: string | null
    }[]
    brands: { name: string; logo: string | null }[]
}>()

const categories = [
    {
        label: 'Muški parfemi',
        image: '/assets/img/pexels-diun-group-1148420145-21234956.webp',
        href: '/parfemi',
    },
    {
        label: 'Ženski parfemi',
        image: '/assets/img/pexels-laurachouette-22589353.webp',
        href: '/parfemi',
    },
    {
        label: 'Uniseks / Pokloni',
        image: '/assets/img/pexels-rehman-yousaf-321165099-14490634.webp',
        href: '/parfemi',
    },
]

function formatPrice(price: number | null): string {
    if (!price) return ''
    return new Intl.NumberFormat('sr-RS').format(price) + ' RSD'
}

function getOriginalPrice(product: typeof props.featuredProducts[0]): string | undefined {
    if (product.sale_price && product.price) {
        return formatPrice(product.price)
    }
    return undefined
}
</script>

<template>
    <Head title="Početna" />

    <!-- Hero -->
    <section class="relative flex h-[85vh] min-h-[600px] items-center overflow-hidden">
        <div class="absolute inset-0">
            <img
                src="/assets/img/pexels-suhashanjar-36779954.webp"
                alt="Luksuzni parfem"
                class="h-full w-full object-cover animate-ken-burns"
            />
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/30 to-transparent" />
        </div>

        <div class="relative z-10 mx-auto w-full max-w-7xl px-6 lg:px-8">
            <div class="max-w-2xl">
                <p class="mb-4 text-sm font-medium tracking-[0.3em] text-white/70 uppercase">
                    dndparfems
                </p>
                <h1 class="font-serif text-5xl font-medium leading-[1.1] tracking-wide text-white lg:text-7xl">
                    Otkrij svoj<br />
                    <span class="italic">potpisni miris</span>
                </h1>
                <p class="mt-6 max-w-md text-base leading-relaxed text-white/60 lg:text-lg">
                    Pažljivo odabrana kolekcija luksuznih parfema koji govore više od reči.
                </p>
                <div class="mt-10">
                    <a
                        href="/parfemi"
                        class="inline-block border border-white/30 bg-white/10 px-10 py-4 text-sm font-medium tracking-[0.2em] text-white uppercase backdrop-blur-sm transition-all hover:border-white hover:bg-white hover:text-gray-900"
                    >
                        Pogledaj kolekciju
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="bg-gray-50">
        <div class="mx-auto max-w-7xl px-6 py-24 lg:px-8">
            <h2 class="mb-12 text-center font-serif text-3xl font-medium tracking-wide text-gray-900 lg:text-4xl">
                Istaknuti proizvodi
            </h2>
            <div class="grid grid-cols-2 gap-x-6 gap-y-10 md:grid-cols-4">
                <ProductCard
                    v-for="product in featuredProducts"
                    :key="product.slug"
                    :image="product.image"
                    :brand="product.brand"
                    :name="product.name + (product.size_label ? ' ' + product.size_label : '')"
                    :price="formatPrice(product.sale_price ?? product.price)"
                    :original-price="getOriginalPrice(product)"
                    :href="`/parfemi/${product.slug}`"
                    :badge="product.badge ?? undefined"
                />
            </div>
        </div>
    </section>

    <!-- Brands -->
    <section class="relative overflow-hidden">
        <div class="pointer-events-none absolute inset-y-0 left-0 z-10 w-24 bg-gradient-to-r from-white to-transparent sm:w-32" />
        <div class="pointer-events-none absolute inset-y-0 right-0 z-10 w-24 bg-gradient-to-l from-white to-transparent sm:w-32" />

        <div class="mx-auto max-w-7xl px-6 py-24 lg:px-8">
            <div class="flex overflow-hidden">
                <div class="flex shrink-0 animate-marquee items-center gap-28 will-change-transform mr-28">
                    <div
                        v-for="brand in brands"
                        :key="brand.name"
                        class="flex shrink-0 items-center justify-center px-4"
                    >
                        <img
                            v-if="brand.logo"
                            :src="brand.logo"
                            :alt="brand.name"
                            class="h-20 max-w-[220px] opacity-60 grayscale transition-all duration-300 hover:opacity-100 hover:grayscale-0"
                        />
                        <span v-else class="font-serif text-xl font-medium text-gray-900">{{ brand.name }}</span>
                    </div>
                </div>
                <div class="flex shrink-0 animate-marquee items-center gap-28 will-change-transform" aria-hidden="true">
                    <div
                        v-for="brand in brands"
                        :key="`${brand.name}-dup`"
                        class="flex shrink-0 items-center justify-center px-4"
                    >
                        <img
                            v-if="brand.logo"
                            :src="brand.logo"
                            :alt="brand.name"
                            class="h-20 max-w-[220px] opacity-60 grayscale transition-all duration-300 hover:opacity-100 hover:grayscale-0"
                        />
                        <span v-else class="font-serif text-xl font-medium text-gray-900">{{ brand.name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop by Category -->
    <section class="bg-gray-50">
        <div class="mx-auto max-w-7xl px-6 py-24 lg:px-8">
            <h2 class="mb-12 text-center font-serif text-3xl font-medium tracking-wide text-gray-900 lg:text-4xl">
                Kupuj po kategoriji
            </h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <a
                    v-for="category in categories"
                    :key="category.label"
                    :href="category.href"
                    class="group relative aspect-[4/5] overflow-hidden"
                >
                    <img
                        :src="category.image"
                        :alt="category.label"
                        class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                    />
                    <div class="absolute inset-0 bg-black/30 transition-colors group-hover:bg-black/40" />
                    <div class="absolute inset-0 flex items-center justify-center">
                        <h3 class="text-2xl font-medium tracking-widest text-white uppercase">
                            {{ category.label }}
                        </h3>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Brand Story -->
    <section class="bg-gray-50">
        <div class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
            <div class="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
                <div class="aspect-[4/5] overflow-hidden lg:aspect-auto lg:h-[600px]">
                    <img
                        src="/assets/img/pexels-isidor-bobinec-94539949-9202888.webp"
                        alt="dndparfems kolekcija"
                        class="h-full w-full object-cover"
                    />
                </div>
                <div class="lg:pl-12">
                    <p class="mb-4 text-xs font-medium tracking-[0.2em] text-gray-500 uppercase">
                        Naša priča
                    </p>
                    <h2 class="font-serif text-4xl font-medium leading-[1.15] text-gray-900 lg:text-5xl">
                        Miris koji<br />priča priču
                    </h2>
                    <p class="mt-6 max-w-md text-base leading-relaxed text-gray-600">
                        Svaki parfem u našoj kolekciji pažljivo je odabran kako bismo ti doneli najfinije mirise sa svih strana sveta. Verujemo da je parfem više od dodatka — to je izraz tvoje ličnosti, uspomene koje nosiš sa sobom i trenutak luksuza u svakodnevnici.
                    </p>
                    <div class="mt-10">
                        <a
                            href="/parfemi"
                            class="inline-block border border-gray-900 bg-gray-900 px-10 py-4 text-sm font-medium tracking-[0.2em] text-white uppercase transition-all hover:bg-white hover:text-gray-900"
                        >
                            Pogledaj kolekciju
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="mx-auto max-w-7xl px-6 py-24 lg:px-8">
        <div class="mx-auto max-w-xl text-center">
            <h2 class="font-serif text-3xl font-medium text-gray-900 lg:text-4xl">
                Budi u toku
            </h2>
            <p class="mt-4 text-base leading-relaxed text-gray-500">
                Prvi saznaj za nove mirise, ekskluzivne kolekcije i specijalne ponude.
            </p>
            <form class="mt-8 flex flex-col gap-4 sm:flex-row">
                <input
                    type="email"
                    placeholder="Tvoja email adresa"
                    class="flex-1 border-b border-gray-300 bg-transparent px-4 py-3 text-sm text-gray-900 placeholder-gray-400 outline-none transition-colors focus:border-gray-900"
                />
                <button
                    type="submit"
                    class="border border-gray-900 bg-gray-900 px-8 py-3 text-sm font-medium tracking-widest text-white uppercase transition-all hover:bg-white hover:text-gray-900"
                >
                    Prijavi se
                </button>
            </form>
            <p class="mt-4 text-xs text-gray-400">
                Ne šaljemo spam. Možeš se odjaviti u bilo kom trenutku.
            </p>
        </div>
    </section>
</template>

<style scoped>
@keyframes ken-burns {
    0% {
        transform: scale(1);
    }
    100% {
        transform: scale(1.08);
    }
}

.animate-ken-burns {
    animation: ken-burns 20s ease-out forwards;
}

@keyframes marquee {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-100%);
    }
}

.animate-marquee {
    animation: marquee 30s linear infinite;
}
</style>
