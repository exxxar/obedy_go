import {defineStore, storeToRefs} from 'pinia'
import {computed, reactive, ref, watch} from 'vue'
import {modals, sendNotify} from "@/app";
import axios from "axios";
import {useUserStore} from "@/stores/userStore";

export const useCartStore = defineStore('cart', () => {

    const items = window.localStorage.getItem('cart_items') === null ? ref([]) : ref(JSON.parse(window.localStorage.getItem('cart_items')))

    const getProductById = computed((id) => items.value.find(item => item.product.id === id) ?? null)

    const userStore = useUserStore()

    const getTotalCount = computed(() => {
        if (items === null || items.value.length === 0)
            return 0
        let sum = 0;
        items.value.forEach((item) => {
            sum += getItemQuantity(item)
        });
        return sum
    })

    const getTotalPrice = computed(() => {
        if (items === null || items.value.length === 0)
            return 0
        let sum = 0;
        items.value.forEach((item) => {
            sum += item.product.price * getItemQuantity(item)
        });
        return sum
    })

    const getTotalWeight = computed(() => {
        if (items === null || items.value.length === 0)
            return 0
        let sum = 0;
        items.value.forEach((item) => {
            if (item.product.weight > 0)
                sum += item.product.weight * getItemQuantity(item)

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
        let cartItem = items.value.find(item => item.product.id === id)
        if (cartItem)
            return getItemQuantity(cartItem)
        return 0
    }

    function getItemQuantity(cartItem) {
        return cartItem.users.reduce((accumulator, object) => {
            return accumulator + parseInt(object.quantity)
        }, 0)
    }

    const hasOtherUserCart = computed(() => {
        let user = userStore.dataUser()
        return items.value.find(item => {
            let res = item.users.find(userItem => userItem.phone !== user.phone)
            return !!res

        }) !== undefined
    })

    function addToCart(product) {
        let cartItem = items.value.find(item => item.product.id === product.id)
        if (!cartItem) {
            let user = userStore.dataUser()
            items.value.push({
                product,
                users: [
                    {
                        name: user.name,
                        phone: user.phone,
                        quantity: 1,
                        date: new Date().toLocaleDateString() + ' ' + new Date().toLocaleTimeString(),
                        inDBCart: user.isAuthorized
                    }
                ]
            })
            cartItem = items.value[items.value.length - 1]
        } else
            cartItem.users[getUserProductIndex(product.product.id)].quantity++;
        sendNotify('Товар добавлен в корзину!')
        saveCart(cartItem, 'add')
    }

    function addToCartFromBD(products) {
        let user = userStore.dataUser()
        products.forEach(product => {
            const cartItem = items.value.find(item => item.product.id === product.product.id)
            if (!cartItem) {
                items.value.push(product)
            } else {
                let usersToAdd = [];
                for (let index = 0; index < product.users.length; index++) {
                    if (index === getUserProductIndex(product.product.id) && user.phone === product.users[index].phone) {
                        if (!cartItem.users[index].inDBCart) {
                            cartItem.users[index].quantity += parseInt(product.users[index].quantity)
                            saveCart(cartItem, 'add')
                        }
                        cartItem.users[index].inDBCart = true
                    } else if (getUserProductIndex(product.product.id) === -1 && !cartItem.users[index].inDBCart) {
                        cartItem.users[0].name = user.name
                        cartItem.users[0].phone = user.phone
                        cartItem.users[0].inDBCart = true
                        saveCart(cartItem, 'addMore')
                        usersToAdd.push(product.users[index])
                    } else {
                        if (index < cartItem.users.length) {
                            if (!cartItem.users[index].inDBCart) {
                                usersToAdd.push(product.users[index])
                                saveCart(cartItem, 'add')
                            }
                        } else {
                            usersToAdd.push(product.users[index])
                            saveCart(cartItem, 'add')
                        }
                    }
                }
                usersToAdd.forEach(users => {
                    let userItem = cartItem.users.find(user=>user.phone === users.phone)
                    if(userItem)
                        userItem.quantity += parseInt(users.quantity)
                    else
                        cartItem.users.push(users)
                })
            }
        })
        let productToAdd = []
        items.value.forEach(item => {
            if (!products.find(product => product.product.id === item.product.id)) {
                item.users[0].name = user.name
                item.users[0].phone = user.phone
                item.users[0].inDBCart = true
                productToAdd.push(item)
            }
        })
        if (productToAdd.length > 0)
            axios.post(route('save.cart', {'products': productToAdd}))
    }

    function getUserProductIndex(id) {
        let user = userStore.dataUser()
        let product = items.value.find(item => item.product.id === id)
        return product.users.findIndex(productUser => productUser.phone === user.phone)
    }

    function incQuantity(id, index = getUserProductIndex(id)) {
        let cartItem = items.value.find(item => item.product.id === id)
        if (index === -1) {
            let user = userStore.dataUser()
            cartItem.users.push( {
                name: user.name,
                phone: user.phone,
                quantity: 1,
                date: new Date().toLocaleDateString(),
                inDBCart: user.isAuthorized
            })
        }else {
            cartItem.users[index].quantity++
        }
        sendNotify('Товар добавлен в корзину!')
        saveCart(cartItem, 'inc')
    }

    function decQuantity(id, index = getUserProductIndex(id)) {
        if (index === -1) {
            modals.getOrCreateInstance(document.getElementById('changeUserCart')).show()
            return
        }
        let cartItem = items.value.find(item => item.product.id === id)
        if (cartItem.users[index].quantity > 1) {
            cartItem.users[index].quantity--
            sendNotify('Лишний товар убран из корзины!')
            saveCart(cartItem, 'dec')
        } else
            removeProduct(id, index)
    }

    function removeProduct(id, index = getUserProductIndex(id)) {
        if (index === -1) {
            modals.getOrCreateInstance(document.getElementById('changeUserCart')).show()
            return
        }
        let cartItem = items.value.find(item => item.product.id === id)
        let cartItemIndex = items.value.indexOf(cartItem)
        if(index === getUserProductIndex(id) || cartItem.users.length === 1){
            saveCart(items.value[cartItemIndex], 'delete')
            items.value.splice(cartItemIndex, 1)
        } else {
            cartItem.users.splice(index, 1)
            saveCart(items.value[cartItemIndex], 'dec')
        }
        sendNotify('Лишний товар убран из корзины!')
    }

    async function clearCart() {
        items.value = []
        let user = userStore.dataUser()
        if (user.isAuthorized) {
            await axios.post(route('clear.cart'))
                .catch(errors => {
                    if (errors.response.status === 403) {
                        userStore.getUser()
                    }
                })
        }
    }

    async function saveCart($product, $action) {
        let user = userStore.dataUser()
        if (user.isAuthorized) {
            await axios.post(route('change.cart', {'product': $product, 'action': $action}))
                .catch(errors => {
                    if (errors.response.status === 403) {
                        userStore.getUser()
                    }
                })
        }
    }

    return {
        items, getProductById, getTotalCount, getTotalPrice,
        getTotalWeight, hasOtherUserCart,
        inCart, addToCart, incQuantity, decQuantity,
        removeProduct, clearCart, addToCartFromBD
    }
})
