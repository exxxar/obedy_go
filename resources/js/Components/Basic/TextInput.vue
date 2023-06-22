<script setup>
import {vMaska} from "maska"

const props = defineProps({
    modelValue: {
        type: String,
        default: null,
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
    error: {
      type: Boolean,
      default: true
    },
    errors: {
        type: Array,
        default: [],
    },
    blur: {
        default: null
    },
    keyupOnlyNumber: {
        default: null
    },
    isMasked: {
        type: Boolean,
        default: false
    },
    inputClass: {
        type: String,
        default: null
    },
    inputId: {
        type: String,
        default: null
    },
    ariaDescribedby: {
        type: String,
        default: null
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

const onPress = (e) => {
    if (props.keyupOnlyNumber !== null)
        emit('update:modelValue', e.target.value.replace(/[^0-9]/g, ""))
}
</script>
<template>
    <input :type="type" class="form-control w-100 mt-2 mb-0 px-4 py-3 col-important" :placeholder="placeholder"
           :value="modelValue" :id="inputId"
           :class="[
               errors.length > 0 ? 'is-invalid' : '',
               (errors.length === 0 && modelValue !== '' && modelValue !== null) ? 'is-valid' : '',
               inputClass
           ]"
           v-maska :data-maska="mask" @maska="onMaska"
           @input="onInput"
           @blur="blur"
           @keyup="onPress"
           :aria-describedby="ariaDescribedby"
           autocomplete="off"
           readonly onfocus="this.removeAttribute('readonly')">
    <div v-if="errors.length > 0 && error" class="invalid-feedback" v-for="err in errors">{{ err }}</div>
</template>
<style scoped>
.m--0{
    margin: 0 !important;
}
.col-important {
    flex: 1 0 0% !important;
}
</style>
