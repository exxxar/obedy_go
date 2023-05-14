<script setup>
import {useMainStore} from '@/stores/mainStore.js'
import {storeToRefs} from "pinia";
//get store
const main = useMainStore()
const {foodParts, part} = storeToRefs(main)
</script>

<template>
    <div class="row w-100 m-0 fixed-main-menu d-none d-md-block">
        <div class="container upper-menu">
            <div class="row d-flex justify-content-between flex-wrap w-100 p-0 m-0">
                <div class="col-lg-3 col-6 col-md-3 col-sm-6" v-for="foodPart in foodParts">
                    <div :class="'item-tab '+(part === foodPart.partId ? 'active':'')" @click="part = foodPart.partId">
                        <h3><img :src="foodPart.image" alt="">{{ foodPart.title }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
