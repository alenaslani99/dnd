<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import Icon from '@/components/Icon.vue'
import adminProductsRoute from '@/routes/admin/products'
import { useDragScroll } from '@/composables/useDragScroll'

defineOptions({ layout: AdminLayout })

const props = defineProps<{
    products: {
        data: {
            id: number
            name: string
            brand: string
            type: string
            gender: string
            is_active: boolean
            price: string
            variants_count: number
        }[]
        current_page: number
        last_page: number
        prev_page_url: string | null
        next_page_url: string | null
        total: number
    }
    filters: {
        search: string | null
        sort: string
    }
}>()

const search = ref(props.filters.search ?? '')
const selectedSort = ref(props.filters.sort ?? 'newest')
const productToDelete = ref<{ id: number; name: string } | null>(null)
const isDeleting = ref(false)
const dragScroll = useDragScroll()

watch(search, () => {
    router.get(adminProductsRoute.index.url(), {
        search: search.value || undefined,
        sort: selectedSort.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
})

watch(selectedSort, () => {
    router.get(adminProductsRoute.index.url(), {
        search: search.value || undefined,
        sort: selectedSort.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
})

const sortOptions = [
    { value: 'newest', label: 'Najnovije' },
    { value: 'price_asc', label: 'Cena: od najniže' },
    { value: 'price_desc', label: 'Cena: od najviše' },
    { value: 'name_asc', label: 'Naziv: A-Z' },
    { value: 'name_desc', label: 'Naziv: Z-A' },
]

function typeLabel(type: string): string {
    return type === 'bundle' ? 'Paket' : 'Jedan'
}

function genderLabel(gender: string): string {
    const map: Record<string, string> = {
        male: 'Muški',
        female: 'Ženski',
        unisex: 'Uniseks',
    }
    return map[gender] ?? gender
}

function openDeleteModal(product: { id: number; name: string }): void {
    productToDelete.value = product
}

function closeDeleteModal(): void {
    productToDelete.value = null
}

function confirmDelete(): void {
    if (!productToDelete.value) {
        return
    }
    isDeleting.value = true
    router.delete(adminProductsRoute.destroy.url(productToDelete.value.id), {
        onFinish: () => {
            isDeleting.value = false
            closeDeleteModal()
        },
    })
}
</script>

<template>
    <Head title="Proizvodi" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="font-serif text-2xl font-medium tracking-wide text-gray-900 lg:text-3xl">
                Proizvodi
            </h1>
            <span class="text-sm text-gray-500">
                {{ products.total }} ukupno
            </span>
        </div>

        <!-- Toolbar -->
        <div class="flex flex-wrap items-center gap-4">
            <!-- Search -->
            <div class="relative flex-1 min-w-[16rem]">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <Icon name="search" :size="16" />
                </div>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Pretraži proizvode..."
                    class="w-full rounded-md border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none transition-colors placeholder:text-gray-400 focus:border-gray-900"
                />
            </div>

            <!-- Sort -->
            <div class="relative">
                <select
                    v-model="selectedSort"
                    class="cursor-pointer appearance-none rounded-md border border-gray-200 bg-white py-2.5 pr-10 pl-4 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
                >
                    <option
                        v-for="option in sortOptions"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6" /></svg>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-md border border-gray-200 bg-white">
            <div
                ref="dragScroll.el"
                class="overflow-x-auto cursor-grab"
                @mousedown="dragScroll.onMouseDown"
                @mouseleave="dragScroll.onMouseLeave"
                @mouseup="dragScroll.onMouseUp"
                @mousemove="dragScroll.onMouseMove"
            >
                <table class="w-full min-w-[52rem] text-left text-sm">
                    <thead class="bg-gray-50 text-xs font-semibold tracking-[0.1em] text-gray-500 uppercase">
                        <tr>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">ID</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Naziv</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Brend</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Tip</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Pol</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Cena</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Varijante</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Status</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 text-right">Akcije</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr
                            v-for="product in products.data"
                            :key="product.id"
                            class="transition-colors hover:bg-gray-50"
                        >
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 text-gray-500">#{{ product.id }}</td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 font-medium text-gray-900">{{ product.name }}</td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 text-gray-600">{{ product.brand }}</td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 text-gray-600">{{ typeLabel(product.type) }}</td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 text-gray-600">{{ genderLabel(product.gender) }}</td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 text-gray-900">{{ product.price }}</td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 text-gray-600">{{ product.variants_count }}</td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">
                                <span
                                    :class="[
                                        'inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium',
                                        product.is_active
                                            ? 'bg-green-50 text-green-700'
                                            : 'bg-gray-100 text-gray-600',
                                    ]"
                                >
                                    {{ product.is_active ? 'Aktivan' : 'Neaktivan' }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <button
                                        type="button"
                                        class="flex h-8 w-8 items-center justify-center rounded-md text-gray-400 transition-colors hover:bg-gray-100 hover:text-gray-600"
                                        title="Izmeni"
                                    >
                                        <Icon name="edit" :size="16" />
                                    </button>
                                    <button
                                        type="button"
                                        class="flex h-8 w-8 items-center justify-center rounded-md text-gray-400 transition-colors hover:bg-red-50 hover:text-red-600"
                                        title="Obriši"
                                        @click="openDeleteModal(product)"
                                    >
                                        <Icon name="trash" :size="16" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="products.last_page > 1" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-500">
                Strana {{ products.current_page }} od {{ products.last_page }}
            </p>
            <div class="flex items-center gap-2">
                <Link
                    v-if="products.prev_page_url"
                    :href="products.prev_page_url"
                    preserve-state
                    class="rounded-md border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:border-gray-900 hover:text-gray-900"
                >
                    Prethodna
                </Link>
                <Link
                    v-if="products.next_page_url"
                    :href="products.next_page_url"
                    preserve-state
                    class="rounded-md border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:border-gray-900 hover:text-gray-900"
                >
                    Sledeća
                </Link>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="products.data.length === 0" class="py-16 text-center">
            <p class="text-lg text-gray-400">Nema proizvoda koji odgovaraju pretrazi.</p>
        </div>
    </div>

    <!-- Delete Modal -->
    <Transition
        enter-active-class="transition-opacity duration-200"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div
            v-if="productToDelete"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
            @click.self="closeDeleteModal"
        >
            <div class="w-full max-w-sm rounded-lg border border-gray-200 bg-white p-6 shadow-xl">
                <div class="mb-4 flex items-center gap-3 text-red-600">
                    <Icon name="trash" :size="24" />
                    <h3 class="text-lg font-semibold text-gray-900">
                        Obrisati proizvod?
                    </h3>
                </div>
                <p class="mb-6 text-sm text-gray-600">
                    Da li ste sigurni da želite da obrišete proizvod
                    <strong class="text-gray-900">"{{ productToDelete.name }}"</strong>?
                    Proizvod će biti arhiviran, a porudžbine ostaju netaknute.
                </p>
                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        class="rounded-md border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:border-gray-900 hover:text-gray-900"
                        @click="closeDeleteModal"
                    >
                        Odustani
                    </button>
                    <button
                        type="button"
                        :disabled="isDeleting"
                        class="rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700 disabled:opacity-50"
                        @click="confirmDelete"
                    >
                        {{ isDeleting ? 'Brisanje...' : 'Obriši' }}
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>
