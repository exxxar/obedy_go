<script setup>
const props = defineProps({
    id: {
        type: String,
        default: 'modal'
    },
    title: {
        type: String,
        default: null
    },
    class_size: {
        type: String,
        default: null
    },
    header: {
        type: Boolean,
        default: true
    },
    footer: {
        type: Boolean,
        default: false
    },
    clearFunction: {
        type: Function,
        default: () => {}
    },
    class_content: {
        type: String,
        default: null
    },
})
</script>

<template>
    <div class="modal fade" :id="id" tabindex="-1" :aria-labelledby="id+'Label'" aria-hidden="true">
        <div class="modal-dialog" :class="class_size">
            <div class="modal-content" :class="class_content">
                <div v-if="header" class="modal-header">
                    <slot name="title"><h5 class="modal-title" :id="id+'Label'">{{title}}</h5></slot>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="clearFunction()"></button>
                </div>
                <slot name="header" v-if="!header"></slot>
                <div class="modal-body">
                    <slot name="body"></slot>
                </div>
                <div v-if="footer" class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Отменить</button>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Подтвердить</button>
                </div>
                <slot name="footer" v-if="!footer"></slot>
            </div>
        </div>
    </div>
</template>
