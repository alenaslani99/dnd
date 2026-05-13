<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { navLinks } from '@/config/nav'

</script>

<template>
    <footer class="border-t border-gray-100 bg-gray-50">
        <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
            <div class="grid grid-cols-1 gap-10 md:grid-cols-3">
                <div>
                    <Link href="/" class="font-sans text-lg font-semibold tracking-[0.25em] text-gray-900 uppercase">
                        dndparfems
                    </Link>
                    <p class="mt-4 text-sm leading-relaxed text-gray-500">
                        Premium prodavnica parfema i fragransa. Pažljivo odabrana kolekcija luksuznih mirisa za svaki trenutak.
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold tracking-wide text-gray-900 uppercase">
                        Brzi linkovi
                    </h3>
                    <ul class="mt-4 flex flex-col gap-2">
                        <li v-for="link in navLinks" :key="link.label">
                            <Link
                                :href="link.href"
                                class="text-sm text-gray-500 transition-colors hover:text-gray-900"
                            >
                                {{ link.label }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-sm font-semibold tracking-wide text-gray-900 uppercase">
                        Kontakt
                    </h3>
                    <ul class="mt-4 flex flex-col gap-2 text-sm text-gray-500">
                        <li>info@dndparfems.rs</li>
                        <li>+381 11 123 4567</li>
                        <li>Beograd, Srbija</li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 border-t border-gray-200 pt-8">
                <p class="text-center text-xs text-gray-400">
                    &copy; {{ new Date().getFullYear() }} dndparfems. Sva prava zadržana.
                </p>
            </div>
        </div>
    </footer>
</template>
