<script setup>
import {storeToRefs} from "pinia"
import {useCartStore} from '@/stores/cartStore.js'
import Modal from "@/Components/Basic/Modal.vue"
import {sendNotify} from "@/app"
import {useMainStore} from "@/stores/mainStore.js"

const cart = useCartStore()
const { items } = storeToRefs(cart)
const { foodParts } = storeToRefs(useMainStore())

const print = async () => {
    await axios.get(route('print.report'), {responseType: 'blob'}).then(response => {
        let fileURL = window.URL.createObjectURL(new Blob([response.data]));
        let fURL = document.createElement('a');

        fURL.href = fileURL;
        fURL.setAttribute('download', 'report.pdf');
        document.body.appendChild(fURL);

        fURL.click();
    }).catch(async error => {
        if (error.response.status === 403)
            sendNotify('Для печати отчета необходима авторизация!', 'error')
    })
}
const foodPart = (product) => {
    return foodParts.value.find(foodPart => foodPart.partId === product.product.partId)
}

</script>

<template>
    <Modal id="changeUserCart" :header="false" :footer="false" class_size="modal-lg">
        <template #header>
            <div class="modal-header">
                <slot name="title"><h5 class="modal-title">Отчет по пользователям</h5></slot>
                <button class="btn btn-secondary btn-link" @click="print()">Распечатать</button>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
        </template>
        <template #body>
            <template v-for="product in items" v-if="items.length > 0">
                <p>
                    <strong v-if="foodPart(product)">{{foodPart(product).title}}. </strong>
                    {{product.product.title}}
                </p>
                <div class="table-responsive">
                    <table class="table table-hover ">
                        <thead>
                        <tr>
                            <th scope="col">Имя пользователя</th>
                            <th scope="col">Телефон пользователя</th>
                            <th scope="col">Количество порций</th>
                            <th scope="col">Дата добавления</th>
                            <th scope="col">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(user, index) in product.users">
                                <td>{{ user.name }}</td>
                                <td>{{ user.phone }}</td>
                                <td>{{ user.quantity }}</td>
                                <td>{{ user.date }}</td>
                                <td>
                                    <div class="d-flex gap-3">
                                        <div class="group-btn-counter gap-1">
                                            <button class="btn btn-danger btn-coutner"
                                                    @click="cart.decQuantity(product.product.id, index)">
                                                -
                                            </button>
                                            <button class="btn btn-danger btn-coutner"
                                                    @click="cart.incQuantity(product.product.id, index)">
                                                <span>+</span>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </template>
        </template>
    </Modal>
</template>
