<script setup>
const props = defineProps({
    modelValue: {
        type: String,
        default: null,
    },
    placeholder: {
        type: String,
        required: true,
    },
    errors: {
        type: Array,
        default: [],
    },
    textareaClass: {
        type: String,
        default: null
    },
    textareaId: {
        type: String,
        default: null
    },
    textareaName: {
        type: String,
        default: null
    },
    textareaGroupClass: {
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

const textAreaAdjust = (event) => {
    let element = event.target
    element.style.height = "1px";
    element.style.height = (16 + element.scrollHeight) + "px";
}
const onInput = (event) => {
    emit('update:modelValue', event.target.value)
}
</script>

<template>
    <div class="input-group" :class="textareaGroupClass">
        <span class="input-group-text justify-content-center w-40px" v-if="groupTextIconLeft !== null">
            <font-awesome-icon :icon="groupTextIconLeft"/>
        </span>
        <textarea :name="textareaName"
                  :id="textareaId"
                  :value="modelValue"
                  @input="onInput"
                  class="form-control m-0 px-4 py-3 resize-n overflow-hidden"
                  :placeholder="placeholder"
                  :class="[textareaClass, errors.length > 0 ? 'is-invalid' : '',
                    errors.length === 0 && modelValue !== '' && modelValue !== null ? 'is-valid' : '']"
                  @keyup="textAreaAdjust">
        </textarea>
        <span class="input-group-text justify-content-center w-40px" v-if="groupTextIconRight !== null">
            <font-awesome-icon :icon="groupTextIconRight"/>
        </span>
        <div class="invalid-feedback"
             v-if="errors.length > 0"
             v-for="err in errors">
            {{ err }}
        </div>
    </div>
</template>

<style scoped>

</style>
