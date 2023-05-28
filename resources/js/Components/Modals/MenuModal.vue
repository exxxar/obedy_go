<script setup>
import Carousel from "@/Components/Basic/Carousel.vue"
import Modal from '@/Components/Basic/Modal.vue'
</script>

<template>
    <Modal id="menu" class_size="modal-lg" :header="false">
        <template #body>
            <Carousel
                id="carousel-fade"
                :items="[
                      '/images/full_1.jpg',
                      '/images/full_2.jpg',
                    ]">
            </Carousel>
        </template>
    </Modal>
</template>

