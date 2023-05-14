import { defineStore } from 'pinia'
import {reactive, ref} from "vue";
import { router } from '@inertiajs/vue3'

const foodParts = reactive([
    {partId: 1, title: 'Стандарт', image: '/images/logo_obed_go.jpg', slug: 'standard'},
    {partId: 2, title: 'Экспресс', image: '/images/logo_obed_go.jpg', slug: 'express'},
    {partId: 3, title: 'Премиум', image: '/images/logo_obed_go.jpg', slug: 'premium'},
    {partId: 4, title: 'Собери сам', image: '/images/logo_obed_go.jpg', slug: 'self'}
])

const part = ref(0)

function changePosition(isNext = true) {
    if (this.part === 0) {
        this.part = 1
        return
    }
    if (isNext) {
        if (this.part === 4)
            this.part = 1
        else
            this.part++;
    } else {
        if (this.part === 1)
            this.part = 4
        else
            this.part--;
    }
}

function selectPart() {
    if(this.part === 0) {
        router.get(route('main'))
        return
    }
    let slug = this.foodParts.find(foodPart => foodPart.partId ===  this.part).slug ?? ''
    router.get(route('products', slug))
}

export const useMainStore = defineStore('main', () => {

    return { foodParts, part, changePosition, selectPart }
})
