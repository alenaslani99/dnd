<script setup lang="ts">
import { computed } from 'vue'
import { statusLabel } from '@/lib/utils'

const props = defineProps<{
    status: string
}>()

const label = computed(() => statusLabel(props.status))
</script>

<template>
    <span class="text-sm font-medium text-gray-900">{{ label }}</span>
</template>
