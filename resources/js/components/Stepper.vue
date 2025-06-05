<script setup>
defineProps({
    steps: {
        type: Array,
        required: true,
    },
    modelValue: Number
})
</script>

<template>
    <div class="tw-flex tw-items-center tw-justify-between tw-w-full tw-mb-4 sm:tw-mb-5">
        <div @click.prevent="step.onClick()" v-for="(step,i) in steps" :key="`step-${i}-${step.text}`" :class="{'bg-opacity-25': modelValue !== i}" class="rounded tw-cursor-pointer tw-flex tw-items-center tw-justify-center tw-w-10 tw-h-10 bg-primary lg:tw-h-12 lg:tw-w-12 tw-shrink-0">
            <span :class="{'text-white': modelValue === i, 'text-primary': modelValue !== i}">{{ step.text }}</span>
        </div>
    </div>
</template>
