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
    <input :type="type" class="form-control w-100 mb-2 px-4 py-3" :placeholder="placeholder"
           :value="modelValue"
           :class="errors.length > 0 ? 'is-invalid' : ''"
           v-maska :data-maska="mask"
           @input="$emit('update:modelValue', $event.target.value)"
           @blur="blur"
           required>
    <div v-if="errors.length > 0" class="invalid-feedback" v-for="error in errors">{{ error }}</div>
</template>
