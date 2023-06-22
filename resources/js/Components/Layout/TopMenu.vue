<script setup>
import {storeToRefs} from "pinia"
import {useMainStore} from '@/stores/mainStore.js'

const {foodParts, part} = storeToRefs(useMainStore())
</script>

<template>
    <div class="container upper-menu position-fixed top-0 end-0 start-0 d-none d-md-block z-20">
        <div class="row justify-content-between">
            <div class="col-6 col-md-3 px-2 w--20" v-for="foodPart in foodParts">
                <div :class="'item-tab '+(part === foodPart.partId ? 'active':'')" @click="part = foodPart.partId">
                    <h3><img :src="foodPart.image" alt="">{{ foodPart.title }}</h3>
                </div>
            </div>
        </div>
    </div>
</template>
