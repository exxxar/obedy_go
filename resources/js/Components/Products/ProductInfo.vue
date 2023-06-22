<script setup>

import {computed} from "vue";

const props = defineProps({
    product: {
        type: Object,
        default: {}
    },
    addMenu: {
        type: Boolean,
        default: false
    }
})

const imageSrc = computed(() => {
    return typeof props.product.image === 'string'
        ? props.product.image
        : URL.createObjectURL(props.product.image)
})
</script>

<template>
    <div class="row w-100">
        <div :class="!addMenu ? 'col-md-7 col-sm-6' : 'col-md-4 col-sm-3'">
            <img v-if="product.image" :src="imageSrc" class="img-fluid w-100" alt="">
        </div>
        <div class="col">
            <div class="d-flex flex-column">
                <p v-if="product.description">{{ product.description }}</p>
                <ul v-if="product.positions">
                    <li v-for="pos in product.positions" class="d-flex mb-2 flex-wrap text-dark">
                        <div>
                            <span class="badge bg-danger me-1" v-if="pos.weight">{{ pos.weight }} гр.</span>
                            <p v-if="pos.title">{{ pos.title }}</p>
                        </div>
                    </li>
                </ul>
                <p v-if="product.price" class="text-dark">Цена: <strong>{{ product.price }} руб.</strong></p>
                <p v-if="product.weight" class="text-dark">Масса: <strong>{{ product.weight }} гр.</strong></p>

                <slot name="controls"/>
            </div>
        </div>
    </div>
</template>
