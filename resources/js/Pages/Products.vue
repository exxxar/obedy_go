<script setup>
import BaseLayout from "@/Layouts/BaseLayout.vue"
import Search from "@/Components/Search.vue";
import ProductCard from "@/Components/ProductCard.vue";
import {computed, provide, reactive, ref} from "vue";

const props = defineProps({
    products: {
        type: Array,
        default: []
    },
    categories: {
        type: Array,
        default: []
    },
})

const filteredText = ref(null)
const current_category_id = ref(1)
const current_day = ref(2)
const settings = reactive({
        suppressScrollX: true,
        suppressScrollY: false,
})


provide('filteredText', filteredText)


const filteredProducts = computed(() => {
    return filteredText.value == null ? props.products :
        props.products.filter(item => item
                .title
                .toLowerCase()
                .indexOf(filteredText.value.toLowerCase()) !== -1 ||
            (item.description ? item
                .description
                .toLowerCase()
                .indexOf(filteredText.value.toLowerCase()) !== -1 : false)
        )
})

const productByCategory = computed(() => {
    return props.categories.find(item => item.id === current_category_id.value).products
})


</script>

<template>
    <BaseLayout>
        <template #content>
            <div class="row w-100 m-0">
                <div class="tab-content container">
                    <Search></Search>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-4 col-12" v-for="product in filteredProducts">
                            <ProductCard :product="product"></ProductCard>
                        </div>
                    </div>
                    <h3 class="mt-4 mb-2 text-uppercase text-white">А также можно добавить к заказу....</h3>
                    <div class="row mt-2 ingrs pb-2">
                        <div class="col-sm-12 d-flex justify-content-start">
                            <p :class="(current_category_id===item.id?'active':'')" v-for="item in categories"
                               @click="current_category_id=item.id">
                                #{{item.title}}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-6 col-lg-4 col-12" v-for="product in productByCategory">
                            <ProductCard :product="product"></ProductCard>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </BaseLayout>
</template>
