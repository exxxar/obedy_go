<script setup>
import { sendNotify } from "@/app"
import { computed } from "vue"

const props = defineProps({
    image: {
        default: null
    },
    error: {
        type: Boolean,
        default: true
    },
    errors: {
        type: Array,
        default: []
    }
})

const emit = defineEmits(['update:image'])

const openProfileImage = (event) => {
    let el = event.target
    if (el.localName === 'div')
        el.nextElementSibling.firstChild.click()
    else if(el.localName === 'svg' || el.localName === 'path' || el.localName === 'span')
        el.closest('div').nextElementSibling.firstChild.click()
}

const onFileChange = (event) => {
    let files = event.target.files
    if (!files.length)
        return
    if (files[0] && files[0].type.includes('image')) {
        event.target.nextElementSibling.firstChild.src = URL.createObjectURL(files[0])
        emit('update:image', files[0])
    } else {
        sendNotify('Произошла ошибка! Файл не является изображением!', 'error')
        event.target.value = null
    }
}

const deleteImage = (event) => {
    emit('update:image', null)
    event.target.closest('div').previousElementSibling.firstChild.src = null
    event.target.closest('div').previousElementSibling.previousElementSibling.value = null
}

const imageSrc = computed(() => {
    return typeof props.image === 'string' ? props.image : URL.createObjectURL(props.image)
})
</script>

<template>
    <div class="input-group">
        <div class="col-sm-auto col-12 input-group-text flex-wrap justify-content-center gap-2 cursor-pointer px-4 py-3"
             @click="openProfileImage">
            <font-awesome-icon icon="fa-solid fa-image"/>
            <span>Выбрать изображение</span>
        </div>
        <div class="form-control col-sm col-12 px-4 py-3 d-flex flex-wrap align-items-center text-center gap-3"
             :class="[
               errors.length > 0 ? 'is-invalid' : '',
               errors.length === 0 && image !== '' && image !== null ? 'is-valid' : '',
           ]">
            <input class="d-none" accept="image/*" type="file" @change="onFileChange"/>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <img class="img-fluid" alt="" :src="imageSrc" v-if="image !== null && image !== ''">
            </div>
            <div class="col-sm-auto col-12">
                <button class="col-auto btn btn-danger" @click="deleteImage" v-if="image !== null && image !== ''">
                    <font-awesome-icon icon="fa-solid fa-trash"/>
                </button>
            </div>
        </div>
        <div v-if="errors.length > 0 && error" class="invalid-feedback" v-for="err in errors">{{ err }}</div>
    </div>
</template>
