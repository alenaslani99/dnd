<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import { navLinks } from '@/config/nav'

const mobileMenuOpen = ref(false)
const page = usePage()

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
            <Link href="/" class="font-sans text-xl font-semibold tracking-[0.25em] text-gray-900 uppercase">
                dndparfems
            </Link>

            <div class="hidden items-center gap-8 md:flex">
                <Link
                    v-for="link in navLinks"
                    :key="link.label"
                    :href="link.href"
                    :class="[
                        'group relative font-sans text-xs font-semibold tracking-[0.15em] uppercase transition-colors',
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

            <div class="hidden items-center gap-6 md:flex">
                <button type="button" class="text-gray-500 transition-colors hover:text-gray-900" aria-label="Pretraga">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </button>
                <button type="button" class="text-gray-500 transition-colors hover:text-gray-900" aria-label="Nalog">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </button>
                <button type="button" class="text-gray-500 transition-colors hover:text-gray-900" aria-label="Korpa">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                </button>
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
            class="fixed inset-0 z-[60] flex flex-col bg-[#111] px-6 py-6 md:hidden"
        >
            <div class="flex items-center justify-between">
                <Link
                    href="/"
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

            <div class="flex items-center justify-between text-sm text-white/60">
                <a href="mailto:info@dndparfems.rs" class="transition-colors hover:text-white">( Email )</a>
                <a href="https://instagram.com" target="_blank" class="transition-colors hover:text-white">( Instagram )</a>
            </div>
        </div>
    </Transition>
</template>
