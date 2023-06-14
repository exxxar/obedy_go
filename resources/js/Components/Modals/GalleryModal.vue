<script setup>
import Modal from '@/Components/Basic/Modal.vue'

const props = defineProps({
    images: {
        type: Array,
        default: []
    },
    active: {
        type: Number,
        default: 0
    }
})

</script>

<template>
    <Modal id="gallery" class_size="modal-dialog-centered modal-fullscreen" class_content="bg-transparent" :header="false" title="">
        <template #header>
            <div class="modal-header border-0">
                <h5 class="modal-title"></h5>
                <i class="fa-solid fa-xmark text-white px-3 cursor-pointer fa-2xl"  data-bs-dismiss="modal"></i>
            </div>
        </template>
        <template #body>
                    <div id="galleryCarouselIndicators" class="carousel slide h-100">
                        <div class="carousel-indicators">
                            <button v-for="(image, index) in images"
                                    type="button"
                                    data-bs-target="#galleryCarouselIndicators"
                                    :data-bs-slide-to="index"
                                    :class="index === active ? 'active' : ''"
                                    aria-current="true"
                                    :aria-label="'Slide ' + index"></button>
                        </div>
                        <div class="carousel-inner h-100">
                            <div v-for="(image, index) in images"
                                 class="carousel-item h-100"  :class="index === active ? 'active' : ''">
                                <img :src="image" class="d-block w-100 h-100 object-fit-contain" alt="...">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#galleryCarouselIndicators" data-bs-slide="prev">
                            <i class="fa-solid fa-chevron-left cursor-pointer fa-2xl" aria-hidden="true"></i>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#galleryCarouselIndicators" data-bs-slide="next">
                            <i class="fa-solid fa-chevron-right cursor-pointer fa-2xl" aria-hidden="true"></i>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
        </template>
    </Modal>
</template>

<style scoped>
.cursor-pointer {
    cursor: pointer;
}
</style>
