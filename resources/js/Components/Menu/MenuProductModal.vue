<script setup>
import { modals, myHasOwnProperty } from "@/app"
import Modal from '@/Components/Basic/Modal.vue'
import ImageInput from "@/Components/Basic/ImageInput.vue"
import TextInput from "@/Components/Basic/TextInput.vue"
import TextTextarea from "@/Components/Basic/TextTextarea.vue"

const props = defineProps({
    product: {
        type: Object,
        default: {}
    },
    productErrors: {
        type: Object,
        default: {}
    },
    selectProduct: {
        type: Object,
        default: {}
    }
})

const emit = defineEmits(['update:product'])

const addOrRemoveInputPositions = (id = null) => {
    const { product } = props
    if (id !== null)
        product.positions.push({ title: '', weight: '' })
    else
        product.positions.splice(id, 1)
    emit('update:product', product)
}

const closeMenuProductModal = () => {
    modals.getOrCreateInstance(document.getElementById('menuProductModal')).hide()
}
</script>

<template>
    <Modal id="menuProductModal" class_size="modal-lg">
        <template #title>
            <h3 class="d-flex align-items-center">
                {{ selectProduct.isEdit ? 'Редактировать ' : 'Создать ' }} товар
                <span class="badge bg-danger ms-2">{{ product.title }}</span>
            </h3>
        </template>
        <template #body>
            <div class="w-100 d-flex flex-column gap-3">
                <TextInput :errors="myHasOwnProperty.call(productErrors,'products.'+selectProduct.id+'.price') ?
                           productErrors['products.'+selectProduct.id+'.price'] : []"
                           placeholder="Цена"
                           v-model="product.price"
                           groupTextIconRight="fa-solid fa-ruble-sign"
                           :keyup-only-number="true"/>
                <TextInput :errors="myHasOwnProperty.call(productErrors,'products.'+selectProduct.id+'.weight') ?
                           productErrors['products.'+selectProduct.id+'.weight'] : []"
                           placeholder="Вес"
                           v-model="product.weight"
                           :keyup-only-number="true">
                    <template #groupTextIconRight>
                        <span class="input-group-text justify-content-center w-auto">
                            гр.
                            <font-awesome-icon class="ms-1" icon="fa-solid fa-scale-unbalanced-flip"/>
                        </span>
                    </template>
                </TextInput>
                <ImageInput :errors="myHasOwnProperty.call(productErrors,'products.'+selectProduct.id+'.image')
                            ? productErrors['products.'+selectProduct.id+'.image'] : []"
                            v-model:image="product.image"/>
                <TextTextarea :errors="myHasOwnProperty.call(productErrors, 'description') ? errors.description : []"
                              placeholder="Описание"
                              v-model="product.description"
                              groupTextIconLeft="fa-solid fa-utensils"/>
                <div class="w-100 d-flex flex-column gap-3">
                    <div class="d-flex gap-3 flex-column border rounded px-4 py-3" v-if="product.positions.length > 0"
                         v-for="(prod, index) in product.positions">
                        <TextInput :errors="myHasOwnProperty.call(productErrors, 'products.'+selectProduct.id+'.positions.'+index+'.title') ?
                                       productErrors['products.'+selectProduct.id+'.positions.'+index+'.title'] : []"
                                   placeholder="Название"
                                   v-model="prod.title"
                                   groupTextIconLeft="fa-solid fa-heading"/>
                        <div class="d-flex gap-3">
                            <TextInput :errors="productErrors.hasOwnProperty('products.'+selectProduct.id+'.positions.'+index+'.weight') ?
                                        productErrors['products.'+selectProduct.id+'.positions.'+index+'.weight'] : []"
                                       placeholder="Вес"
                                       v-model="prod.weight"
                                       :keyup-only-number="true">
                                <template #groupTextIconRight>
                                    <span class="input-group-text justify-content-center w-auto">
                                        гр.
                                        <font-awesome-icon class="ms-1" icon="fa-solid fa-scale-unbalanced-flip"/>
                                    </span>
                                </template>
                            </TextInput>
                            <div class="d-flex gap-2"
                                 :class="[
                                    productErrors.hasOwnProperty('products.'+selectProduct.id+'.positions.'+index+'.weight')
                                    && productErrors['products.'+selectProduct.id+'.positions.'+index+'.weight'].length > 0
                                    ? 'align-items-start' : 'align-items-center'
                                ]">
                                <button v-if="product.positions.length - 1 === index" class="btn btn-success"
                                        type="button" @click="addOrRemoveInputPositions">
                                    <font-awesome-icon icon="fa-solid fa-plus"/>
                                </button>
                                <button class="btn btn-danger" type="button" @click="addOrRemoveInputPositions(index)">
                                    <font-awesome-icon icon="fa-solid fa-trash"/>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-else class="col d-flex flex-column align-self-center">
                        <button class="btn btn-success"
                                :class="[
                                    productErrors.hasOwnProperty('products.'+selectProduct.id+'.positions')
                                        && productErrors['products.'+selectProduct.id+'.positions'].length > 0
                                        ? 'is-invalid' : ''
                                ]"
                                type="button" @click="addOrRemoveInputPositions">
                            Добавить ингредиент
                        </button>
                        <div
                            v-if="productErrors.hasOwnProperty('products.'+selectProduct.id+'.positions')
                                  && productErrors['products.'+selectProduct.id+'.positions'].length > 0"
                            class="invalid-feedback"
                            v-for="error in productErrors['products.'+selectProduct.id+'.positions']">{{ error }}
                        </div>
                    </div>
                    <div class="col d-flex align-self-center">
                        <button class="btn btn-success" type="button" @click="closeMenuProductModal()">
                            Продолжить
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </Modal>
</template>
