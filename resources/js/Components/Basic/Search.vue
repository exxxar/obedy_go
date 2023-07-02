<script setup>
import { ref } from "vue"

const props = defineProps({
    filteredText: {
        type: String,
        default: ''
    }
})

const emit = defineEmits(['update:filteredText'])

const is_search_opened = ref(false)

const onInput = (event) => {
    emit('update:filteredText', event.target.value)
}

const closeFilter = () => {
    if (props.filteredText === '' || props.filteredText.length === 0)
        is_search_opened.value = false
}
</script>

<template>
    <div class="search" @mouseenter="is_search_opened = true">
        <div class="search-icon" v-if="!is_search_opened"><img v-lazy="'/images/search.png'" alt="Поиск"></div>
        <input type="search" class="search-input" v-if="is_search_opened" :value="filteredText" @input="onInput"
               @mouseleave="closeFilter" placeholder="Быстрый поиск по продуктам...">
    </div>
</template>

