<script setup>
import SpecialistCard from "@/Components/Specialists/SpecialistCard.vue"
import Modal from '@/Components/Basic/Modal.vue'
import {ref} from "vue"


const props = defineProps({
    specialists: {
        type: Array,
        default: []
    }
})

const selectSpecialistId = ref(null)

</script>

<template>
    <div class="container tab-content pb-3">
        <div class="row justify-content-center" v-if="specialists.length > 0">
            <div class="col-sm-6 col-md-6 col-lg-4 col-12" v-for="specialist in specialists">
                <SpecialistCard :specialist="specialist" v-model="selectSpecialistId"></SpecialistCard>
            </div>
        </div>
        <p v-else>На данный момент нет доступных специалистов</p>
    </div>

    <Modal v-if="selectSpecialistId !== null" id="specialistModal">
        <template #body>
            <SpecialistCard :specialist="specialists.find((item) => {return (item.id === selectSpecialistId)})"
                            :in-modal="true" v-model="selectSpecialistId"></SpecialistCard>
        </template>
    </Modal>
</template>
