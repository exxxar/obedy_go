<script setup>
import { ref } from "vue"

const props = defineProps({
    filteredText: {
        type: String,
        default: ''
    },
    filteredCategory: {
        type: String,
        default: 'name'
    }
})

const emit = defineEmits(['update:filteredText', 'update:filteredCategory'])

const is_search_opened = ref(false)

const onInput = (event) => {
    emit('update:filteredText', event.target.value)
}
const onChange = (event) => {
    emit('update:filteredCategory', event.target.value)
}

const closeFilter = () => {
    if (props.filteredText === '' || props.filteredText.length === 0) {
        is_search_opened.value = false
        emit('update:filteredCategory', 'name')
    }
}
</script>

<template>
    <div class="search" @mouseenter="is_search_opened = true" @mouseleave="closeFilter">
        <div class="search-icon" v-if="!is_search_opened"><img v-lazy="'/images/search.png'" alt="Поиск"></div>
        <input type="search" class="search-input" v-if="is_search_opened" :value="filteredText" @input="onInput"
               placeholder="Быстрый поиск по продуктам...">
        <div v-if="is_search_opened" class="search-check-group">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1"
                       :value="'name'" :checked="'name' === filteredCategory"  @change="onChange">
                <label class="form-check-label" for="flexRadioDefault1">по названиям</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2"
                       :value="'ingredients'" :checked="'ingredients' === filteredCategory" @change="onChange">
                <label class="form-check-label" for="flexRadioDefault2">по ингридиентам</label>
            </div>
        </div>

    </div>

</template>

