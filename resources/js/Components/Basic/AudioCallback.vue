<script setup>
import VueRecordAudio from "@/Components/Basic/vue-record/VueRecordAudio.vue"
import {ref, watch} from "vue"

const props = defineProps({
    modelValue: {
        type: Array,
        default: [],
        required: true
    }
})

const emit = defineEmits(['update:modelValue'])

const records = ref([])

watch(records.value, (val) => {
    emit('update:modelValue', val)
});

watch(() => props.modelValue, (newValue, oldValue) => {
    if (newValue.length === 0 && records.value.length !== 0) {
        records.value = []
        emit('update:modelValue', records.value)
    }
});

const onResult = (data) => {
    records.value.push({
        src: window.URL.createObjectURL(data),
        data: data
    })
}

const removeRecord = (index) => {
    records.value.splice(index, 1)
}

</script>

<template>
    <div>
        <h6 class="text-center">Запиши голосовое сообщение<br><strong class="text-danger">Удерживай кнопку для записи!</strong></h6>
        <div class="d-flex justify-content-center mt-2">
            <vue-record-audio :mode="'hold'" @result="onResult"></vue-record-audio>
        </div>

        <div class="row d-flex justify-content-center mt-2" v-if="modelValue.length > 0">
            <div class="col-12">
                <div class="recorded-audio">
                    <div v-for="(record, index) in modelValue" :key="index" class="recorded-item">
                        <div class="audio-container">
                            <audio :src="record.src" controls/>
                        </div>
                        <div>
                            <a @click="removeRecord(index)" class="button is-dark"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</template>

