<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import FilterButton from '@/components/FilterButton.vue'
import PageContainer from '@/components/PageContainer.vue'
import ProductCard from '@/components/ProductCard.vue'
import SectionHeader from '@/components/SectionHeader.vue'
import type { ProductListItem, ProductFilters, Brand } from '@/types'
import productRoutes from '@/routes/products'

defineOptions({ layout: AppLayout })

const props = defineProps<{
    products: {
        data: ProductListItem[]
        current_page: number
        last_page: number
        prev_page_url: string | null
        next_page_url: string | null
    }
    total_count: string
    filters: ProductFilters
    brands: Brand[]
    sizes: string[]
    genders: { value: string; label: string }[]
    shouldNoindex?: boolean
}>()

const showFilters = ref(false)

const selectedBrands = computed({
    get: () => props.filters.brands ?? [],
    set: () => applyFilters(),
})

const selectedSizes = computed({
    get: () => props.filters.sizes ?? [],
    set: () => applyFilters(),
})

const selectedGenders = computed({
    get: () => props.filters.genders ?? [],
    set: () => applyFilters(),
})

const selectedSort = ref(props.filters.sort ?? 'newest')

watch(() => props.filters.sort, (newSort) => {
    selectedSort.value = newSort ?? 'newest'
})

watch(selectedSort, () => {
    applyFilters()
})

function toggleFilter(list: string[], value: string) {
    const idx = list.indexOf(value)
    if (idx > -1) {
        list.splice(idx, 1)
    } else {
        list.push(value)
    }
    applyFilters()
}

function applyFilters() {
    router.get(productRoutes.index.url(), {
        brands: selectedBrands.value.length > 0 ? selectedBrands.value : undefined,
        sizes: selectedSizes.value.length > 0 ? selectedSizes.value : undefined,
        genders: selectedGenders.value.length > 0 ? selectedGenders.value : undefined,
        sort: selectedSort.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
}

function clearFilters() {
    router.get(productRoutes.index.url(), {}, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
}

const activeFiltersCount = computed(() => {
    let count = 0
    if (selectedBrands.value.length > 0) count += selectedBrands.value.length
    if (selectedSizes.value.length > 0) count += selectedSizes.value.length
    if (selectedGenders.value.length > 0) count += selectedGenders.value.length
    if (selectedSort.value !== 'newest') count++
    return count
})

const sortOptions = [
    { value: 'newest', label: 'Najnovije' },
    { value: 'price_asc', label: 'Cena: od najniže' },
    { value: 'price_desc', label: 'Cena: od najviše' },
    { value: 'name_asc', label: 'Naziv: A-Z' },
    { value: 'name_desc', label: 'Naziv: Z-A' },
]
</script>

<template>
    <Head title="Parfemi — dndparfems">
        <meta name="description" content="Kupi luksuzne parfeme online | dndparfems. Pažljivo odabrana kolekcija muških, ženskih i uniseks mirisa." />
        <meta v-if="shouldNoindex" name="robots" content="noindex, follow" />
    </Head>

    <PageContainer>
        <SectionHeader
            title="Parfemi"
            subtitle="Pažljivo odabrana kolekcija luksuznih parfema"
        />

        <!-- Toolbar -->
        <div class="mb-10 flex flex-wrap items-center justify-between gap-4">
            <p class="text-sm text-gray-500">
                {{ total_count }} proizvoda
            </p>

            <div class="flex items-center gap-3">
                <!-- Filter Toggle -->
                <button
                    type="button"
                    class="flex cursor-pointer items-center gap-2 border border-gray-200 px-4 py-2.5 text-xs font-medium tracking-[0.1em] text-gray-700 uppercase transition-colors hover:border-gray-900 hover:text-gray-900"
                    :class="showFilters ? 'border-gray-900 text-gray-900' : ''"
                    @click="showFilters = !showFilters"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" /></svg>
                    Filteri
                    <span
                        v-if="activeFiltersCount > 0"
                        class="flex h-4 w-4 items-center justify-center rounded-full bg-gray-900 text-[9px] font-bold text-white"
                    >
                        {{ activeFiltersCount }}
                    </span>
                </button>

                <!-- Sort -->
                <div class="relative">
                    <select
                        v-model="selectedSort"
                        class="cursor-pointer appearance-none border border-gray-200 bg-white pr-10 pl-4 py-2.5 text-xs font-medium tracking-[0.1em] text-gray-700 uppercase outline-none transition-colors hover:border-gray-900"
                    >
                        <option
                            v-for="option in sortOptions"
                            :key="option.value"
                            :value="option.value"
                        >
                            {{ option.label }}
                        </option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6" /></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Panel -->
        <Transition name="filter-panel">
            <div
                v-show="showFilters"
                class="mb-10 border border-gray-100 bg-gray-50 px-6 py-6 overflow-hidden"
            >
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <!-- Brand Filter -->
                    <div>
                        <label class="mb-3 block text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                            Brend
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <FilterButton
                                v-for="brand in brands"
                                :key="brand.id"
                                :label="brand.name"
                                :active="selectedBrands.includes(brand.slug)"
                                @toggle="toggleFilter(selectedBrands, brand.slug)"
                            />
                        </div>
                    </div>

                    <!-- Size Filter -->
                    <div>
                        <label class="mb-3 block text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                            Veličina
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <FilterButton
                                v-for="size in sizes"
                                :key="size"
                                :label="size"
                                :active="selectedSizes.includes(size)"
                                @toggle="toggleFilter(selectedSizes, size)"
                            />
                        </div>
                    </div>

                    <!-- Gender Filter -->
                    <div>
                        <label class="mb-3 block text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                            Pol
                        </label>
                        <div class="flex flex-wrap gap-2">
                            <FilterButton
                                v-for="gender in genders"
                                :key="gender.value"
                                :label="gender.label"
                                :active="selectedGenders.includes(gender.value)"
                                @toggle="toggleFilter(selectedGenders, gender.value)"
                            />
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-4 border-t border-gray-200 pt-4">
                    <button
                        type="button"
                        class="text-xs font-medium tracking-[0.1em] text-gray-500 uppercase transition-colors hover:text-red-600"
                        @click="clearFilters"
                    >
                        Očisti filtere
                    </button>
                </div>
            </div>
        </Transition>

        <!-- Product Grid -->
        <div class="grid grid-cols-2 gap-x-6 gap-y-10 md:grid-cols-3 lg:grid-cols-4">
            <ProductCard
                v-for="product in products.data"
                :key="product.id"
                :image="product.image"
                :brand="product.brand"
                :name="product.name + (product.size_label ? ' ' + product.size_label : '')"
                :price="product.sale_price || product.price"
                :original-price="product.sale_price ? product.price : undefined"
                :href="productRoutes.show.url(product.slug)"
                :badge="product.badge ?? undefined"
            />
        </div>

        <!-- Pagination -->
        <div v-if="products.last_page > 1" class="mt-14 flex items-center justify-center gap-4">
            <Link
                v-if="products.prev_page_url"
                :href="products.prev_page_url"
                preserve-state
                class="border border-gray-200 px-6 py-2.5 text-xs font-medium tracking-[0.1em] text-gray-700 uppercase transition-colors hover:border-gray-900 hover:text-gray-900"
            >
                Prethodna
            </Link>
            <span class="text-xs text-gray-400">
                Strana {{ products.current_page }} / {{ products.last_page }}
            </span>
            <Link
                v-if="products.next_page_url"
                :href="products.next_page_url"
                preserve-state
                class="border border-gray-200 px-6 py-2.5 text-xs font-medium tracking-[0.1em] text-gray-700 uppercase transition-colors hover:border-gray-900 hover:text-gray-900"
            >
                Sledeća
            </Link>
        </div>

        <!-- Empty State -->
        <div v-if="products.data.length === 0" class="py-20 text-center">
            <p class="text-lg text-gray-400">
                Nema proizvoda koji odgovaraju izabranim filterima.
            </p>
            <button
                type="button"
                class="mt-4 text-sm font-medium tracking-[0.1em] text-red-600 underline-offset-4 transition-colors hover:text-red-700 hover:underline uppercase"
                @click="clearFilters"
            >
                Očisti filtere
            </button>
        </div>
    </PageContainer>
</template>

<style scoped>
.filter-panel-enter-active,
.filter-panel-leave-active {
    transition: max-height 400ms ease, opacity 400ms ease, margin 400ms ease;
    max-height: 500px;
}

.filter-panel-enter-from,
.filter-panel-leave-to {
    max-height: 0;
    opacity: 0;
    margin-top: 0;
    margin-bottom: 0;
    padding-top: 0;
    padding-bottom: 0;
    border-width: 0;
}
</style>
