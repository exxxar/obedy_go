import {defineStore, storeToRefs} from 'pinia'
import {reactive, ref} from "vue";
import { router } from '@inertiajs/vue3'


export const useMainStore = defineStore('main', () => {

    const foodParts = reactive([
        {partId: 1, title: 'Стандарт', image: '/images/logo_obed_go.jpg', slug: 'standard'},
        {partId: 2, title: 'Экспресс', image: '/images/logo_obed_go.jpg', slug: 'express'},
        {partId: 3, title: 'Премиум', image: '/images/logo_obed_go.jpg', slug: 'premium'},
        {partId: 4, title: 'Собери сам', image: '/images/logo_obed_go.jpg', slug: 'self'},
        {partId: 5, title: 'Рецепты', image: '/images/logo_obed_go.jpg', slug: 'special'}
    ])

    const part = ref(0)

    function changePosition(isNext = true) {
        if (part.value === 0) {
            part.value = 1
            return
        }
        if (isNext) {
            if (part.value === 5)
                part.value = 1
            else
                part.value++;
        } else {
            if (part.value === 1)
                part.value = 5
            else
                part.value--;
        }
    }

    function selectPart() {
        if(part.value === 0) {
            router.get(route('main'))
            return
        }
        let slug = foodParts.find(foodPart => foodPart.partId ===  part.value).slug ?? ''
        if(slug === 'self'){
            router.get(route('self'))
            return
        }
        if(slug === 'special'){
            router.get(route('special'))
            return
        }
        router.get(route('products', slug))
    }

    return { foodParts, part, changePosition, selectPart }
})
