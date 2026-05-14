<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { computed, ref, watch } from 'vue'
import { navLinks } from '@/config/nav'
import { home, login, logout } from '@/routes'
import cart from '@/routes/cart'

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
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </button>

                <Link
                    v-if="!authUser"
                    :href="login.url()"
                    class="flex items-center text-gray-500 transition-colors hover:text-gray-900"
                    aria-label="Nalog"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </Link>

                <div v-else class="relative flex items-center group">
                    <button
                        type="button"
                        class="relative flex items-center text-gray-900 transition-colors hover:text-gray-700"
                        aria-label="Nalog"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <span class="absolute -bottom-0.5 -right-0.5 h-2 w-2 rounded-full bg-green-600 ring-2 ring-white" />
                    </button>
                    <div class="absolute right-0 top-full mt-3 w-52 origin-top-right border border-gray-100 bg-white shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ authUser.name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ authUser.email }}</p>
                        </div>
                        <Link :href="logout.url()" method="post" as="button" class="flex w-full items-center gap-2 px-4 py-3 text-left text-sm text-red-600 hover:bg-red-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                            Odjavi se
                        </Link>
                    </div>
                </div>

                <Link
                    :href="cart.index.url()"
                    class="text-gray-500 transition-colors hover:text-gray-900"
                    aria-label="Korpa"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                </Link>
            </div>

            <button
                type="button"
                class="md:hidden text-gray-500 transition-colors hover:text-gray-900"
                aria-label="Meni"
                @click="mobileMenuOpen = true"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 5h16"/><path d="M4 12h16"/><path d="M4 19h16"/></svg>
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

    <!-- Mobile Menu Overlay -->
    <Transition
        enter-active-class="transition-transform duration-500 ease-out"
        enter-from-class="-translate-y-full"
        enter-to-class="translate-y-0"
        leave-active-class="transition-transform duration-500 ease-in"
        leave-from-class="translate-y-0"
        leave-to-class="-translate-y-full"
    >
        <div
            v-if="mobileMenuOpen"
            class="fixed inset-0 z-[60] flex flex-col bg-gray-900 px-6 py-6 md:hidden"
        >
            <div class="flex items-center justify-between">
                <Link
                    :href="home.url()"
                    class="font-sans text-xl font-semibold tracking-[0.25em] text-white uppercase"
                    @click="mobileMenuOpen = false"
                >
                    dndparfems
                </Link>
                <button
                    type="button"
                    class="text-white transition-colors hover:text-gray-300"
                    aria-label="Zatvori"
                    @click="mobileMenuOpen = false"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>

            <div class="flex flex-1 flex-col items-center justify-center gap-6">
                <Link
                    v-for="link in navLinks"
                    :key="link.label"
                    :href="link.href"
                    :class="[
                        'group relative font-sans text-4xl font-bold uppercase tracking-tight text-white transition-opacity',
                        isActive(link.href) ? 'opacity-100' : 'opacity-60 hover:opacity-100',
                    ]"
                    @click="mobileMenuOpen = false"
                >
                    {{ link.label }}
                    <span
                        :class="[
                            'absolute -bottom-2 left-0 h-px bg-white transition-all duration-300',
                            isActive(link.href) ? 'w-full' : 'w-0 group-hover:w-full',
                        ]"
                    />
                </Link>
            </div>

            <div class="flex items-center justify-center gap-8">
                <Link
                    v-if="!authUser"
                    :href="login.url()"
                    class="flex flex-col items-center gap-2 text-white/60 transition-colors hover:text-white"
                    aria-label="Nalog"
                    @click="mobileMenuOpen = false"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span class="text-[10px] font-medium tracking-[0.2em] uppercase">Nalog</span>
                </Link>
                <Link
                    v-else
                    :href="logout.url()"
                    method="post"
                    as="button"
                    class="flex flex-col items-center gap-2 text-white/60 transition-colors hover:text-white"
                    aria-label="Odjavi se"
                    @click="mobileMenuOpen = false"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
                    <span class="text-[10px] font-medium tracking-[0.2em] uppercase">Odjavi se</span>
                </Link>
                <Link
                    :href="cart.index.url()"
                    class="flex flex-col items-center gap-2 text-white/60 transition-colors hover:text-white"
                    aria-label="Korpa"
                    @click="mobileMenuOpen = false"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    <span class="text-[10px] font-medium tracking-[0.2em] uppercase">Korpa</span>
                </Link>
            </div>
        </div>
    </Transition>
</template>
