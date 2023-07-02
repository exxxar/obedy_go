<script setup>
import Modal from '@/Components/Basic/Modal.vue'
import TextInput from "@/Components/Basic/TextInput.vue"
import { ref } from "vue"
import { myHasOwnProperty } from "@/app"

const errors = ref([])
const number = ref(null)
const status = ref(null)

const checkOrder = async () =>{
    await axios.post(route('check.order'), { number: number.value }).then(response => {
        errors.value = []
        status.value = response.data.status
        number.value = null
    }).catch(error => {
        if (error.response.status === 422) {
            errors.value = error.response.data.errors
            status.value = null
        }
    })
}

</script>

<template>
    <Modal id="checkOrder" title="Узнайте статус заказа по номеру" :footer="false">
        <template #body>
            <p v-if="status === 0" class="text-success mt-2">Ваш заказ принят</p>
            <p v-if="status === 1" class="text-danger mt-2">Ваш заказ отклонен</p>
            <TextInput :errors="myHasOwnProperty.call(errors, 'number') ? errors.number : []"
                       placeholder="Введите номер заказа"
                       v-model="number"/>
        </template>
        <template #footer>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-success" @click="checkOrder">Подтвердить</button>
            </div>
        </template>
    </Modal>
</template>
