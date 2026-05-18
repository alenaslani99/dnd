<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { computed, ref, watch, onUnmounted } from 'vue'
import { dashboard as adminDashboard } from '@/routes/admin'
import adminBrands from '@/routes/admin/brands'
import adminMessages from '@/routes/admin/messages'
import adminProducts from '@/routes/admin/products'
import adminOrders from '@/routes/admin/orders'
import { home, logout } from '@/routes'
import Icon from '@/components/Icon.vue'

const page = usePage()
const authUser = computed(() => page.props.auth?.user as { name: string; email: string } | null)
const mobileMenuOpen = ref(false)

const navLinks = [
    { label: 'Dashboard', href: adminDashboard.url(), icon: 'dashboard' as const },
    { label: 'Proizvodi', href: adminProducts.index.url(), icon: 'package' as const },
    { label: 'Porudžbine', href: adminOrders.index.url(), icon: 'shopping-bag' as const },
    { label: 'Poruke', href: adminMessages.index.url(), icon: 'mail' as const },
    { label: 'Brendovi', href: adminBrands.index.url(), icon: 'tag' as const },
    { label: 'Promocije', href: '#', icon: 'percent' as const },
]

function isActive(href: string): boolean {
    if (href === '#') {
        return false
    }

    const normalized = page.url.split('?')[0]
    return normalized === href || normalized.startsWith(href.replace(/\/$/, '') + '/')
}

function closeMobileMenu() {
    mobileMenuOpen.value = false
}

watch(mobileMenuOpen, (open) => {
    if (open) {
        document.body.style.overflow = 'hidden'
    } else {
        document.body.style.overflow = ''
    }
})

onUnmounted(() => {
    document.body.style.overflow = ''
})
</script>

<template>
    <div class="flex min-h-screen bg-gray-50 font-sans antialiased">
        <!-- Desktop Sidebar -->
        <aside class="hidden lg:flex w-64 flex-col border-r border-gray-200 bg-white">
            <div class="flex h-16 items-center border-b border-gray-200 px-6">
                <Link
                    :href="adminDashboard.url()"
                    class="font-sans text-lg font-semibold tracking-[0.2em] text-gray-900 uppercase"
                >
                    dndparfems
                </Link>
            </div>

            <nav class="flex-1 space-y-1 p-4">
                <Link
                    v-for="link in navLinks"
                    :key="link.label"
                    :href="link.href"
                    :class="[
                        'flex items-center gap-3 rounded-md px-4 py-2.5 text-sm font-medium transition-colors',
                        isActive(link.href)
                            ? 'bg-gray-100 text-gray-900'
                            : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                    ]"
                >
                    <Icon :name="link.icon" :size="18" />
                    {{ link.label }}
                </Link>
            </nav>

            <div class="border-t border-gray-200 p-4">
                <div class="space-y-1">
                    <Link
                        :href="home.url()"
                        class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50 hover:text-gray-900"
                    >
                        <Icon name="arrow-left" :size="18" />
                        Nazad u radnju
                    </Link>
                    <Link
                        :href="logout.url()"
                        method="post"
                        as="button"
                        class="flex w-full items-center gap-3 rounded-md px-4 py-2 text-left text-sm font-medium text-red-600 transition-colors hover:bg-red-50"
                    >
                        <Icon name="logout" :size="18" />
                        Odjavi se
                    </Link>
                </div>

                <div v-if="authUser" class="mt-4 border-t border-gray-100 pt-4">
                    <p class="text-sm font-medium text-gray-900">{{ authUser.name }}</p>
                    <p class="text-xs text-gray-500">{{ authUser.email }}</p>
                </div>
            </div>
        </aside>

        <!-- Mobile Header -->
        <div class="fixed top-0 left-0 right-0 z-40 flex h-16 items-center justify-between border-b border-gray-200 bg-white px-4 lg:hidden">
            <Link
                :href="adminDashboard.url()"
                class="font-sans text-lg font-semibold tracking-[0.2em] text-gray-900 uppercase"
            >
                dndparfems
            </Link>
            <button
                type="button"
                class="flex h-10 w-10 items-center justify-center rounded-md text-gray-600 transition-colors hover:bg-gray-100"
                aria-label="Meni"
                @click="mobileMenuOpen = true"
            >
                <Icon name="menu" :size="24" />
            </button>
        </div>

        <!-- Mobile Drawer -->
        <Transition
            enter-active-class="transition-opacity duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-200"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="mobileMenuOpen"
                class="fixed inset-0 z-50 bg-black/50 lg:hidden"
                @click="closeMobileMenu"
            />
        </Transition>

        <Transition
            enter-active-class="transition-transform duration-300 ease-out"
            enter-from-class="-translate-x-full"
            enter-to-class="translate-x-0"
            leave-active-class="transition-transform duration-200 ease-in"
            leave-from-class="translate-x-0"
            leave-to-class="-translate-x-full"
        >
            <div
                v-if="mobileMenuOpen"
                class="fixed inset-y-0 left-0 z-50 flex w-72 flex-col bg-white shadow-xl lg:hidden"
            >
                <div class="flex h-16 items-center justify-between border-b border-gray-200 px-6">
                    <span class="font-sans text-lg font-semibold tracking-[0.2em] text-gray-900 uppercase">
                        dndparfems
                    </span>
                    <button
                        type="button"
                        class="flex h-10 w-10 items-center justify-center rounded-md text-gray-500 transition-colors hover:bg-gray-100"
                        aria-label="Zatvori"
                        @click="closeMobileMenu"
                    >
                        <Icon name="close" :size="20" />
                    </button>
                </div>

                <nav class="flex-1 space-y-1 p-4">
                    <Link
                        v-for="link in navLinks"
                        :key="link.label"
                        :href="link.href"
                        :class="[
                            'flex items-center gap-3 rounded-md px-4 py-3 text-sm font-medium transition-colors',
                            isActive(link.href)
                                ? 'bg-gray-100 text-gray-900'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                        ]"
                        @click="closeMobileMenu"
                    >
                        <Icon :name="link.icon" :size="18" />
                        {{ link.label }}
                    </Link>
                </nav>

                <div class="border-t border-gray-200 p-4">
                    <div class="space-y-1">
                        <Link
                            :href="home.url()"
                            class="flex items-center gap-3 rounded-md px-4 py-2 text-sm font-medium text-gray-600 transition-colors hover:bg-gray-50 hover:text-gray-900"
                            @click="closeMobileMenu"
                        >
                            <Icon name="arrow-left" :size="18" />
                            Nazad u radnju
                        </Link>
                        <Link
                            :href="logout.url()"
                            method="post"
                            as="button"
                            class="flex w-full items-center gap-3 rounded-md px-4 py-2 text-left text-sm font-medium text-red-600 transition-colors hover:bg-red-50"
                            @click="closeMobileMenu"
                        >
                            <Icon name="logout" :size="18" />
                            Odjavi se
                        </Link>
                    </div>

                    <div v-if="authUser" class="mt-4 border-t border-gray-100 pt-4">
                        <p class="text-sm font-medium text-gray-900">{{ authUser.name }}</p>
                        <p class="text-xs text-gray-500">{{ authUser.email }}</p>
                    </div>
                </div>
            </div>
        </Transition>

        <!-- Main content -->
        <main class="flex-1 min-w-0 overflow-x-hidden p-4 pt-20 lg:p-8">
            <slot />
        </main>
    </div>
</template>
