<script setup>
import BaseLayout from "@/Layouts/BaseLayout.vue";
import {useMainStore} from '@/stores/mainStore.js'
import {storeToRefs} from "pinia";
//get store
const main = useMainStore()
const {foodParts, part} = storeToRefs(main)
</script>

<template>
    <BaseLayout>
        <template #content>
            <div class="row d-flex justify-content-center flex-wrap w-100 m-0">
                <template v-for="foodPart in foodParts">
                    <div class="col-lg-4 col-md-6 col-sm-6 d-flex justify-content-center mt-2">
                        <div class="go-item" @click="part = foodPart.partId">
                            <img :src="foodPart.image" class="img-fluid" alt="">
                            <h3 class="text-center w-100">{{ foodPart.title }}</h3>
                        </div>
                    </div>
                    <div v-if="foodPart.partId % 2 === 0 && foodPart.partId < foodParts.length" class="w-100"></div>
                </template>
            </div>
        </template>
    </BaseLayout>
</template>
