<script setup>
import {inject, ref} from "vue"

const is_search_opened = ref(false)
const filteredText = inject('filteredText')

const closeFilter = () => {
    if (filteredText.value == null || filteredText.value.length === 0)
        is_search_opened.value = false
}
</script>

<template>
    <div class="search" @mouseenter="is_search_opened = true">
        <div class="search-icon" v-if="!is_search_opened">
            <img v-lazy="'/images/search.png'" alt="Поиск">
        </div>
        <input type="search" class="search-input" v-if="is_search_opened" v-model="filteredText"
               @mouseleave="closeFilter" placeholder="Быстрый поиск по продуктам...">
    </div>
</template>

