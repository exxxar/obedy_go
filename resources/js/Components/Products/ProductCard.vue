<script setup>
import {nextTick, ref} from "vue"
import {modals, popover} from "@/app"
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

const selectedProduct = ref(null)

const setPopover = async (id) => {
    [...document.getElementsByName('btn-popover-'+id)].forEach(element => popover.getOrCreateInstance(element).dispose())
    await nextTick(() => {
        [...document.getElementsByName('btn-popover-'+id)].forEach(element => popover.getOrCreateInstance(element))
    })
}

const openProductModal = async (id) => {
    selectedProduct.value = props.product
    await setPopover(id)

    await nextTick(() => {
        let productModal = document.getElementById('productModal');
        modals.getOrCreateInstance(productModal).show()
        productModal.addEventListener('hidden.bs.modal', (event) => {
            selectedProduct.value = null
        }, { once: true })
    })
}
</script>

<template>
    <div class="day-item" :class="week ? 'week-product' : ''">
        <h3>{{ product.title }}</h3>
        <img v-lazy="product.image" :alt="product.title" class="w-100" @click="openProductModal(product.id)">
        <ProductControls :product="product"></ProductControls>
        <div class="week-label" v-if="product.is_week">Бесплатная доставка</div>
    </div>
    <ProductModal v-if="selectedProduct !== null" :product="selectedProduct"></ProductModal>
</template>
