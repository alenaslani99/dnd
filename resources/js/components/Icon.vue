<script setup lang="ts">
interface Props {
    name: 'search' | 'user' | 'cart' | 'menu' | 'close' | 'logout' | 'dashboard' | 'package' | 'shopping-bag' | 'users' | 'tag' | 'percent' | 'arrow-left' | 'edit' | 'trash' | 'eye' | 'mail'
    size?: number
}

withDefaults(defineProps<Props>(), {
    size: 20,
})

const icons: Record<string, string> = {
    search: 'M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm10 2-4.35-4.35',
    user: 'M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2M12 7a4 4 0 1 0 0-8 4 4 0 0 0 0 8z',
    cart: 'M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12M8 21h.01M19 21h.01',
    menu: 'M4 5h16M4 12h16M4 19h16',
    close: 'M18 6 6 18M6 6l12 12',
    logout: 'M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9',
}
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        :width="size"
        :height="size"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
    >
        <path v-if="name === 'search'" d="M11 19a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm10 2-4.35-4.35" />
        <template v-else-if="name === 'user'">
            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
        </template>
        <template v-else-if="name === 'cart'">
            <circle cx="8" cy="21" r="1" />
            <circle cx="19" cy="21" r="1" />
            <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
        </template>
        <template v-else-if="name === 'menu'">
            <path d="M4 5h16" />
            <path d="M4 12h16" />
            <path d="M4 19h16" />
        </template>
        <template v-else-if="name === 'close'">
            <path d="M18 6 6 18" />
            <path d="m6 6 12 12" />
        </template>
        <template v-else-if="name === 'logout'">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <polyline points="16 17 21 12 16 7" />
            <line x1="21" x2="9" y1="12" y2="12" />
        </template>
        <template v-else-if="name === 'dashboard'">
            <rect x="3" y="3" width="7" height="7" rx="1" />
            <rect x="14" y="3" width="7" height="7" rx="1" />
            <rect x="14" y="14" width="7" height="7" rx="1" />
            <rect x="3" y="14" width="7" height="7" rx="1" />
        </template>
        <template v-else-if="name === 'package'">
            <path d="m7.5 4.27 9 5.15M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z" />
            <path d="m3.3 7 8.7 5 8.7-5" />
            <path d="M12 22V12" />
        </template>
        <template v-else-if="name === 'shopping-bag'">
            <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
            <path d="M3 6h18" />
            <path d="M16 10a4 4 0 0 1-8 0" />
        </template>
        <template v-else-if="name === 'users'">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </template>
        <template v-else-if="name === 'tag'">
            <path d="M12 2H2v10l9 9 9-9-8-8Z" />
            <circle cx="6" cy="6" r="1.5" />
        </template>
        <template v-else-if="name === 'percent'">
            <path d="M19 5 5 19" />
            <circle cx="9" cy="9" r="2" />
            <circle cx="15" cy="15" r="2" />
        </template>
        <template v-else-if="name === 'arrow-left'">
            <path d="m12 19-7-7 7-7" />
            <path d="M19 12H5" />
        </template>
        <template v-else-if="name === 'edit'">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
        </template>
        <template v-else-if="name === 'trash'">
            <polyline points="3 6 5 6 21 6" />
            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
        </template>
        <template v-else-if="name === 'eye'">
            <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
            <circle cx="12" cy="12" r="3" />
        </template>
        <template v-else-if="name === 'mail'">
            <rect x="2" y="4" width="20" height="16" rx="2" />
            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
        </template>
    </svg>
</template>
