<script setup>
import {inject, provide} from "vue"
import Modal from '@/Components/Modal.vue'
import ProductControls from "@/Components/ProductControls.vue"

const props = defineProps({
    product: {
        type: Object,
        default: {}
    }
})
provide('inModal', true)
</script>

<template>
    <Modal :id="'modal-' + product.id" class_size="modal-lg">
        <template #title>
            <h3><span class="badge bg-danger">"{{ product.title }}"</span></h3>
        </template>
        <template #body>
            <div class="row" v-if="!product.is_week">
                <div class="col-md-7 col-sm-6">
                    <img :src="product.image" class="img-fluid" alt="">
                </div>
                <div class="col-md-5 col-sm-6">
                    <p v-if="product.description">{{ product.description }}</p>
                    <ul v-if="product.positions">
                        <li v-for="pos in product.positions" class="d-flex mb-2 flex-wrap text-dark">
                            <p><span class="badge bg-danger me-1">{{ pos.weight }} гр.</span>{{ pos.title }}</p>
                        </li>
                    </ul>
                    <p v-if="product.price" class="text-dark">Цена: <strong>{{ product.price }} руб.</strong></p>
                    <p v-if="product.weight" class="text-dark">Масса: <strong>{{ product.weight }} гр.</strong></p>
                    <ProductControls  :product="product"></ProductControls>
                </div>
            </div>
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
