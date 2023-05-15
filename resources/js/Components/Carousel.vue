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
    <div :id="id" class="carousel slide carousel-fade" data-ride="carousel">
        <ol class="carousel-indicators" v-if="hasIndicators">
            <li :data-target="'#'+id" :data-slide-to="index" :class="index === 0 ? 'active' : ''" v-for="(item, index) in items"></li>
        </ol>
        <div class="carousel-inner">
            <div class="carousel-item" :class="index === 0 ? 'active' : ''" v-for="(item, index) in items">
                <img class="d-block w-100" :src="item" alt="">
            </div>
        </div>
        <template v-if="hasControls">
            <a class="carousel-control-prev" :href="'#'+id" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" :href="'#'+id" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </template>
    </div>
</template>
