<script setup>
import Modal from '@/Components/Basic/Modal.vue'
import ImageInput from "@/Components/Basic/ImageInput.vue"
import TextInput from "@/Components/Basic/TextInput.vue"
import {ref, watch} from "vue";
import {modals} from "@/app";

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

const emit = defineEmits(['update:product', 'update:productErrors'])
const localProduct = ref(props.product)
const localProductErrors = ref(props.productErrors)
watch(localProduct, (newValue) => { emit('update:product', newValue) })
watch(() => props.product, (newValue) => { localProduct.value = newValue })
watch(localProductErrors, (newValue) => { emit('update:productErrors', newValue) })
watch(() => props.productErrors, (newValue) => { localProductErrors.value = newValue })

const textAreaAdjust = (element) => {
    element.style.height = "1px";
    element.style.height = (24 + element.scrollHeight) + "px";
}

const addInputPositions = () => {
    localProduct.value.positions.push({title: '', weight: ''})
}
const removeInputPositions = (id) => {
    localProduct.value.positions.splice(id, 1);
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
                <div class="input-group">
                    <TextInput placeholder="Цена" v-model="product.price"
                               :errors="localProductErrors.hasOwnProperty('products.'+selectProduct.id+'.price') ? localProductErrors['products.'+selectProduct.id+'.price'] : []"
                               :keyup-only-number="true"
                               :error="false"
                               input-class="w-auto m--0"></TextInput>
                    <span class="input-group-text justify-content-center w-40px">
                        <font-awesome-icon icon="fa-solid fa-ruble-sign" />
                    </span>
                    <div v-if="localProductErrors.hasOwnProperty('products.'+selectProduct.id+'.price') && localProductErrors['products.'+selectProduct.id+'.price'].length > 0"
                         class="invalid-feedback" v-for="error in localProductErrors['products.'+selectProduct.id+'.price']">{{ error }}
                    </div>
                </div>

                <div class="input-group">
                    <TextInput placeholder="Вес" v-model="product.weight"
                               :errors="localProductErrors.hasOwnProperty('products.'+selectProduct.id+'.weight') ? localProductErrors['products.'+selectProduct.id+'.weight'] : []"
                               :error="false"
                               :keyup-only-number="true"
                               input-class="w-auto m--0"></TextInput>
                    <div class="input-group-text justify-content-center gap-2">
                        <p>гр.</p>
                        <font-awesome-icon icon="fa-solid fa-scale-unbalanced-flip" />
                    </div>
                    <div v-if="localProductErrors.hasOwnProperty('products.'+selectProduct.id+'.weight') && localProductErrors['products.'+selectProduct.id+'.weight'].length > 0"
                         class="invalid-feedback" v-for="error in localProductErrors['products.'+selectProduct.id+'.weight']">{{ error }}
                    </div>
                </div>

                <ImageInput v-model:image="product.image"
                            :errors="localProductErrors.hasOwnProperty('products.'+selectProduct.id+'.image') ? localProductErrors['products.'+selectProduct.id+'.image'] : []"/>

                <div class="input-group">
                    <span class="input-group-text justify-content-center w-40px">
                        <font-awesome-icon icon="fa-solid fa-utensils" />
                    </span>
                    <textarea name="description" v-model="product.description" class="form-control px-4 py-3 overflow-hidden"
                              placeholder="Описание"
                              style="resize:none"
                              :class="[
                                  localProductErrors.hasOwnProperty('products.'+selectProduct.id+'.description') && localProductErrors['products.'+selectProduct.id+'.description'].length > 0 ? 'is-invalid' : '',
                                  (!localProductErrors.hasOwnProperty('products.'+selectProduct.id+'.description') || localProductErrors['products.'+selectProduct.id+'.description'].length === 0) && product.description !== null && product.description.length > 0 ? 'is-valid' : ''
                              ]"
                              @keyup="textAreaAdjust($event.target)">
                    </textarea>
                    <div v-if="localProductErrors.hasOwnProperty('products.'+selectProduct.id+'.description') && localProductErrors['products.'+selectProduct.id+'.description'].length > 0"
                         class="invalid-feedback" v-for="error in localProductErrors['products.'+selectProduct.id+'.description']">{{ error }}
                    </div>
                </div>

                <div class="w-100 d-flex flex-column gap-3">
                    <div class="d-flex gap-3 flex-column border rounded px-4 py-3" v-if="product.positions.length > 0" v-for="(prod, index) in product.positions">
                        <div class="input-group">
                            <div class="input-group-text justify-content-center gap-2">
                                <font-awesome-icon icon="fa-solid fa-heading" />
                            </div>
                            <TextInput placeholder="Название" v-model="prod.title"
                                       :errors="localProductErrors.hasOwnProperty('products.'+selectProduct.id+'.positions.'+index+'.title') ? localProductErrors['products.'+selectProduct.id+'.positions.'+index+'.title'] : []"
                                       input-class="w-auto m--0"></TextInput>
                        </div>
                        <div class="d-flex gap-3">
                            <div class="input-group">
                                <TextInput placeholder="Вес" v-model="prod.weight"
                                           :keyup-only-number="true"
                                           :errors="localProductErrors.hasOwnProperty('products.'+selectProduct.id+'.positions.'+index+'.weight') ? localProductErrors['products.'+selectProduct.id+'.positions.'+index+'.weight'] : []"
                                           :error="false"
                                           input-class="w-auto m--0"></TextInput>
                                <div class="input-group-text justify-content-center gap-2">
                                    <p>гр.</p>
                                    <font-awesome-icon icon="fa-solid fa-scale-unbalanced-flip" />
                                </div>
                                <div v-if="localProductErrors.hasOwnProperty('products.'+selectProduct.id+'.positions.'+index+'.weight') && localProductErrors['products.'+selectProduct.id+'.positions.'+index+'.weight'].length > 0"
                                     class="invalid-feedback" v-for="error in localProductErrors['products.'+selectProduct.id+'.positions.'+index+'.weight']">{{ error }}
                                </div>
                            </div>
                            <div class="d-flex gap-2"
                                :class="[
                                    localProductErrors.hasOwnProperty('products.'+selectProduct.id+'.positions.'+index+'.weight') && localProductErrors['products.'+selectProduct.id+'.positions.'+index+'.weight'].length > 0 ? 'align-items-start' : 'align-items-center'
                                ]">
                                <button v-if="product.positions.length - 1 === index" class="btn btn-success" type="button" @click="addInputPositions">
                                    <font-awesome-icon icon="fa-solid fa-plus"/>
                                </button>
                                <button class="btn btn-danger" type="button" @click="removeInputPositions(index)">
                                    <font-awesome-icon icon="fa-solid fa-trash"/>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-else class="col d-flex flex-column align-self-center">
                        <button class="btn btn-success"
                                :class="[
                                    localProductErrors.hasOwnProperty('products.'+selectProduct.id+'.positions') && localProductErrors['products.'+selectProduct.id+'.positions'].length > 0 ? 'is-invalid' : ''
                                ]"
                                type="button" @click="addInputPositions">
                            Добавить ингредиент
                        </button>
                        <div v-if="localProductErrors.hasOwnProperty('products.'+selectProduct.id+'.positions') && localProductErrors['products.'+selectProduct.id+'.positions'].length > 0"
                             class="invalid-feedback" v-for="error in localProductErrors['products.'+selectProduct.id+'.positions']">{{ error }}
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

<style scoped>
.w-40px {
    width: 40px;
}
</style>
