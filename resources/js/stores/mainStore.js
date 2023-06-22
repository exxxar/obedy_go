import { defineStore } from 'pinia'
import { reactive, ref, watch } from "vue"
import { router } from '@inertiajs/vue3'


export const useMainStore = defineStore('main', () => {

    const foodParts = reactive([
        {partId: 1, title: 'Стандарт', image: '/images/logo_obed_go.jpg', slug: 'standard'},
        {partId: 2, title: 'Экспресс', image: '/images/logo_obed_go.jpg', slug: 'express'},
        {partId: 3, title: 'Премиум', image: '/images/logo_obed_go.jpg', slug: 'premium'},
        {partId: 4, title: 'Собери сам', image: '/images/logo_obed_go.jpg', slug: 'self'},
        {partId: 5, title: 'Рецепты', image: '/images/logo_obed_go.jpg', slug: 'special'},
    ])

    const part = ref(0)

    watch(() => part.value, (newValue, oldValue) => {
        if (newValue !== oldValue && newValue !== null)
            selectPart()
    })

    function changePosition(isNext = true) {
        if (part.value === 0 || part.value === null) {
            part.value = 1
            return
        }
        if (isNext) {
            if (part.value === 5)
                part.value = 1
            else
                part.value++
        } else {
            if (part.value === 1)
                part.value = 5
            else
                part.value--
        }
    }

    function selectPart() {
        if(part.value === 0) {
            router.get(route('main'))
            return
        }
        let slug = foodParts.find(foodPart => foodPart.partId ===  part.value).slug ?? ''
        if(slug === 'self' || slug === 'special') {
            router.get(route(slug))
            return
        }
        if(slug === 'standard' || slug === 'express' || slug === 'premium') {
            router.get(route('products', slug))
        }
    }

    function switchFoodPart() {
        if (route().current('main'))
            part.value = 0
        else if (route().current('products'))
            part.value = foodParts.find(foodPart => foodPart.slug === route().params.foodPart).partId
        else if (route().current('self') || route().current('special'))
            part.value = foodParts.find(foodPart => foodPart.slug === route().current()).partId
        else
            part.value = null
    }

    return { foodParts, part, changePosition, selectPart, switchFoodPart }
})
