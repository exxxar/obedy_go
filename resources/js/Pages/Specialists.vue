<script setup>
import BaseLayout from "@/Layouts/BaseLayout.vue"
import SpecialistCard from "@/Components/Specialists/SpecialistCard.vue"
import Modal from '@/Components/Basic/Modal.vue'


const props = defineProps({
    specialists: {
        type: Array,
        default: []
    }
})
</script>

<template>
    <BaseLayout>
        <template #content>
            <div class="container tab-content pb-3">
                <div class="row justify-content-center" v-if="specialists.length > 0">
                    <div class="col-sm-6 col-md-6 col-lg-4 col-12" v-for="specialist in specialists">
                        <SpecialistCard :specialist="specialist"></SpecialistCard>
                        <Modal :id="'specialist-'+specialist.id">
                            <template #body>
                                <SpecialistCard :specialist="specialist" :in-modal="true"></SpecialistCard>
                            </template>
                        </Modal>
                    </div>
                </div>
                <p v-else>На данный момент нет доступных специалистов</p>
            </div>
        </template>
    </BaseLayout>
</template>
