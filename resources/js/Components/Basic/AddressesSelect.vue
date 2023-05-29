<script setup>
import {useUserStore} from '@/stores/userStore.js'
import {storeToRefs} from "pinia"
import {ref, watch} from "vue"

const props = defineProps({
    formAddress: {
        type: String,
        default: null
    },
    errors: {
        type: Array,
        required: true
    },
    blur: {
        type: Function,
        default: () => {}
    },
    clearMessage: {
        type: Function,
        default: () => {}
    }
})

const emit = defineEmits(['update:formAddress', 'option:selected', 'search:blur'])

const {user} = storeToRefs(useUserStore())

const vFormAddress = ref(null)
const closeOnSelect = ref(true)
const deletedOption = ref('')

watch(vFormAddress, () => {
    emit('update:formAddress', vFormAddress.value)
})

const search = (value) => {
    if (value !== null && value !== '') {
        vFormAddress.value = value
        props.clearMessage()
    }
}


const searchBlur = () => {
    addOption(true)
    props.blur()
}

const addOption = (isSearchBlur = false) => {
    if (!user.value.addresses.some((x) => x === vFormAddress.value) && vFormAddress.value !== null && vFormAddress.value !== '')
        user.value.addresses.push(vFormAddress.value)
    if (isSearchBlur)
        emit('option:selected', vFormAddress.value)
}

const deleteOptionAddress = async (value) => {
    closeOnSelect.value = false
    vFormAddress.value = null
    user.value.addresses.splice(user.value.addresses.indexOf(value), 1)
    deletedOption.value = value
}

const selected = (value) => {
    if (value !== deletedOption.value) {
        vFormAddress.value = value
        if (!closeOnSelect.value)
            document.getElementsByClassName('vs__search')[0].blur()
        closeOnSelect.value = true
    } else {
        deletedOption.value = ''
        vFormAddress.value = null
    }
}
</script>

<template>
    <v-select
        v-model="vFormAddress"
        :options="user.addresses"
        taggable
        @search="search"
        @search:blur="searchBlur"
        @change="addOption"
        :closeOnSelect="closeOnSelect"
        @option:selected="selected"

        placeholder="Адрес доставки"
        class="form-control w-100 mt-2 mb-0 ps-3 pe-4 py-3"
        :class="[errors.length > 0 ? 'is-invalid' : '',
        errors.length === 0 && vFormAddress !== null ? 'is-valid' : '']">
        <template #option="{label}">
            <button v-if="user.addresses.indexOf(label) !== -1"
                    type="button" class="btn-close position-relative z-3" aria-label="Close"
                    @click="deleteOptionAddress(label)"></button>
            {{ label }}
        </template>
    </v-select>
</template>

<style lang="scss">

.v-select {
    width: 100%;
    padding: 15px;
    //border: 1px #d50c0d solid;
    margin-top: 0.5rem;
    border-radius: 5px;

    & .vs__dropdown-toggle {
        border: 0;
        padding: 0;

        & .vs__selected-options {
            flex-wrap: nowrap;
            word-wrap: break-word;
        }

        & .vs__search {
            width: 0 !important;
            border: 0 !important;
            margin: 4px 0 0 !important;
            padding: 0 7px !important;
        }

        & .vs__actions .vs__clear {
            display: flex;
        }

    }
}
</style>
