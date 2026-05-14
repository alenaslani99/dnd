<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { formatPrice } from '@/lib/utils'
import type { Cart } from '@/types'
import productRoutes from '@/routes/products'
import cartRoutes from '@/routes/cart'
import checkoutRoutes from '@/routes/checkout'

defineOptions({ layout: AppLayout })

const props = defineProps<{
    cart: Cart
}>()

const updateForm = useForm({ quantity: 1 })

function updateQuantity(itemId: number, qty: number) {
    if (qty < 1) return
    updateForm.quantity = qty
    updateForm.patch(cartRoutes.update.url(itemId), { preserveScroll: true })
}

function removeItem(itemId: number) {
    if (confirm('Da li sigurno želiš da ukloniš ovaj proizvod iz korpe?')) {
        updateForm.delete(cartRoutes.destroy.url(itemId), { preserveScroll: true })
    }
}
</script>

<template>
    <Head title="Korpa" />

    <section class="mx-auto max-w-7xl px-6 py-24 lg:px-8">
        <h1 class="mb-12 text-center font-serif text-4xl font-medium tracking-wide text-gray-900">
            Korpa
        </h1>

        <div v-if="cart.items.length > 0" class="grid grid-cols-1 gap-12 lg:grid-cols-3">
            <!-- Items -->
            <div class="lg:col-span-2 space-y-8">
                <div
                    v-for="item in cart.items"
                    :key="item.id"
                    class="flex gap-6 border-b border-gray-100 pb-8"
                >
                    <Link :href="productRoutes.show.url(item.product.slug)" class="shrink-0">
                        <div class="h-32 w-24 overflow-hidden bg-gray-100">
                            <img
                                :src="item.product.image"
                                :alt="item.product.name"
                                class="h-full w-full object-cover"
                            />
                        </div>
                    </Link>

                    <div class="flex flex-1 flex-col justify-between min-w-0">
                        <div>
                            <p class="text-[11px] font-medium tracking-[0.2em] text-gray-500 uppercase">
                                {{ item.product.brand }}
                            </p>
                            <Link
                                :href="productRoutes.show.url(item.product.slug)"
                                class="mt-1 block text-base font-medium text-gray-900 break-words"
                            >
                                {{ item.product.name }}
                            </Link>
                            <p class="mt-1 text-sm text-gray-400">
                                {{ item.variant.size_label }}
                            </p>
                        </div>

                        <div class="mt-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="inline-flex w-fit items-center border border-gray-200">
                                <button
                                    type="button"
                                    @click="updateQuantity(item.id, item.quantity - 1)"
                                    class="px-3 py-2 text-sm text-gray-600 transition-colors hover:bg-gray-50"
                                >
                                    −
                                </button>
                                <span class="w-8 px-1 py-2 text-center text-sm font-medium text-gray-900">
                                    {{ item.quantity }}
                                </span>
                                <button
                                    type="button"
                                    @click="updateQuantity(item.id, item.quantity + 1)"
                                    class="px-3 py-2 text-sm text-gray-600 transition-colors hover:bg-gray-50"
                                >
                                    +
                                </button>
                            </div>

                            <div class="flex items-center justify-between gap-4 sm:justify-end">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ formatPrice(item.total_price) }}
                                </p>
                                <button
                                    type="button"
                                    @click="removeItem(item.id)"
                                    class="text-xs text-gray-400 underline-offset-4 transition-colors hover:text-red-600 hover:underline"
                                >
                                    Ukloni
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary -->
            <div class="border border-gray-100 bg-gray-50 p-6 sm:p-8">
                <h2 class="text-xs font-medium tracking-[0.15em] text-gray-500 uppercase">
                    Pregled
                </h2>

                <div class="mt-6 space-y-3">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Ukupno</span>
                        <span>{{ formatPrice(cart.total) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>Dostava</span>
                        <span>{{ formatPrice(cart.shipping_cost) }}</span>
                    </div>
                </div>

                <div class="mt-6 border-t border-gray-200 pt-6">
                    <div class="flex justify-between text-base font-medium text-gray-900">
                        <span>Ukupno</span>
                        <span>{{ formatPrice((cart.total ?? 0) + cart.shipping_cost) }}</span>
                    </div>
                </div>

                <Link
                    :href="checkoutRoutes.create.url()"
                    class="mt-8 block w-full border border-gray-900 bg-gray-900 px-4 py-4 text-center text-sm font-medium tracking-[0.2em] text-white uppercase transition-all hover:bg-white hover:text-gray-900"
                >
                    Checkout
                </Link>

                <Link
                    :href="productRoutes.index.url()"
                    class="mt-4 block w-full px-4 py-4 text-center text-sm font-medium tracking-[0.1em] text-gray-500 uppercase transition-colors hover:text-gray-900"
                >
                    Nastavi kupovinu
                </Link>
            </div>
        </div>

        <!-- Empty Cart -->
        <div v-else class="py-20 text-center">
            <p class="text-lg text-gray-400">Tvoja korpa je prazna.</p>
            <Link
                :href="productRoutes.index.url()"
                class="mt-4 inline-block border border-gray-900 bg-gray-900 px-8 py-3 text-sm font-medium tracking-[0.2em] text-white uppercase transition-all hover:bg-white hover:text-gray-900"
            >
                Pogledaj kolekciju
            </Link>
        </div>
    </section>
</template>
