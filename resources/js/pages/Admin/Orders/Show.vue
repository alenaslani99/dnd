<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import Icon from '@/components/Icon.vue'
import { orders as adminOrdersRoute } from '@/routes/admin'
import { statusLabel, formatPrice } from '@/lib/utils'

defineOptions({ layout: AdminLayout })

const props = defineProps<{
    order: {
        id: number
        order_number: string
        status: string
        total_amount: number
        shipping_cost: number
        payment_method: string
        created_at: string
        customer: {
            name: string
            email: string
            phone: string
        }
        shipping: {
            address: string
            house_number: string
            zip: string
            city: string
        }
        items: {
            product_name: string
            brand_name: string
            size_label: string
            quantity: number
            unit_price: number
            total_price: number
        }[]
        next_status: string | null
    }
}>()

const form = useForm({})

function advanceStatus() {
    form.patch(adminOrdersRoute.updateStatus.url(props.order.order_number))
}

function statusColor(status: string): string {
    const map: Record<string, string> = {
        pending: 'bg-yellow-50 text-yellow-700',
        processing: 'bg-blue-50 text-blue-700',
        shipped: 'bg-purple-50 text-purple-700',
        delivered: 'bg-green-50 text-green-700',
        cancelled: 'bg-gray-100 text-gray-600',
        refunded: 'bg-red-50 text-red-700',
    }
    return map[status] ?? 'bg-gray-100 text-gray-600'
}

function nextStatusLabel(status: string | null): string {
    if (!status) return ''
    return statusLabel(status)
}
</script>

<template>
    <Head :title="`Porudžbina #${order.order_number}`" />

    <div class="space-y-8">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-500">
            <Link
                :href="adminOrdersRoute.index.url()"
                class="transition-colors hover:text-gray-900"
            >
                Porudžbine
            </Link>
            <span>/</span>
            <span class="font-medium text-gray-900">#{{ order.order_number }}</span>
        </div>

        <!-- Header -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <h1 class="font-serif text-3xl font-medium tracking-wide text-gray-900">
                    Porudžbina #{{ order.order_number }}
                </h1>
                <span
                    :class="[
                        'inline-flex rounded-full px-3 py-1 text-xs font-medium',
                        statusColor(order.status),
                    ]"
                >
                    {{ statusLabel(order.status) }}
                </span>
            </div>

            <button
                v-if="order.next_status"
                type="button"
                :disabled="form.processing"
                class="rounded-md bg-gray-900 px-5 py-2.5 text-sm font-medium text-white transition-colors hover:bg-gray-800 disabled:opacity-50"
                @click="advanceStatus"
            >
                {{ form.processing ? 'Ažuriranje...' : `Promeni u: ${nextStatusLabel(order.next_status)}` }}
            </button>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Items -->
            <div class="lg:col-span-2 space-y-6">
                <div class="overflow-hidden rounded-md border border-gray-200 bg-white">
                    <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                        <h2 class="text-sm font-semibold tracking-[0.1em] text-gray-900 uppercase">
                            Stavke
                        </h2>
                    </div>
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs font-semibold tracking-[0.1em] text-gray-500 uppercase">
                            <tr>
                                <th class="px-6 py-3">Proizvod</th>
                                <th class="px-6 py-3">Brend</th>
                                <th class="px-6 py-3">Veličina</th>
                                <th class="px-6 py-3">Količina</th>
                                <th class="px-6 py-3 text-right">Cena</th>
                                <th class="px-6 py-3 text-right">Ukupno</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr
                                v-for="item in order.items"
                                :key="item.product_name + item.size_label"
                                class="hover:bg-gray-50"
                            >
                                <td class="px-6 py-4 font-medium text-gray-900">{{ item.product_name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ item.brand_name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ item.size_label }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ item.quantity }}</td>
                                <td class="px-6 py-4 text-right text-gray-600">{{ formatPrice(item.unit_price) }}</td>
                                <td class="px-6 py-4 text-right font-medium text-gray-900">{{ formatPrice(item.total_price) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Sidebar info -->
            <div class="space-y-6">
                <!-- Customer -->
                <div class="rounded-md border border-gray-200 bg-white p-6">
                    <h2 class="mb-4 text-sm font-semibold tracking-[0.1em] text-gray-900 uppercase">
                        Kupac
                    </h2>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-xs font-medium tracking-[0.1em] text-gray-500 uppercase">Ime</p>
                            <p class="mt-1 text-gray-900">{{ order.customer.name }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium tracking-[0.1em] text-gray-500 uppercase">Email</p>
                            <p class="mt-1 text-gray-900">{{ order.customer.email }}</p>
                        </div>
                        <div v-if="order.customer.phone">
                            <p class="text-xs font-medium tracking-[0.1em] text-gray-500 uppercase">Telefon</p>
                            <p class="mt-1 text-gray-900">{{ order.customer.phone }}</p>
                        </div>
                    </div>
                </div>

                <!-- Shipping -->
                <div class="rounded-md border border-gray-200 bg-white p-6">
                    <h2 class="mb-4 text-sm font-semibold tracking-[0.1em] text-gray-900 uppercase">
                        Dostava
                    </h2>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-xs font-medium tracking-[0.1em] text-gray-500 uppercase">Adresa</p>
                            <p class="mt-1 text-gray-900">{{ order.shipping.address }} {{ order.shipping.house_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium tracking-[0.1em] text-gray-500 uppercase">Grad / ZIP</p>
                            <p class="mt-1 text-gray-900">{{ order.shipping.zip }} {{ order.shipping.city }}</p>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="rounded-md border border-gray-200 bg-white p-6">
                    <h2 class="mb-4 text-sm font-semibold tracking-[0.1em] text-gray-900 uppercase">
                        Rezime
                    </h2>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Način plaćanja</span>
                            <span class="font-medium text-gray-900">{{ order.payment_method === 'cash_on_delivery' ? 'Pouzeće' : order.payment_method }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Dostava</span>
                            <span class="font-medium text-gray-900">{{ formatPrice(order.shipping_cost) }}</span>
                        </div>
                        <div class="border-t border-gray-100 pt-3 flex justify-between">
                            <span class="font-semibold text-gray-900">Ukupno</span>
                            <span class="font-semibold text-gray-900">{{ formatPrice(order.total_amount) }}</span>
                        </div>
                        <div class="pt-1 text-xs text-gray-400">
                            Kreirano: {{ order.created_at }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
