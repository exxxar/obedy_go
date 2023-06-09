<script setup>
import { nextTick, onMounted, reactive, ref } from "vue"
import TextInput from "@/Components/Basic/TextInput.vue"
import PageTitle from "@/Components/Layout/PageTitle.vue"
import ProductInfo from "@/Components/Products/ProductInfo.vue"
import ImageInput from "@/Components/Basic/ImageInput.vue"
import { modals, sendNotify, myHasOwnProperty } from "@/app"
import MenuProductModal from "@/Components/Menu/MenuProductModal.vue"
import TextTextarea from "@/Components/Basic/TextTextarea.vue"

const props = defineProps({
    menu: {
        type: Object,
        default: {}
    }
})

const baseForm = () => ({
    title: '',
    image: '',
    description: '',
    price: '',
    products: []
})

const baseProduct = () => ({
    image: '',
    title: '',
    description: '',
    positions: [],
    price: '',
    weight: ''
})

const form = reactive(baseForm())
const errors = ref([])
const selectProduct = reactive({id: null, isEdit: null})
const days = ['Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница']


onMounted(() => {
    addProducts()
})

const addProducts = () => {
    days.forEach((element, index) => {
        if (route().current('menu.create')) {
            form.products.push(baseProduct())
            form.products[index].title = element
        } else if (route().current('menu.edit')) {
            let productByDay = props.menu.products.findIndex(product => product.day_index === index + 1)
            if (productByDay === -1) {
                form.products.push(baseProduct())
                form.products[index].title = element
            } else
                form.products.push(props.menu.products[productByDay])
        }
    })
    if (route().current('menu.edit')) {
        form.id = props.menu.id
        form.title = props.menu.title
        form.image = props.menu.image
        form.description = props.menu.description
        form.price = props.menu.price
    }
}

const openMenuProductModal = async (isEdit, id) => {
    selectProduct.id = id
    selectProduct.isEdit = isEdit
    await nextTick(() => {
        let menuProductModal = document.getElementById('menuProductModal');
        modals.getOrCreateInstance(menuProductModal).show()
        menuProductModal.addEventListener('hidden.bs.modal', (event) => {
            selectProduct.id = null
            selectProduct.isEdit = null
        }, {once: true})
    })
}

const saveOrUpdateMenu = () => {
    axios.post(route('menu.store'), createFormData(form), {
        headers: {'Content-Type': 'multipart/form-data'}
    }).then(response => {
        sendNotify('Ваше меню было успешно сохранено!')
        errors.value = []
        if (route().current('menu.create')) {
            Object.assign(form, baseForm())
            addProducts()
        }
    }).catch(error => {
        if (error.response.status === 422) {
            errors.value = error.response.data.errors
        }
    })
}

const createFormData = (obj, formData = new FormData(), subKeyStr = '') => {
    for (let i in obj) {
        let value = obj[i]
        let subKeyStrTrans = subKeyStr ? subKeyStr + '[' + i + ']' : i
        if ((typeof (value) === 'string' || typeof (value) === 'number') && i !== 'image')
            formData.append(subKeyStrTrans, value)
        else if (i === 'image' && (value instanceof File || value === null || value.length === 0))
            formData.append(subKeyStrTrans, value)
        else if (typeof (value) === 'object')
            createFormData(value, formData, subKeyStrTrans)
    }
    return formData
}

</script>

<template>
    <PageTitle :title="route().current('menu.edit') ? 'Редактирование меню': 'Создание меню'"/>
    <div class="container tab-content z-10">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-body d-flex flex-column gap-3 align-items-center">
                        <div class="w-100 d-flex flex-column gap-3">
                            <TextInput :errors="myHasOwnProperty.call(errors, 'title') ? errors.title : []"
                                       placeholder="Название"
                                       v-model="form.title"
                                       groupTextIconLeft="fa-solid fa-heading"/>

                            <TextTextarea  :errors="myHasOwnProperty.call(errors, 'description') ? errors.description : []"
                                           placeholder="Описание"
                                           v-model="form.description"
                                           textareaName="description"
                                           groupTextIconLeft="fa-solid fa-utensils"/>

                            <TextInput :errors="myHasOwnProperty.call(errors, 'price') ? errors.price : []"
                                       placeholder="Цена"
                                       v-model="form.price"
                                       :keyup-only-number="true"
                                       groupTextIconRight="fa-solid fa-ruble-sign"/>

                            <ImageInput :errors="myHasOwnProperty.call(errors, 'image') ? errors.image : []"
                                        v-model:image="form.image"/>

                            <div class="w-100 list-group">
                                <div class="list-group-item px-2 py-2" v-for="(day, index) in days">
                                    <div class="d-flex flex-column px-3 py-2 gap-3"
                                         :class="[
                                         Object.keys(errors).filter(element => element.toLowerCase().indexOf(('products.'+index).toLowerCase()) !== -1).length > 0 ? 'border rounded div-invalid' : '',
                                         Object.keys(errors).length > 0 && Object.keys(errors).filter(element => element.toLowerCase().indexOf(('products.'+index).toLowerCase()) !== -1).length === 0 ? 'border rounded div-valid' : ''
                                     ]">
                                        <div class="w-100 d-flex align-items-center justify-content-between">
                                            <h5 class="m-0" v-if="form.products[index]">{{ form.products[index].title }}</h5>
                                            <button class="btn btn-success" type="button"
                                                    v-if="form.products[index] &&
                                                          !myHasOwnProperty.call(form.products[index], 'day_index')"
                                                    @click="openMenuProductModal(false, index)">
                                                <font-awesome-icon icon="fa-solid fa-plus"/>
                                            </button>
                                            <button class="btn btn-warning" type="button"
                                                    v-else
                                                    @click="openMenuProductModal(true, index)">
                                                <font-awesome-icon icon="fa-solid fa-pencil"/>
                                            </button>
                                        </div>
                                        <div class="d-flex gap-3">
                                            <ProductInfo :product="form.products[index]" :add-menu="true"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button class="btn btn-success" type="button"
                                    @click="saveOrUpdateMenu()">
                                {{ route().current('menu.edit') ? 'Обновить меню' : 'Сохранить меню' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <MenuProductModal v-if="selectProduct.id !== null"
                      :select-product="selectProduct"
                      :product-errors="Object.fromEntries(Object.entries(errors).filter(([key]) => key.startsWith('products.'+selectProduct.id)))"
                      :product="form.products[selectProduct.id]"/>
</template>

<style scoped>
.div-invalid {
    border-color: var(--bs-form-invalid-border-color) !important
}

.div-valid {
    border-color: var(--bs-form-valid-border-color) !important
}

.list-group-item.div-invalid + .list-group-item.div-invalid {
    border-top-width: 0
}

.list-group-item + .list-group-item.div-invalid {
    border-top-width: 1px
}
</style>
