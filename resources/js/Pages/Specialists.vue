<script setup>
import SpecialistCard from "@/Components/Specialists/SpecialistCard.vue"
import Modal from '@/Components/Basic/Modal.vue'
import {ref} from "vue"
import PageTitle from "@/Components/Layout/PageTitle.vue"

const props = defineProps({
    specialists: {
        type: Array,
        default: []
    }
})

const selectSpecialistId = ref(null)
</script>

<template>
    <PageTitle title="Специалисты"/>
    <div class="container tab-content pb-3 mt-4">
        <div class="row justify-content-center" v-if="specialists.length > 0">
            <div class="col-sm-6 col-md-6 col-lg-4 col-12" v-for="specialist in specialists">
                <SpecialistCard :specialist="specialist" v-model:specialist-id="selectSpecialistId"></SpecialistCard>
            </div>
        </div>
        <p v-else>На данный момент нет доступных специалистов</p>
    </div>

    <Modal v-if="selectSpecialistId !== null" id="specialistModal">
        <template #body>
            <SpecialistCard :specialist="specialists.find(item => item.id === selectSpecialistId)"
                            :in-modal="true" v-model:specialist-id="selectSpecialistId"></SpecialistCard>
        </template>
    </Modal>
</template>
