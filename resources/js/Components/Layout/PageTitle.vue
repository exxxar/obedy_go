<script setup>
/* ------------------------ IMPORT ----------------------------- */
import { storeToRefs } from "pinia"
import { useMainStore } from "@/stores/mainStore"
/* ------------------------------------------------------------ */

/* ------------------------ STORE ----------------------------- */
const { foodParts, part } = storeToRefs(useMainStore())
/* ------------------------------------------------------------ */

/* ------------------------ PROPS ----------------------------- */
const props = defineProps({
    title: {
        type: String,
        default: null
    }
})
/* ------------------------------------------------------------ */

</script>

<template>
    <div class="w-100 d-flex flex-column align-items-center text-uppercase text-white text-center mt-4"
         :class="part === 0 ? 'mb-5 mt-5' : ''">
        <h6 class="col-8 px-3" v-if="part !== null">Полное меню на неделю можно глянуть
            <a href="#" class="text-danger font-weight-bold"
               data-bs-toggle="modal" data-bs-target="#menu">ТУТ</a>
        </h6>
        <h1 class="mt-2" v-if="part !== null && part !== 0">
            {{ foodParts.find(foodPart => foodPart.partId === part).title }}
        </h1>
        <h1 class="mt-2" v-if="part === null && title !== null">{{ title }}</h1>
    </div>
</template>
