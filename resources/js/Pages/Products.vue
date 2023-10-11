<script setup>
import {computed, provide, ref} from "vue"
import Search from "@/Components/Basic/Search.vue"
import ProductCard from "@/Components/Products/ProductCard.vue"
import PageTitle from "@/Components/Layout/PageTitle.vue"

const props = defineProps({
    products: {
        type: Array,
        default: []
    },
    title: {
        type: String,
        default: null
    },
    categories: {
        type: Array,
        default: []
    }
})

const filteredText = ref('')
const current_category_id = ref(1)
const filteredCategory = ref('name')

const filteredProducts = computed(() => {
    if(filteredText.value === '') {
        return props.products
    }else{
        if(filteredCategory.value === 'name'){
            return props.products.filter(item => item
                    .title
                    .toLowerCase()
                    .indexOf(filteredText.value.toLowerCase()) !== -1 ||
                (item.description ? item
                    .description
                    .toLowerCase()
                    .indexOf(filteredText.value.toLowerCase()) !== -1 : false))
        }else{
            return props.products.filter(item =>
                    item.positions
                    .map(position => position.title).join(', ')
                    .toLowerCase()
                    .indexOf(filteredText.value.toLowerCase()) !== -1)
        }
    }
})

const productByCategory = computed(() => {
    return props.categories.find(item => item.id === current_category_id.value).products
})
</script>

<template>
    <PageTitle :title="title"/>
    <div class="container tab-content pb-3">
        <Search v-model:filtered-text="filteredText" v-model:filtered-category="filteredCategory"></Search>
        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-4 col-12" v-for="product in filteredProducts">
                <ProductCard :product="product" :week="product.is_week"></ProductCard>
            </div>
        </div>
        <template v-if="title === null">
            <h3 class="mt-4 mb-2 text-uppercase text-white">А также можно добавить к заказу....</h3>
            <div class="row ingrs pb-2 mt-2">
                <div class="col-12 d-flex flex-wrap justify-content-start">
                    <p :class="(current_category_id===item.id?'active':'')" v-for="item in categories"
                       @click="current_category_id===item.id">#{{ item.title }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-md-6 col-lg-4 col-12" v-for="product in productByCategory">
                    <ProductCard :product="product"></ProductCard>
                </div>
            </div>
        </template>
    </div>
</template>
