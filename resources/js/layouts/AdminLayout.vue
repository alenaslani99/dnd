<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { dashboard as adminDashboard, products as adminProducts, orders as adminOrders } from '@/routes/admin'
import { home, logout } from '@/routes'
import Icon from '@/components/Icon.vue'

const page = usePage()
const authUser = computed(() => page.props.auth?.user as { name: string; email: string } | null)

const navLinks = [
    { label: 'Dashboard', href: adminDashboard.url(), icon: 'dashboard' as const },
    { label: 'Proizvodi', href: adminProducts.index.url(), icon: 'package' as const },
    { label: 'Porudžbine', href: adminOrders.index.url(), icon: 'shopping-bag' as const },
    { label: 'Korisnici', href: '#', icon: 'users' as const },
    { label: 'Brendovi', href: '#', icon: 'tag' as const },
    { label: 'Promocije', href: '#', icon: 'percent' as const },
]

function isActive(href: string): boolean {
    if (href === '#') {
        return false
    }

    const normalized = page.url.split('?')[0]
    return normalized === href || normalized.startsWith(href.replace(/\/$/, '') + '/')
}
</script>

<template>
    <div class="flex min-h-screen bg-gray-50 font-sans antialiased">
        <!-- Sidebar -->
        <aside class="flex w-64 flex-col border-r border-gray-200 bg-white">
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

        <!-- Main content -->
        <main class="flex-1 p-8">
            <slot />
        </main>
    </div>
</template>
