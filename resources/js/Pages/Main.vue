<script setup>
import {storeToRefs} from "pinia"
import {useMainStore} from '@/stores/mainStore.js'
import {onMounted} from "vue"

const {foodParts, part} = storeToRefs(useMainStore())
onMounted(() => {
    part.value = 0
})

</script>

<template>
    <div class="row justify-content-center w-100 m-0">
        <template v-for="foodPart in foodParts">
            <div class="col-lg-4 col-md-6 col-sm-6 mt-2 text-center">
                <div class="go-item" @click="part = foodPart.partId">
                    <img :src="foodPart.image" class="img-fluid" alt="">
                    <h3>{{ foodPart.title }}</h3>
                </div>
            </div>
            <div v-if="foodPart.partId % 2 === 0 && foodPart.partId < foodParts.length" class="w-100"></div>
        </template>
    </div>
</template>
