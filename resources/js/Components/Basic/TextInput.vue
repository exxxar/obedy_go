<script setup>
import {vMaska} from "maska"

const props = defineProps({
    modelValue: {
        //type: String,
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
    inputName: {
        type: String,
        default: null
    },
    inputGroupClass: {
        type: String,
        default: null
    },
    groupTextIconLeft: {
        type: String,
        default: null
    },
    groupTextIconRight: {
        type: String,
        default: null
    }

})
const emit = defineEmits(['update:modelValue'])

const onMaska = (event) => {
    if (props.mask !== null && !props.isMasked)
        emit('update:modelValue', '7' + event.detail.unmasked)
}

const onInput = (event) => {
    if (props.mask === null || props.isMasked)
        emit('update:modelValue', event.target.value)
}

const onPress = (event) => {
    if (props.keyupOnlyNumber !== null)
        emit('update:modelValue', event.target.value.replace(/[^0-9]/g, ""))
}
</script>
<template>
    <!-- class="form-control w-100 mt-2 mb-0 px-4 py-3 col-important" -->
    <div class="input-group" :class="inputGroupClass">
        <span class="input-group-text justify-content-center w-40px" v-if="groupTextIconLeft !== null">
            <font-awesome-icon :icon="groupTextIconLeft"/>
        </span>
        <slot name="groupTextIconLeft"/>
        <input :id="inputId"
               :name="inputName"
               :type="type"
               class="form-control m-0 px-4 py-3"
               :placeholder="placeholder"
               :value="modelValue"
               :class="[inputClass, errors.length > 0 ? 'is-invalid' : '',
                    errors.length === 0 && modelValue !== '' && modelValue !== null ? 'is-valid' : '']"
               v-maska :data-maska="mask" @maska="onMaska"
               @input="onInput"
               @blur="blur"
               @keyup="onPress"
               autocomplete="off" readonly onfocus="this.removeAttribute('readonly')">
        <span class="input-group-text justify-content-center w-40px" v-if="groupTextIconRight !== null">
            <font-awesome-icon :icon="groupTextIconRight"/>
        </span>
        <slot name="groupTextIconRight"/>
        <div class="invalid-feedback"
             v-if="errors.length > 0 && error"
             v-for="err in errors">
            {{ err }}
        </div>
        <slot name="invalidFeedback"/>
    </div>
</template>
