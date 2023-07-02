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
    },
    inputClass: {
        type: String,
        default: null
    },
})

const emit = defineEmits(['update:formAddress', 'option:selected', 'search:blur'])

const {user} = storeToRefs(useUserStore())

const vFormAddress = ref(null)
const closeOnSelect = ref(true)
const deletedOption = ref('')

watch(() => vFormAddress.value, (newValue, oldValue) => {
    if (newValue)
        emit('update:formAddress', newValue)
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
    <div class="input-group">
        <span class="input-group-text justify-content-center w-40px">
           <font-awesome-icon icon="fa-solid fa-location-dot"/>
        </span>
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
            class="v--select form-control m-0 ps-3 pe-4 py-3 z-4-important"
            :class="[inputClass, errors.length > 0 ? 'is-invalid' : '',
                errors.length === 0 && vFormAddress !== '' && vFormAddress !== null ? 'is-valid' : '']"
        >
            <template #option="{label}">
                <button v-if="user.addresses.indexOf(label) !== -1"
                        type="button" class="btn-close position-relative z-3" aria-label="Close"
                        @click="deleteOptionAddress(label)"></button>
                {{ label }}
            </template>
        </v-select>
        <div class="invalid-feedback" v-if="errors.length > 0" v-for="error in errors">
            {{ error }}
        </div>
    </div>
</template>

<style lang="scss">
.v-select.v--select {
    //border: 1px #d50c0d solid;

    & .vs__dropdown-toggle {
        border: 0;
        padding: 0;
        overflow: hidden;

        & .vs__selected-options {
            flex-wrap: nowrap;
            word-wrap: break-word;
        }

        & .vs__search {
            width: 0 !important;
            border: 0 !important;
            margin: 4px 0 0 !important;
            padding: 0 7px !important;
            color: var(--bs-secondary-color);
            opacity: 1;
        }
        & .vs__actions {
            padding-right: 1rem;
        }
        & .vs__actions .vs__clear {
            display: flex;
        }

    }

    & .vs__dropdown-menu {
        & .vs__dropdown-option {
            overflow: auto;

            /* width */
            &::-webkit-scrollbar {
                //width: 5px;
                height: 0.5rem;
            }

            /* Track */
            &::-webkit-scrollbar-track {
                box-shadow: inset 0 0 0.25rem grey;
            }

            /* Handle */
            &::-webkit-scrollbar-thumb {
                background: #e3342f;
                border-radius: 0.25rem;
            }

            /* Handle on hover */
            &::-webkit-scrollbar-thumb:hover {
                background: #e64844;
            }
        }
    }
}
</style>
