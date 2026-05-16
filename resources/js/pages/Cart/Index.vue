<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import CartItem from '@/components/CartItem.vue'
import GhostButton from '@/components/GhostButton.vue'
import PageContainer from '@/components/PageContainer.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
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

    <PageContainer>
        <h1 class="mb-12 text-center font-serif text-4xl font-medium tracking-wide text-gray-900">
            Korpa
        </h1>

        <div v-if="cart.items.length > 0" class="grid grid-cols-1 gap-12 lg:grid-cols-3">
            <!-- Items -->
            <div class="lg:col-span-2 space-y-8">
                <CartItem
                    v-for="item in cart.items"
                    :key="item.id"
                    :item="item"
                    @update-quantity="updateQuantity"
                    @remove="removeItem"
                />
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

                <PrimaryButton
                    as="link"
                    variant="full"
                    :href="checkoutRoutes.create.url()"
                    class="mt-8"
                >
                    Checkout
                </PrimaryButton>

                <GhostButton
                    as="link"
                    :href="productRoutes.index.url()"
                    class="mt-4"
                >
                    Nastavi kupovinu
                </GhostButton>
            </div>
        </div>

        <!-- Empty Cart -->
        <div v-else class="py-20 text-center">
            <p class="text-lg text-gray-400">Tvoja korpa je prazna.</p>
            <PrimaryButton
                as="link"
                variant="inline"
                :href="productRoutes.index.url()"
                class="mt-4"
            >
                Pogledaj kolekciju
            </PrimaryButton>
        </div>
    </PageContainer>
</template>
