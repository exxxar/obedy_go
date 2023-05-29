<script setup>
import {vMaska} from "maska"

const props = defineProps({
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
    },
    isMasked: {
        type: Boolean,
        default: false
    }
})
const emit = defineEmits(['update:modelValue'])

const onMaska = (event) => {
    if (props.mask !== null && !props.isMasked) {
        emit('update:modelValue', '7' + event.detail.unmasked)
    }
}

const onInput = (e) => {
    if (props.mask === null || props.isMasked) {
        emit('update:modelValue', e.target.value)
    }
}
</script>
<template>
    <input :type="type" class="form-control w-100 mt-2 mb-0 px-4 py-3" :placeholder="placeholder"
           :value="modelValue"
           :class="[errors.length > 0 ? 'is-invalid' : '', (errors.length === 0 && modelValue !== '' && modelValue !== null) ? 'is-valid' : '']"
           v-maska :data-maska="mask" @maska="onMaska"
           @input="onInput"
           @blur="blur"
           >
    <div v-if="errors.length > 0" class="invalid-feedback" v-for="error in errors">{{ error }}</div>
</template>
