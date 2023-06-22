<script setup>
import {provide} from "vue"
import Modal from '@/Components/Basic/Modal.vue'
import ProductControls from "@/Components/Products/ProductControls.vue"
import ProductInfo from "@/Components/Products/ProductInfo.vue"

const props = defineProps({
    product: {
        type: Object,
        default: {}
    }
})
provide('inModal', true)
</script>

<template>
    <Modal id="productModal" class_size="modal-lg">
        <template #title>
            <h3><span class="badge bg-danger">"{{ product.title }}"</span></h3>
        </template>
        <template #body>
            <ProductInfo v-if="!product.is_week" :product="product">
                <template #controls>
                    <ProductControls  :product="product"></ProductControls>
                </template>
            </ProductInfo>
            <div class="row" v-if="product.is_week">
                <ul v-if="product.positions" class="row p-2">
                    <li v-for="pos in product.positions" class="col-md-6 mt-2">
                        <div class="row">
                            <div class="col-md-6">
                                <img :src="pos.image" class="img-fluid" alt="">
                            </div>
                            <div class="col-md-6">
                                <p><span class="badge bg-danger me-1">{{ pos.weight }} гр.</span>{{ pos.title }}</p>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="row w-100">
                    <div class="col-md-4 d-flex justify-content-center align-items-center">
                        <p v-if="product.price" class="text-dark">Полная цена: <strong>{{ product.price }} руб.</strong>
                        </p>
                    </div>
                    <div class="col-md-4 d-flex justify-content-center align-items-center">
                        <p v-if="product.weight" class="text-dark">Общая масса: <strong>{{ product.weight }}
                            гр.</strong></p>
                    </div>
                    <div class="col-md-4">
                        <ProductControls :product="product"></ProductControls>
                    </div>
                </div>
            </div>
        </template>
    </Modal>
</template>
