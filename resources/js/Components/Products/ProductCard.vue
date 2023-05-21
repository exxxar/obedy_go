<script setup>
import {nextTick} from "vue"
import {popover} from "@/app"
import ProductControls from "@/Components/Products/ProductControls.vue"
import ProductModal from "@/Components/Products/ProductModal.vue"

const props = defineProps({
    product: {
        type: Object,
        default: {}
    },
    week: {
        type: Boolean,
        default: false
    }
})

const setPopover = async (id) => {
    [...document.getElementsByName('btn-popover-'+id)].forEach(element => popover.getOrCreateInstance(element).dispose());
    await nextTick();
    [...document.getElementsByName('btn-popover-'+id)].forEach(element => popover.getOrCreateInstance(element))
}
</script>

<template>
    <div class="day-item" :class="week ? 'week-product' : ''">
        <h3>{{ product.title }}</h3>
        <img v-lazy="product.image" :alt="product.title" class="w-100" data-bs-toggle="modal" :data-bs-target="'#modal-'+product.id" @click="setPopover(product.id)">
        <ProductControls :product="product"></ProductControls>
        <div class="week-label" v-if="product.is_week">Бесплатная доставка</div>
    </div>
    <ProductModal :product="product"></ProductModal>
</template>
