<script setup>
import Modal from '@/Components/Basic/Modal.vue'

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
                    <div id="carouselExampleIndicators" class="carousel slide h-100">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        </div>
                        <div class="carousel-inner h-100">
                            <div class="carousel-item h-100 active">
                                <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png" class="d-block w-100 h-100 object-fit-contain" alt="...">
                            </div>
                            <div class="carousel-item h-100">
                                <img src="https://wallpaper-house.com/data/out/8/wallpaper2you_232438.jpg" class="d-block w-100 h-100 object-fit-contain" alt="...">
                            </div>
                            <div class="carousel-item h-100">
                                <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png" class="d-block w-100 h-100 object-fit-contain" alt="...">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                            <i class="fa-solid fa-chevron-left cursor-pointer fa-2xl" aria-hidden="true"></i>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
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
