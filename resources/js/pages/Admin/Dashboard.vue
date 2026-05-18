<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/layouts/AdminLayout.vue'
import Icon from '@/components/Icon.vue'
import adminOrdersRoute from '@/routes/admin/orders'
import adminProductsRoute from '@/routes/admin/products'
import adminBrandsRoute from '@/routes/admin/brands'
import adminMessagesRoute from '@/routes/admin/messages'
import { statusLabel, formatPrice } from '@/lib/utils'

defineOptions({ layout: AdminLayout })

const props = defineProps<{
    stats: {
        total_orders: number
        pending_orders: number
        today_revenue: number
        total_products: number
        total_brands: number
        unread_messages: number
    }
    recentOrders: {
        id: number
        order_number: string
        customer_name: string
        total_amount: number
        status: string
        created_at: string
        items_count: number
    }[]
    ordersByStatus: {
        label: string
        value: number
        color: string
    }[]
}>()

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

const statCards = [
    {
        label: 'Ukupno porudžbina',
        value: props.stats.total_orders,
        icon: 'shopping-bag',
        href: adminOrdersRoute.index.url(),
    },
    {
        label: 'Na čekanju',
        value: props.stats.pending_orders,
        icon: 'shopping-bag',
        href: adminOrdersRoute.index.url(),
    },
    {
        label: 'Današnji prihod',
        value: `${formatPrice(props.stats.today_revenue)}`,
        icon: 'tag',
        href: '#',
    },
    {
        label: 'Proizvoda',
        value: props.stats.total_products,
        icon: 'package',
        href: adminProductsRoute.index.url(),
    },
    {
        label: 'Brendova',
        value: props.stats.total_brands,
        icon: 'tag',
        href: adminBrandsRoute.index.url(),
    },
    {
        label: 'Nepročitanih poruka',
        value: props.stats.unread_messages,
        icon: 'mail',
        href: adminMessagesRoute.index.url(),
    },
]
</script>

<template>
    <Head title="Dashboard" />

    <div class="space-y-8">
        <!-- Header -->
        <div>
            <h1 class="font-serif text-2xl font-medium tracking-wide text-gray-900 lg:text-3xl">
                Dashboard
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Pregled ključnih metrika i aktivnosti prodavnice.
            </p>
        </div>

        <!-- Stat Cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <Link
                v-for="card in statCards"
                :key="card.label"
                :href="card.href"
                class="group overflow-hidden rounded-md border border-gray-200 bg-white p-5 transition-all hover:border-gray-900 hover:shadow-sm"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium tracking-[0.1em] text-gray-500 uppercase">
                            {{ card.label }}
                        </p>
                        <p class="mt-2 font-serif text-2xl font-medium text-gray-900">
                            {{ card.value }}
                        </p>
                    </div>
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-50 transition-colors group-hover:bg-gray-100">
                        <Icon :name="card.icon" :size="18" class="text-gray-400 group-hover:text-gray-600" />
                    </div>
                </div>
            </Link>
        </div>

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
            <!-- Recent Orders -->
            <div class="lg:col-span-2">
                <div class="overflow-hidden rounded-md border border-gray-200 bg-white">
                    <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                        <h2 class="text-sm font-semibold tracking-[0.1em] text-gray-900 uppercase">
                            Nedavne porudžbine
                        </h2>
                        <Link
                            :href="adminOrdersRoute.index.url()"
                            class="text-xs font-medium text-gray-500 transition-colors hover:text-gray-900"
                        >
                            Pogledaj sve →
                        </Link>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-xs font-semibold tracking-[0.1em] text-gray-500 uppercase">
                                <tr>
                                    <th class="whitespace-nowrap px-5 py-3">Broj</th>
                                    <th class="whitespace-nowrap px-5 py-3">Kupac</th>
                                    <th class="whitespace-nowrap px-5 py-3">Status</th>
                                    <th class="whitespace-nowrap px-5 py-3">Ukupno</th>
                                    <th class="whitespace-nowrap px-5 py-3">Datum</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <tr
                                    v-for="order in recentOrders"
                                    :key="order.id"
                                    class="transition-colors hover:bg-gray-50"
                                >
                                    <td class="whitespace-nowrap px-5 py-3">
                                        <Link
                                            :href="adminOrdersRoute.show.url(order.order_number)"
                                            class="font-medium text-gray-900 underline-offset-4 hover:underline"
                                        >
                                            #{{ order.order_number }}
                                        </Link>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3 text-gray-600">{{ order.customer_name }}</td>
                                    <td class="whitespace-nowrap px-5 py-3">
                                        <span
                                            :class="[
                                                'inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium',
                                                statusColor(order.status),
                                            ]"
                                        >
                                            {{ statusLabel(order.status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3 font-medium text-gray-900">
                                        {{ formatPrice(order.total_amount) }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-3 text-gray-500">{{ order.created_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div v-if="recentOrders.length === 0" class="py-10 text-center">
                        <p class="text-sm text-gray-400">Nema nedavnih porudžbina.</p>
                    </div>
                </div>
            </div>

            <!-- Orders by Status -->
            <div>
                <div class="overflow-hidden rounded-md border border-gray-200 bg-white">
                    <div class="border-b border-gray-100 px-5 py-4">
                        <h2 class="text-sm font-semibold tracking-[0.1em] text-gray-900 uppercase">
                            Statusi porudžbina
                        </h2>
                    </div>

                    <div class="divide-y divide-gray-100">
                        <div
                            v-for="item in ordersByStatus"
                            :key="item.label"
                            class="flex items-center justify-between px-5 py-3.5"
                        >
                            <div class="flex items-center gap-3">
                                <span
                                    class="block h-2.5 w-2.5 rounded-full"
                                    :class="{
                                        'bg-yellow-400': item.color === 'yellow',
                                        'bg-blue-500': item.color === 'blue',
                                        'bg-purple-500': item.color === 'purple',
                                        'bg-green-500': item.color === 'green',
                                        'bg-gray-400': item.color === 'gray',
                                    }"
                                />
                                <span class="text-sm text-gray-600">{{ item.label }}</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900">{{ item.value }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
