<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import Icon from '@/components/Icon.vue'
import adminMessagesRoute from '@/routes/admin/messages'

defineOptions({ layout: AdminLayout })

const props = defineProps<{
    messages: {
        data: {
            id: number
            name: string
            email: string
            phone: string | null
            message: string
            is_read: boolean
            created_at: string
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
}>()

const search = ref(props.filters.search ?? '')
const selectedStatus = ref(props.filters.status ?? '')
const selectedMessage = ref<number | null>(null)

watch(search, () => {
    router.get(adminMessagesRoute.index.url(), {
        search: search.value || undefined,
        status: selectedStatus.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
})

watch(selectedStatus, () => {
    router.get(adminMessagesRoute.index.url(), {
        search: search.value || undefined,
        status: selectedStatus.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    })
})

function selectMessage(id: number): void {
    if (selectedMessage.value === id) {
        selectedMessage.value = null
        return
    }

    selectedMessage.value = id

    const msg = props.messages.data.find(m => m.id === id)
    if (msg && !msg.is_read) {
        router.patch(adminMessagesRoute.read.url(id), {}, { preserveScroll: true })
    }
}

function messagePreview(text: string, length: number = 60): string {
    if (text.length <= length) {
        return text
    }
    return text.slice(0, length).trim() + '...'
}

const statusOptions = [
    { value: '', label: 'Sve poruke' },
    { value: 'unread', label: 'Nepročitane' },
    { value: 'read', label: 'Pročitane' },
]
</script>

<template>
    <Head title="Poruke" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="font-serif text-2xl font-medium tracking-wide text-gray-900 lg:text-3xl">
                Poruke
            </h1>
            <span class="text-sm text-gray-500">
                {{ messages.total }} ukupno
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
                    placeholder="Pretraži poruke..."
                    class="w-full rounded-md border border-gray-200 bg-white py-2.5 pl-10 pr-4 text-sm text-gray-900 outline-none transition-colors placeholder:text-gray-400 focus:border-gray-900"
                />
            </div>

            <!-- Status Filter -->
            <div class="relative">
                <select
                    v-model="selectedStatus"
                    class="cursor-pointer appearance-none rounded-md border border-gray-200 bg-white py-2.5 pr-10 pl-4 text-sm text-gray-900 outline-none transition-colors focus:border-gray-900"
                >
                    <option
                        v-for="s in statusOptions"
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

        <!-- Messages List -->
        <div class="overflow-hidden rounded-md border border-gray-200 bg-white">
            <div
                v-for="msg in messages.data"
                :key="msg.id"
                class="border-b border-gray-100 last:border-b-0"
            >
                <!-- Message Row -->
                <button
                    type="button"
                    class="flex w-full items-center gap-4 px-4 py-3 text-left transition-colors hover:bg-gray-50 lg:px-6"
                    :class="[
                        selectedMessage === msg.id ? 'bg-gray-50' : '',
                        !msg.is_read ? 'bg-blue-50/30' : '',
                    ]"
                    @click="selectMessage(msg.id)"
                >
                    <!-- Unread Dot -->
                    <div class="flex shrink-0 w-2 items-center justify-center">
                        <span
                            v-if="!msg.is_read"
                            class="block h-2 w-2 rounded-full bg-blue-500"
                        />
                    </div>

                    <!-- Avatar Initial -->
                    <div
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xs font-semibold text-white uppercase"
                        :class="msg.is_read ? 'bg-gray-400' : 'bg-gray-900'"
                    >
                        {{ msg.name.charAt(0) }}
                    </div>

                    <!-- Content -->
                    <div class="flex min-w-0 flex-1 flex-col gap-0.5">
                        <div class="flex items-center gap-3">
                            <span
                                class="truncate text-sm"
                                :class="msg.is_read ? 'font-medium text-gray-700' : 'font-semibold text-gray-900'"
                            >
                                {{ msg.name }}
                            </span>
                            <span class="hidden shrink-0 text-xs text-gray-400 sm:inline">
                                &lt;{{ msg.email }}&gt;
                            </span>
                        </div>
                        <p
                            class="truncate text-sm"
                            :class="msg.is_read ? 'text-gray-500' : 'text-gray-600'"
                        >
                            {{ messagePreview(msg.message, 80) }}
                        </p>
                    </div>

                    <!-- Meta -->
                    <div class="flex shrink-0 flex-col items-end gap-0.5">
                        <span class="text-xs text-gray-400">
                            {{ msg.created_at }}
                        </span>
                    </div>
                </button>

                <!-- Expanded Message -->
                <div
                    v-if="selectedMessage === msg.id"
                    class="border-t border-gray-100 bg-gray-50/50 px-4 py-5 lg:px-8 lg:py-6"
                >
                    <div v-if="msg.phone" class="mb-3 text-sm text-gray-600">
                        Telefon: <span class="font-medium text-gray-900">{{ msg.phone }}</span>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white p-5 lg:p-6">
                        <p class="whitespace-pre-wrap text-base leading-relaxed text-gray-700">
                            {{ msg.message }}
                        </p>
                    </div>

                    <div class="mt-4 flex justify-end">
                        <a
                            :href="`mailto:${msg.email}`"
                            class="inline-flex items-center gap-2 rounded-md border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:border-gray-900 hover:text-gray-900"
                        >
                            <Icon name="mail" :size="14" />
                            Odgovori
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="messages.last_page > 1" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-500">
                Strana {{ messages.current_page }} od {{ messages.last_page }}
            </p>
            <div class="flex items-center gap-2">
                <Link
                    v-if="messages.prev_page_url"
                    :href="messages.prev_page_url"
                    preserve-state
                    class="rounded-md border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:border-gray-900 hover:text-gray-900"
                >
                    Prethodna
                </Link>
                <Link
                    v-if="messages.next_page_url"
                    :href="messages.next_page_url"
                    preserve-state
                    class="rounded-md border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:border-gray-900 hover:text-gray-900"
                >
                    Sledeća
                </Link>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="messages.data.length === 0" class="py-16 text-center">
            <Icon name="mail" :size="32" class="mx-auto mb-3 text-gray-300" />
            <p class="text-lg text-gray-400">Nema poruka.</p>
        </div>
    </div>
</template>
