<script setup>
import ProductControls from "@/Components/ProductControls.vue"
import ProductModal from "@/Components/ProductModal.vue"
import {provide} from "vue"

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

provide('product', props.product)
</script>

<template>
    <div class="day-item" :class="week ? 'week-product' : ''">
        <h3>{{ product.title }}</h3>
        <img v-lazy="product.image" :alt="product.title" class="w-100" data-toggle="modal" :data-target="'#modal-'+product.id">
        <ProductControls></ProductControls>
        <div class="week-label" v-if="product.is_week">Бесплатная доставка</div>
    </div>
    <ProductModal></ProductModal>
</template>
