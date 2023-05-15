import {defineStore} from 'pinia'
import {computed, ref, watch} from 'vue'
import {sendNotify} from "@/app";

export const useCartStore = defineStore('cart', () => {

    const items = window.localStorage.getItem('cart_items') === null ? ref([]) : ref(JSON.parse(window.localStorage.getItem('cart_items')))

    const getProductById = computed((id) => items.value.find(item => item.product.id === id) ?? null)

    const getTotalCount = computed(() => {
        if (items === null || items.value.length === 0)
            return 0
        let sum = 0;
        items.value.forEach((item) => {
            sum += item.quantity
        });
        return sum
    })

    const getTotalPrice = computed(() => {
        if (items === null || items.value.length === 0)
            return 0
        let sum = 0;
        items.value.forEach((item) => {
            sum += item.product.price * item.quantity * (item.days.length > 0 ? item.days.length : 1)
        });
        return sum
    })

    const getTotalWeight = computed(() => {
        if (items === null || items.value.length === 0)
            return 0
        let sum = 0;
        items.value.forEach((item) => {
            if (item.product.weight > 0)
                sum += item.product.weight * item.quantity * (item.days.length > 0 ? item.days.length : 1)

            if (item.product.weight === 0) {
                if (item.product.positions.length > 0)
                    item.product.positions.forEach((j_item) => {
                        sum += j_item.weight
                    })
            }
        });
        return sum
    })

    watch(getTotalCount, (val) => {
        localStorage.setItem('cart_items', JSON.stringify(items.value))
    });

    function inCart(id) {
        return items.value.find(item => item.product.id === id) ? (items.value.find(item => item.product.id === id)).quantity : 0
    }

    function addToCart(product) {
        const cartItem = items.value.find(item => item.product.id === product.id)
        if (!cartItem)
            items.value.push({
                product,
                quantity: 1,
                days: [],
            })
        else
            cartItem.quantity++;
        sendNotify('Товар добавлен в корзину!')
    }

    function setDaysForItem(product) {
        let cartItem = items.value.find(item => item.product.id === product.id)
        cartItem.days = data.days;
    }

    function setQuantity(product) {
        let cartItem = items.value.find(item => item.product.id === product.id)
        cartItem.quantity = product.quantity;
    }

    function incQuantity(id) {
        let cartItem = items.value.find(item => item.product.id === id)
        cartItem.quantity++
        sendNotify('Товар добавлен в корзину!')
    }

    function decQuantity(id) {
        let cartItem = items.value.find(item => item.product.id === id)
        if (cartItem.quantity > 1) {
            cartItem.quantity--
            sendNotify('Лишний товар убран из корзины!')
        }
        else
            removeProduct(id)
    }

    function removeProduct(id) {
        let cartItemIndex = items.value.indexOf(items.value.find(item => item.product.id === id))
        items.value.splice(cartItemIndex, 1)
        sendNotify('Лишний товар убран из корзины!')
    }

    function clearCart() {
        items.value = []
    }

    return {
        items, getProductById, getTotalCount, getTotalPrice, getTotalWeight,
        inCart, addToCart, setDaysForItem, setQuantity, incQuantity, decQuantity,
        removeProduct, clearCart
    }
})
