<script setup>
const props = defineProps({
    id: {
        type: String,
        default: 'carousel'
    },
    hasIndicators: {
        type: Boolean,
        default: true
    },
    hasControls: {
        type: Boolean,
        default: true
    },
    items: {
        type: Array,
        default: []
    }
})
</script>

<template>
    <div :id="id" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-indicators" v-if="hasIndicators">
            <button type="button" :data-bs-target="'#'+id" :data-bs-slide-to="index"
                    :class="index === 0 ? 'active' : ''" v-for="(item, index) in items"
                    :aria-current="index === 0 ? 'active' : ''" :aria-label="item"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item" :class="index === 0 ? 'active' : ''" v-for="(item, index) in items">
                <img class="d-block w-100" :src="item" alt="">
            </div>
        </div>
        <template v-if="hasControls">
            <button class="carousel-control-prev" type="button" :data-bs-target="'#'+id" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" :data-bs-target="'#'+id" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </template>
    </div>
</template>
