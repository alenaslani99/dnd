<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import { computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import ProductCard from '@/components/ProductCard.vue'

defineOptions({ layout: AppLayout })

const props = defineProps<{
    products: {
        id: number
        slug: string
        name: string
        brand: string
        image: string
        price: number | null
        sale_price: number | null
        size_label: string | null
    }[]
    filters: {
        brands: string[]
        sizes: string[]
        sort: string
    }
    brands: { id: number; name: string; slug: string }[]
    sizes: string[]
}>()

const selectedBrands = computed({
    get: () => props.filters.brands ?? [],
    set: (val: string[]) => applyFilters(),
})

const selectedSizes = computed({
    get: () => props.filters.sizes ?? [],
    set: (val: string[]) => applyFilters(),
})

const selectedSort = computed({
    get: () => props.filters.sort ?? 'newest',
    set: (val: string) => applyFilters(),
})

function toggleBrand(slug: string) {
    const idx = selectedBrands.value.indexOf(slug)
    if (idx > -1) {
        selectedBrands.value.splice(idx, 1)
    } else {
        selectedBrands.value.push(slug)
    }
    applyFilters()
}

function toggleSize(size: string) {
    const idx = selectedSizes.value.indexOf(size)
    if (idx > -1) {
        selectedSizes.value.splice(idx, 1)
    } else {
        selectedSizes.value.push(size)
    }
    applyFilters()
}

function applyFilters() {
    router.get('/parfemi', {
        brands: selectedBrands.value.length > 0 ? selectedBrands.value : undefined,
        sizes: selectedSizes.value.length > 0 ? selectedSizes.value : undefined,
        sort: selectedSort.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
}

function clearFilters() {
    router.get('/parfemi', {}, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
}

const activeFiltersCount = computed(() => {
    let count = 0
    if (selectedBrands.value.length > 0) count += selectedBrands.value.length
    if (selectedSizes.value.length > 0) count += selectedSizes.value.length
    if (selectedSort.value !== 'newest') count++
    return count
})

function formatPrice(price: number | null): string {
    if (!price) return ''
    return new Intl.NumberFormat('sr-RS').format(price) + ' RSD'
}

function getBadge(product: { sale_price: number | null }): string | undefined {
    if (product.sale_price) return 'Akcija'
    return undefined
}

const sortOptions = [
    { value: 'newest', label: 'Najnovije' },
    { value: 'price_asc', label: 'Cena: od najniže' },
    { value: 'price_desc', label: 'Cena: od najviše' },
    { value: 'name_asc', label: 'Naziv: A-Z' },
    { value: 'name_desc', label: 'Naziv: Z-A' },
]
</script>

<template>
    <Head title="Parfemi" />

    <section class="mx-auto max-w-7xl px-6 py-24 lg:px-8">
        <!-- Header -->
        <div class="mb-12 text-center">
            <h1 class="font-serif text-4xl font-medium tracking-wide text-gray-900 lg:text-5xl">
                Parfemi
            </h1>
            <p class="mt-4 text-base text-gray-500">
                Pažljivo odabrana kolekcija luksuznih parfema
            </p>
        </div>

        <!-- Toolbar -->
        <div class="mb-10 flex flex-wrap items-center justify-between gap-4">
            <p class="text-sm text-gray-500">
                {{ products.length }} proizvoda
            </p>

            <div class="flex items-center gap-3">
                <!-- Filter Toggle -->
                <button
                    type="button"
                    class="flex items-center gap-2 border border-gray-200 px-4 py-2.5 text-xs font-medium tracking-[0.1em] text-gray-700 uppercase transition-colors hover:border-gray-900 hover:text-gray-900"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    Filteri
                    <span
                        v-if="activeFiltersCount > 0"
                        class="flex h-4 w-4 items-center justify-center rounded-full bg-gray-900 text-[9px] font-bold text-white"
                    >
                        {{ activeFiltersCount }}
                    </span>
                </button>

                <!-- Sort -->
                <select
                    v-model="selectedSort"
                    class="cursor-pointer border border-gray-200 bg-white px-4 py-2.5 text-xs font-medium tracking-[0.1em] text-gray-700 uppercase outline-none transition-colors hover:border-gray-900"
                >
                    <option
                        v-for="option in sortOptions"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </option>
                </select>
            </div>
        </div>

        <!-- Filter Panel -->
        <div class="mb-10 border border-gray-100 bg-gray-50 px-6 py-6">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
                <!-- Brand Filter -->
                <div>
                    <label class="mb-3 block text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                        Brend
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="brand in brands"
                            :key="brand.id"
                            type="button"
                            @click="toggleBrand(brand.slug)"
                            class="border px-4 py-2 text-xs font-medium tracking-wider uppercase transition-all"
                            :class="selectedBrands.includes(brand.slug)
                                ? 'border-gray-900 bg-gray-900 text-white'
                                : 'border-gray-200 bg-white text-gray-700 hover:border-gray-900'
                            "
                        >
                            {{ brand.name }}
                        </button>
                    </div>
                </div>

                <!-- Size Filter -->
                <div>
                    <label class="mb-3 block text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                        Veličina
                    </label>
                    <div class="flex flex-wrap gap-2">
                        <button
                            v-for="size in sizes"
                            :key="size"
                            type="button"
                            @click="toggleSize(size)"
                            class="border px-4 py-2 text-xs font-medium tracking-wider uppercase transition-all"
                            :class="selectedSizes.includes(size)
                                ? 'border-gray-900 bg-gray-900 text-white'
                                : 'border-gray-200 bg-white text-gray-700 hover:border-gray-900'
                            "
                        >
                            {{ size }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center gap-4 border-t border-gray-200 pt-4">
                <button
                    type="button"
                    @click="clearFilters"
                    class="text-xs font-medium tracking-[0.1em] text-gray-500 uppercase transition-colors hover:text-red-600"
                >
                    Očisti filtere
                </button>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-2 gap-x-6 gap-y-10 md:grid-cols-3 lg:grid-cols-4">
            <ProductCard
                v-for="product in products"
                :key="product.id"
                :image="product.image"
                :brand="product.brand"
                :name="product.name + (product.size_label ? ' ' + product.size_label : '')"
                :price="formatPrice(product.sale_price ?? product.price)"
                :original-price="product.sale_price ? formatPrice(product.price) : undefined"
                :href="`/parfemi/${product.slug}`"
                :badge="getBadge(product)"
            />
        </div>

        <!-- Empty State -->
        <div v-if="products.length === 0" class="py-20 text-center">
            <p class="text-lg text-gray-400">Nema proizvoda koji odgovaraju izabranim filterima.</p>
            <button
                type="button"
                @click="clearFilters"
                class="mt-4 text-sm font-medium tracking-[0.1em] text-red-600 underline-offset-4 transition-colors hover:text-red-700 hover:underline uppercase"
            >
                Očisti filtere
            </button>
        </div>
    </section>
</template>
