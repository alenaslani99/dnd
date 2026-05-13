<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

const categories = [
    {
        label: 'Muški parfemi',
        image: '/assets/img/pexels-diun-group-1148420145-21234956.webp',
        href: '#',
    },
    {
        label: 'Ženski parfemi',
        image: '/assets/img/pexels-laurachouette-22589353.webp',
        href: '#',
    },
    {
        label: 'Uniseks / Pokloni',
        image: '/assets/img/pexels-rehman-yousaf-321165099-14490634.webp',
        href: '#',
    },
]

const brands = [
    { name: 'Chanel', logo: '/assets/img/brands/chanel.svg' },
    { name: 'Dolce & Gabbana', logo: '/assets/img/brands/dolce-and-gabbana.svg' },
    { name: 'Giorgio Armani', logo: '/assets/img/brands/giorgio-armani.svg' },
    { name: 'Mancera', logo: '/assets/img/brands/Mancera.svg' },
    { name: 'Versace', logo: '/assets/img/brands/versace.svg' },
    { name: 'Yves Saint Laurent', logo: '/assets/img/brands/yves-saint-laurent.svg' },
]
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
                        href="#"
                        class="inline-block border border-white/30 bg-white/10 px-10 py-4 text-sm font-medium tracking-[0.2em] text-white uppercase backdrop-blur-sm transition-all hover:border-white hover:bg-white hover:text-gray-900"
                    >
                        Pogledaj kolekciju
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop by Category -->
    <section class="mx-auto max-w-7xl px-6 pt-20 pb-10 lg:px-8">
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
    </section>

    <!-- Brands -->
    <section class="bg-gray-50 overflow-hidden">
        <div class="mx-auto max-w-7xl px-6 py-16 lg:px-8">
            <div class="relative flex overflow-hidden">
                <div class="flex shrink-0 animate-marquee items-center gap-16">
                    <div
                        v-for="brand in brands"
                        :key="brand.name"
                        class="flex shrink-0 items-center justify-center px-4"
                    >
                        <img
                            :src="brand.logo"
                            :alt="brand.name"
                            class="h-16 max-w-[180px] opacity-60 grayscale transition-all duration-300 hover:opacity-100 hover:grayscale-0"
                        />
                    </div>
                </div>
                <div class="flex shrink-0 animate-marquee items-center gap-16" aria-hidden="true">
                    <div
                        v-for="brand in brands"
                        :key="brand.name"
                        class="flex shrink-0 items-center justify-center px-4"
                    >
                        <img
                            :src="brand.logo"
                            :alt="brand.name"
                            class="h-16 max-w-[180px] opacity-60 grayscale transition-all duration-300 hover:opacity-100 hover:grayscale-0"
                        />
                    </div>
                </div>
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
                            href="#"
                            class="inline-block border border-gray-900 bg-gray-900 px-10 py-4 text-sm font-medium tracking-[0.2em] text-white uppercase transition-all hover:bg-white hover:text-gray-900"
                        >
                            Saznaj više o nama
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
    animation: marquee 25s linear infinite;
}
</style>
