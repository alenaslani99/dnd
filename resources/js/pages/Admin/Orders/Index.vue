<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import Icon from '@/components/Icon.vue'
import adminOrdersRoute from '@/routes/admin/orders'
import { useDragScroll } from '@/composables/useDragScroll'
import { statusLabel } from '@/lib/utils'

defineOptions({ layout: AdminLayout })

const props = defineProps<{
    orders: {
        data: {
            id: number
            order_number: string
            customer_name: string
            customer_email: string
            total_amount: number
            status: string
            created_at: string
            items_count: number
        }[]
        current_page: number
        last_page: number
        prev_page_url: string | null
        next_page_url: string | null
        total: number
    }
    filters: {
        search: string | null
        status: string | null
    }
    statuses: { value: string; label: string }[]
}>()

const search = ref(props.filters.search ?? '')
const selectedStatus = ref(props.filters.status ?? '')
const dragScroll = useDragScroll()

watch(search, () => {
    router.get(adminOrdersRoute.index.url(), {
        search: search.value || undefined,
        status: selectedStatus.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
})

watch(selectedStatus, () => {
    router.get(adminOrdersRoute.index.url(), {
        search: search.value || undefined,
        status: selectedStatus.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
})

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
</script>

<template>
    <Head title="Porudžbine" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="font-serif text-2xl font-medium tracking-wide text-gray-900 lg:text-3xl">
                Porudžbine
            </h1>
            <span class="text-sm text-gray-500">
                {{ orders.total }} ukupno
            </span>
        </div>

        <!-- Toolbar -->
        <div class="flex flex-wrap items-center gap-4">
            <!-- Search -->
            <div class="relative flex-1 min-w-[16rem]">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                    <Icon name="search" :size="16" />
                </div>
                <input
                    v-model="search"
                    type="text"
                    placeholder="Pretraži porudžbine..."
                    class="w-full rounded-md border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none transition-colors placeholder:text-gray-400 focus:border-gray-900"
                />
            </div>

            <!-- Status Filter -->
            <div class="relative">
                <select
                    v-model="selectedStatus"
                    class="cursor-pointer appearance-none rounded-md border border-gray-200 bg-white py-2.5 pr-10 pl-4 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
                >
                    <option value="">Svi statusi</option>
                    <option
                        v-for="s in statuses"
                        :key="s.value"
                        :value="s.value"
                    >
                        {{ s.label }}
                    </option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m6 9 6 6 6-6" /></svg>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-md border border-gray-200 bg-white">
            <div
                ref="dragScroll.el"
                class="overflow-x-auto cursor-grab"
                @mousedown="dragScroll.onMouseDown"
                @mouseleave="dragScroll.onMouseLeave"
                @mouseup="dragScroll.onMouseUp"
                @mousemove="dragScroll.onMouseMove"
            >
                <table class="w-full min-w-[56rem] text-left text-sm">
                    <thead class="bg-gray-50 text-xs font-semibold tracking-[0.1em] text-gray-500 uppercase">
                        <tr>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Broj</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Kupac</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Email</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Ukupno</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Status</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Stavki</th>
                            <th class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">Datum</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <tr
                            v-for="order in orders.data"
                            :key="order.id"
                            class="transition-colors hover:bg-gray-50"
                        >
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">
                                <Link
                                    :href="adminOrdersRoute.show.url(order.order_number)"
                                    class="font-medium text-gray-900 underline-offset-4 hover:underline"
                                >
                                    #{{ order.order_number }}
                                </Link>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 text-gray-600">{{ order.customer_name }}</td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 text-gray-600">{{ order.customer_email }}</td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 font-medium text-gray-900">
                                {{ new Intl.NumberFormat('sr-RS').format(order.total_amount) }} RSD
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4">
                                <span
                                    :class="[
                                        'inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium',
                                        statusColor(order.status),
                                    ]"
                                >
                                    {{ statusLabel(order.status) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 text-gray-600">{{ order.items_count }}</td>
                            <td class="whitespace-nowrap px-4 py-3 lg:px-6 lg:py-4 text-gray-500">{{ order.created_at }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="orders.last_page > 1" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-500">
                Strana {{ orders.current_page }} od {{ orders.last_page }}
            </p>
            <div class="flex items-center gap-2">
                <Link
                    v-if="orders.prev_page_url"
                    :href="orders.prev_page_url"
                    preserve-state
                    class="rounded-md border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:border-gray-900 hover:text-gray-900"
                >
                    Prethodna
                </Link>
                <Link
                    v-if="orders.next_page_url"
                    :href="orders.next_page_url"
                    preserve-state
                    class="rounded-md border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:border-gray-900 hover:text-gray-900"
                >
                    Sledeća
                </Link>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="orders.data.length === 0" class="py-16 text-center">
            <p class="text-lg text-gray-400">Nema porudžbina koje odgovaraju pretrazi.</p>
        </div>
    </div>
</template>
