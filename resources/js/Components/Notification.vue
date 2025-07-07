<template>
    <div
        v-if="isVisible"
        :class="[
      'fixed top-4 right-4 text-white px-6 py-3 rounded-lg shadow-lg transition-opacity duration-300 z-50',
      typeClasses[type],
      isVisible ? 'opacity-100' : 'opacity-0'
    ]"
        role="alert"
    >
        <div class="flex items-center justify-between">
            <p class="text-sm font-medium">{{ message }}</p>
            <button
                @click="$emit('close')"
                class="ml-4 text-white hover:text-gray-100 focus:outline-none"
                aria-label="Close notification"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed, watch, onUnmounted } from 'vue'

const props = defineProps({
    message: String,
    type: {
        type: String,
        default: 'info'
    },
    isVisible: Boolean
})

const emit = defineEmits(['close'])

const typeClasses = computed(() => ({
    success: 'bg-green-500',
    error: 'bg-red-500',
    info: 'bg-blue-500',
}))

let timer = null

watch(() => props.isVisible, (isVisible) => {
    if (isVisible) {
        timer = setTimeout(() => {
            emit('close')
        }, 3000)
    } else {
        if (timer) {
            clearTimeout(timer)
            timer = null
        }
    }
})

onUnmounted(() => {
    if (timer) {
        clearTimeout(timer)
    }
})
</script>
