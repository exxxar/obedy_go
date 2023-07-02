<script setup>
import VueRecordAudio from "@/Components/Basic/vue-record/VueRecordAudio.vue"
import {reactive} from "vue";

const props = defineProps({
    recordings: {
        type: Array,
        default: [],
        required: true
    }
})

const emit = defineEmits(['update:recordings'])

const onResult = (data) => {
    const { recordings } = props
    recordings.push({src: window.URL.createObjectURL(data), data: data})
    emit('update:recordings', recordings)
}

const removeRecord = (index) => {
    const { recordings } = props
    recordings.splice(index, 1)
    emit('update:recordings', recordings)
}

</script>

<template>
    <div class="d-flex flex-column align-items-center gap-2">
        <h6>Запиши голосовое сообщение<br><strong class="text-danger">Удерживай кнопку для записи!</strong></h6>
        <vue-record-audio mode="hold" @result="onResult"></vue-record-audio>
        <div class="recorded-audio w-100 d-flex flex-column gap-3 rounded-2 px-4 py-3" v-if="recordings.length > 0">
            <div v-for="(record, index) in recordings" :key="index"
                 class="recorded-item d-flex align-items-center justify-content-between gap-3">
                <audio :src="record.src" controls/>
                <a @click="removeRecord(index)" class="d-flex cursor-pointer">
                    <font-awesome-icon icon="fa-solid fa-trash"/>
                </a>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.recorded-audio {
    max-height: calc(163px + 4rem);
    overflow: auto;
    border: thin solid #eee;
    background-color: #fffcfc;
    box-shadow: 1px 1px 1px 0 inset;
    & .recorded-item {
        height: 54px
    }
}
</style>
