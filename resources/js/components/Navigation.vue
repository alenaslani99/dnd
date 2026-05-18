<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { computed, onUnmounted, ref, watch } from 'vue'
import { navLinks } from '@/config/nav'
import { home, login, logout } from '@/routes'
import cart from '@/routes/cart'
import productRoutes from '@/routes/products'
import Icon from '@/components/Icon.vue'
import MobileMenu from '@/components/MobileMenu.vue'

const mobileMenuOpen = ref(false)
const activeDropdown = ref<number | null>(null)
const dropdownTimer = ref<ReturnType<typeof setTimeout> | null>(null)

const page = usePage()
const authUser = computed(() => page.props.auth?.user as { name: string; email: string } | null)

// Search state
const searchQuery = ref('')
const searchResults = ref<{ id: number; name: string; slug: string; brand: string; image: string | null; price: string }[]>([])
const searchLoading = ref(false)
const searchOpen = ref(false)
let searchDebounceTimer: ReturnType<typeof setTimeout> | null = null
let searchBlurTimer: ReturnType<typeof setTimeout> | null = null

function isActive(href: string): boolean {
    if (href === '#') {
        return false
    }

    if (href === '/') {
        return page.url === '/' || page.url === ''
    }

    const normalized = page.url.split('?')[0]
    return normalized === href || normalized.startsWith(href.replace(/\/$/, '') + '/')
}

function openDropdown(index: number) {
    if (dropdownTimer.value) {
        clearTimeout(dropdownTimer.value)
        dropdownTimer.value = null
    }
    activeDropdown.value = index
}

function closeDropdown() {
    dropdownTimer.value = setTimeout(() => {
        activeDropdown.value = null
    }, 150)
}

function performSearch() {
    const query = searchQuery.value.trim()

    if (query.length < 3) {
        searchResults.value = []
        searchOpen.value = false
        return
    }

    searchLoading.value = true
    searchOpen.value = true

    fetch(`/pretraga?q=${encodeURIComponent(query)}`, {
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
    })
        .then(res => res.json())
        .then((data) => {
            searchResults.value = data
            searchOpen.value = true
        })
        .catch(() => {
            searchResults.value = []
        })
        .finally(() => {
            searchLoading.value = false
        })
}

function onSearchInput() {
    if (searchDebounceTimer) {
        clearTimeout(searchDebounceTimer)
    }

    const query = searchQuery.value.trim()
    if (query.length < 3) {
        searchResults.value = []
        searchOpen.value = false
        return
    }

    searchDebounceTimer = setTimeout(() => {
        performSearch()
    }, 350)
}

function onSearchFocus() {
    if (searchBlurTimer) {
        clearTimeout(searchBlurTimer)
        searchBlurTimer = null
    }
    if (searchQuery.value.trim().length >= 3 && searchResults.value.length > 0) {
        searchOpen.value = true
    }
}

function onSearchBlur() {
    searchBlurTimer = setTimeout(() => {
        searchOpen.value = false
    }, 200)
}

function clearSearch() {
    searchQuery.value = ''
    searchResults.value = []
    searchOpen.value = false
}

watch(mobileMenuOpen, (open) => {
    if (open) {
        document.body.style.overflow = 'hidden'
    } else {
        document.body.style.overflow = ''
    }
})

onUnmounted(() => {
    if (dropdownTimer.value) clearTimeout(dropdownTimer.value)
    if (searchDebounceTimer) clearTimeout(searchDebounceTimer)
    if (searchBlurTimer) clearTimeout(searchBlurTimer)
    document.body.style.overflow = ''
})
</script>

<template>
    <header class="sticky top-0 z-50 border-b border-gray-100 bg-white/80 backdrop-blur-md">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5 lg:px-8">
            <Link :href="home.url()" class="font-sans text-xl font-semibold tracking-[0.25em] text-gray-900 uppercase">
                dndparfems
            </Link>

            <div class="hidden items-center gap-8 md:flex">
                <div
                    v-for="(link, index) in navLinks"
                    :key="link.label"
                    class="relative"
                    @mouseenter="link.dropdown ? openDropdown(index) : undefined"
                    @mouseleave="link.dropdown ? closeDropdown() : undefined"
                >
                    <Link
                        :href="link.href"
                        :class="[
                            'group relative block font-sans text-xs font-semibold tracking-[0.15em] uppercase transition-colors',
                            isActive(link.href)
                                ? 'text-gray-900'
                                : 'text-gray-500 hover:text-gray-900',
                        ]"
                    >
                        {{ link.label }}
                        <span
                            :class="[
                                'absolute -bottom-1 h-px bg-gray-900 transition-all duration-300',
                                isActive(link.href)
                                    ? 'left-0 w-full'
                                    : 'left-1/2 w-0 group-hover:left-0 group-hover:w-full',
                            ]"
                        />
                    </Link>
                </div>
            </div>

            <div class="hidden items-center gap-6 md:flex">
                <!-- Search -->
                <div class="relative">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <Icon name="search" :size="16" />
                        </div>
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Pretraži parfeme..."
                            class="h-9 w-48 rounded-full border border-gray-200 bg-white pl-9 pr-8 text-sm text-gray-900 outline-none transition-all placeholder:text-gray-400 focus:w-64 focus:border-gray-900"
                            @input="onSearchInput"
                            @focus="onSearchFocus"
                            @blur="onSearchBlur"
                        />
                        <button
                            v-if="searchQuery"
                            type="button"
                            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 transition-colors hover:text-gray-600"
                            @mousedown.prevent="clearSearch"
                        >
                            <Icon name="close" :size="14" />
                        </button>
                    </div>

                    <!-- Search Dropdown -->
                    <Transition
                        enter-active-class="transition-all duration-200 ease-out"
                        enter-from-class="opacity-0 -translate-y-1"
                        enter-to-class="opacity-100 translate-y-0"
                        leave-active-class="transition-all duration-150 ease-in"
                        leave-from-class="opacity-100 translate-y-0"
                        leave-to-class="opacity-0 -translate-y-1"
                    >
                        <div
                            v-if="searchOpen"
                            class="absolute right-0 top-full mt-2 w-80 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-xl"
                            @mousedown.prevent
                        >
                            <div v-if="searchLoading" class="py-6 text-center">
                                <div class="mx-auto h-5 w-5 animate-spin rounded-full border-2 border-gray-200 border-t-gray-900" />
                            </div>

                            <div v-else-if="searchResults.length === 0" class="py-5 text-center">
                                <p class="text-sm text-gray-400">Nema rezultata.</p>
                            </div>

                            <div v-else class="divide-y divide-gray-100">
                                <Link
                                    v-for="product in searchResults"
                                    :key="product.id"
                                    :href="productRoutes.show.url(product.slug)"
                                    class="flex items-center gap-3 px-4 py-3 transition-colors hover:bg-gray-50"
                                    @click="clearSearch"
                                >
                                    <div class="h-12 w-12 shrink-0 overflow-hidden rounded-md bg-gray-100">
                                        <img
                                            v-if="product.image"
                                            :src="product.image"
                                            :alt="product.name"
                                            class="h-full w-full object-cover"
                                            loading="lazy"
                                        />
                                        <div v-else class="flex h-full w-full items-center justify-center text-xs text-gray-400">
                                            Nema
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-medium text-gray-900">
                                            {{ product.name }}
                                        </p>
                                        <p class="truncate text-xs text-gray-500">
                                            {{ product.brand }}
                                        </p>
                                        <p class="mt-0.5 text-xs font-semibold text-gray-900">
                                            {{ product.price }}
                                        </p>
                                    </div>
                                </Link>
                            </div>

                            <div class="border-t border-gray-100 bg-gray-50 px-4 py-2">
                                <Link
                                    :href="productRoutes.index.url({ query: { search: searchQuery } })"
                                    class="block text-center text-xs font-medium text-gray-500 transition-colors hover:text-gray-900"
                                    @click="clearSearch"
                                >
                                    Pogledaj sve rezultate →
                                </Link>
                            </div>
                        </div>
                    </Transition>
                </div>

                <Link
                    v-if="!authUser"
                    :href="login.url()"
                    class="flex items-center text-gray-500 transition-colors hover:text-gray-900"
                    aria-label="Nalog"
                >
                    <Icon name="user" :size="20" />
                </Link>

                <div v-else class="relative flex items-center group">
                    <button
                        type="button"
                        class="relative flex items-center text-gray-900 transition-colors hover:text-gray-700"
                        aria-label="Nalog"
                    >
                        <Icon name="user" :size="20" />
                        <span class="absolute -bottom-0.5 -right-0.5 h-2 w-2 rounded-full bg-green-600 ring-2 ring-white" />
                    </button>
                    <div class="absolute right-0 top-full mt-3 w-52 origin-top-right border border-gray-100 bg-white shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ authUser.name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ authUser.email }}</p>
                        </div>
                        <Link :href="logout.url()" method="post" as="button" class="flex w-full items-center gap-2 px-4 py-3 text-left text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <Icon name="logout" :size="16" />
                            Odjavi se
                        </Link>
                    </div>
                </div>

                <Link
                    :href="cart.index.url()"
                    class="text-gray-500 transition-colors hover:text-gray-900"
                    aria-label="Korpa"
                >
                    <Icon name="cart" :size="20" />
                </Link>
            </div>

            <button
                type="button"
                class="md:hidden text-gray-500 transition-colors hover:text-gray-900"
                aria-label="Meni"
                @click="mobileMenuOpen = true"
            >
                <Icon name="menu" :size="24" />
            </button>
        </nav>

        <!-- Full-width dropdown -->
        <Transition
            enter-active-class="transition-all duration-300 ease-out"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition-all duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-2"
        >
            <div
                v-if="activeDropdown !== null && navLinks[activeDropdown]?.dropdown"
                class="absolute left-0 right-0 z-40 border-b border-gray-100 bg-white/95 shadow-sm backdrop-blur"
                @mouseenter="openDropdown(activeDropdown)"
                @mouseleave="closeDropdown()"
            >
                <div class="mx-auto max-w-7xl px-6 py-14 lg:px-8">
                    <div class="grid grid-cols-1 gap-12 md:grid-cols-3">
                        <div
                            v-for="column in navLinks[activeDropdown].dropdown"
                            :key="column.title"
                        >
                            <Link
                                :href="column.href"
                                class="group mb-8 inline-block"
                            >
                                <h3 class="font-serif text-xl font-medium text-gray-900 transition-colors group-hover:text-gray-600">
                                    {{ column.title }}
                                </h3>
                                <span class="mt-2 block text-[11px] font-medium tracking-[0.2em] text-gray-400 uppercase transition-colors group-hover:text-gray-900">
                                    Pogledaj sve
                                </span>
                            </Link>
                            <ul class="space-y-3">
                                <li
                                    v-for="brand in column.brands"
                                    :key="brand.name"
                                >
                                    <Link
                                        :href="brand.href"
                                        class="text-sm text-gray-500 transition-colors hover:text-gray-900"
                                    >
                                        {{ brand.name }}
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </header>

    <MobileMenu v-model="mobileMenuOpen" />
</template>
