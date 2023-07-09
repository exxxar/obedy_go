<script setup>
import {storeToRefs} from "pinia"
import {useMainStore} from '@/stores/mainStore.js'
import PageTitle from "@/Components/Layout/PageTitle.vue"
import {onMounted} from "vue"
import {popover} from "@/app"

const { foodParts, part } = storeToRefs(useMainStore())

onMounted(() => {
    foodParts.value.forEach(el => {
        popover.getOrCreateInstance(document.querySelector("[name='btn-popover-" + el.partId + "']"))
    })
})
</script>

<template>
    <div class="row justify-content-center w-100 m-0">
        <template v-for="foodPart in foodParts">
            <div class="col-lg-4 col-md-6 col-sm-6 mt-2 text-center">
                <div class="position-relative">
                    <div class="go-item" @click="part = foodPart.partId">
                        <img :src="foodPart.image" class="img-fluid" alt="">
                        <h3>{{ foodPart.title }}</h3>

                    </div>
                    <button :name="'btn-popover-' + foodPart.partId" class="btn btn-link position-absolute btn-custom-question z-1"
                            data-bs-trigger="hover" data-bs-toggle="popover" data-bs-placement="bottom"
                            :data-bs-content="foodPart.tip">
                        <font-awesome-icon class="text-danger" icon="fa-solid fa-question" beat-fade/>
                    </button>
                </div>

            </div>
            <div v-if="foodPart.partId % 2 === 0 && foodPart.partId < foodParts.length" class="w-100"></div>
        </template>
    </div>
    <PageTitle/>
    <div class="w-100"></div><div class="w-100"></div>
</template>

<style scoped>
.btn-custom-question {
    top: 10px;
    right: 10px;
    background: white;
    border: 1px solid #e3342f;
    border-radius: 50%;
    padding: 0;
    margin: 0.25rem;
    font-size: 22px;
    font-weight: bold;
    width: 30px;
    height: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
