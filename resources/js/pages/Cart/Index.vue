<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import BaseDialog from '@/components/BaseDialog.vue'
import CartItem from '@/components/CartItem.vue'
import GhostButton from '@/components/GhostButton.vue'
import PageContainer from '@/components/PageContainer.vue'
import PrimaryButton from '@/components/PrimaryButton.vue'
import OrderSummary from '@/components/OrderSummary.vue'
import type { Cart } from '@/types'
import productRoutes from '@/routes/products'
import cartRoutes from '@/routes/cart'
import checkoutRoutes from '@/routes/checkout'

defineOptions({ layout: AppLayout })

const props = defineProps<{
    cart: Cart
}>()

const updateForm = useForm({ quantity: 1 })
const itemToRemove = ref<number | null>(null)

function updateQuantity(itemId: number, qty: number) {
    if (qty < 1) return
    updateForm.quantity = qty
    updateForm.patch(cartRoutes.update.url(itemId), { preserveScroll: true })
}

function removeItem(itemId: number) {
    itemToRemove.value = itemId
}

function confirmRemove() {
    if (itemToRemove.value !== null) {
        updateForm.delete(cartRoutes.destroy.url(itemToRemove.value), { preserveScroll: true })
    }
    itemToRemove.value = null
}

function cancelRemove() {
    itemToRemove.value = null
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
            <div class="space-y-8">
                <OrderSummary
                    :items="cart.items.map(item => ({
                        product_name: item.product.name,
                        brand_name: item.product.brand,
                        size_label: item.variant.size_label,
                        quantity: item.quantity,
                        unit_price: item.unit_price ?? 0,
                        total_price: item.total_price ?? 0,
                    }))"
                    :subtotal="cart.total ?? 0"
                    :shipping="cart.shipping_cost"
                    :total="(cart.total ?? 0) + cart.shipping_cost"
                />

                <PrimaryButton
                    as="link"
                    variant="full"
                    :href="checkoutRoutes.create.url()"
                >
                    Checkout
                </PrimaryButton>

                <GhostButton
                    as="link"
                    :href="productRoutes.index.url()"
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

    <BaseDialog
        :open="itemToRemove !== null"
        title="Ukloni iz korpe"
        message="Da li sigurno želiš da ukloniš ovaj proizvod iz korpe?"
        confirm-label="Ukloni"
        cancel-label="Otkaži"
        icon="alert"
        @confirm="confirmRemove"
        @cancel="cancelRemove"
        @close="cancelRemove"
    />
</template>
