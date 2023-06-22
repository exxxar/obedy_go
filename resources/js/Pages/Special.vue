<script setup>
import { ref } from "vue"
import MenuCard from "@/Components/Menu/MenuCard.vue"
import Modal from '@/Components/Basic/Modal.vue'
import PageTitle from "@/Components/Layout/PageTitle.vue"

const selectSpecialId = ref(null)

</script>

<template>
    <PageTitle title="Рецепты"/>
    <div class="container tab-content pb-3">
        <div class="row justify-content-center">
            <div class="col-sm-6 col-12">
                <MenuCard v-model="selectSpecialId"></MenuCard>
            </div>
        </div>
    </div>

    <Modal v-if="selectSpecialId !== null" id="specialModal">
        <template #body>
            <MenuCard :in-modal="true" v-model="selectSpecialId"></MenuCard>
        </template>
    </Modal>
</template>

