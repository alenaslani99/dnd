<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'
import { navLinks } from '@/config/nav'
import { home, login, logout } from '@/routes'
import cart from '@/routes/cart'
import Icon from '@/components/Icon.vue'

const mobileMenuOpen = defineModel<boolean>({ required: true })

const page = usePage()
const authUser = computed(() => page.props.auth?.user as { name: string; email: string } | null)

function isActive(href: string): boolean {
    if (href === '#') return false
    if (href === '/') return page.url === '/' || page.url === ''
    const normalized = page.url.split('?')[0]
    return normalized === href || normalized.startsWith(href.replace(/\/$/, '') + '/')
}

function close() {
    mobileMenuOpen.value = false
}
</script>

<template>
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
                    @click="close"
                >
                    dndparfems
                </Link>
                <button
                    type="button"
                    class="text-white transition-colors hover:text-gray-300"
                    aria-label="Zatvori"
                    @click="close"
                >
                    <Icon name="close" :size="24" />
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
                    @click="close"
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
                    @click="close"
                >
                    <Icon name="user" :size="24" />
                    <span class="text-[10px] font-medium tracking-[0.2em] uppercase">Nalog</span>
                </Link>
                <Link
                    v-else
                    :href="logout.url()"
                    method="post"
                    as="button"
                    class="flex flex-col items-center gap-2 text-white/60 transition-colors hover:text-white"
                    aria-label="Odjavi se"
                    @click="close"
                >
                    <Icon name="logout" :size="24" />
                    <span class="text-[10px] font-medium tracking-[0.2em] uppercase">Odjavi se</span>
                </Link>
                <Link
                    :href="cart.index.url()"
                    class="flex flex-col items-center gap-2 text-white/60 transition-colors hover:text-white"
                    aria-label="Korpa"
                    @click="close"
                >
                    <Icon name="cart" :size="24" />
                    <span class="text-[10px] font-medium tracking-[0.2em] uppercase">Korpa</span>
                </Link>
            </div>
        </div>
    </Transition>
</template>
