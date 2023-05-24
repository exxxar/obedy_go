<script setup>
import {vMaska} from "maska"

defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    placeholder: {
        type: String,
        required: true,
    },
    type: {
        type: String,
        default: 'text',
    },
    mask: {
        type: String,
        default: null,
    },
    errors: {
        type: Array,
        default: [],
    },
    blur: {
        default: null
    }
})
defineEmits(['update:modelValue'])
</script>
<template>
    <input :type="type" class="form-control w-100 mt-2 mb-0 px-4 py-3" :placeholder="placeholder"
           :value="modelValue"
           :class="[errors.length > 0 ? 'is-invalid' : '', (errors.length === 0 && modelValue !== '') ? 'is-valid' : '']"
           v-maska :data-maska="mask"
           @input="$emit('update:modelValue', $event.target.value)"
           @blur="blur"
           >
    <div v-if="errors.length > 0" class="invalid-feedback" v-for="error in errors">{{ error }}</div>
</template>
