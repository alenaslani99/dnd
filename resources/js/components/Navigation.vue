<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import { navLinks } from '@/config/nav'
import { home, login, logout } from '@/routes'
import cart from '@/routes/cart'
import Icon from '@/components/Icon.vue'
import MobileMenu from '@/components/MobileMenu.vue'

const mobileMenuOpen = ref(false)
const activeDropdown = ref<number | null>(null)
const dropdownTimer = ref<ReturnType<typeof setTimeout> | null>(null)

const page = usePage()
const authUser = computed(() => page.props.auth?.user as { name: string; email: string } | null)

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

watch(mobileMenuOpen, (open) => {
    if (open) {
        document.body.style.overflow = 'hidden'
    } else {
        document.body.style.overflow = ''
    }
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
                <button type="button" class="text-gray-500 transition-colors hover:text-gray-900" aria-label="Pretraga">
                    <Icon name="search" :size="20" />
                </button>

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
                class="absolute left-0 right-0 z-40 border-b border-gray-100 bg-white shadow-sm"
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
