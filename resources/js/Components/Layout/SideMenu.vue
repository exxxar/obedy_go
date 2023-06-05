<script setup>

</script>

<template>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center col-lg-4 mb-2 mb-lg-0 link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
            </a>
            <ul class="dropdown-menu text-small shadow" style="">
                <li><a class="dropdown-item active" href="#" aria-current="page">Overview</a></li>
                <li><a class="dropdown-item" href="#">Inventory</a></li>
                <li><a class="dropdown-item" href="#">Customers</a></li>
                <li><a class="dropdown-item" href="#">Products</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="#">Reports</a></li>
                <li><a class="dropdown-item" href="#">Analytics</a></li>
                <li><div class="dropdown-item" data-bs-toggle="modal" data-bs-target="#login" v-if="!user.isAuthorized">
                    Войти
                </div></li>
                <div class="cart-icon login" @click="userStore.logout()" v-else>Выйти</div>
                <div class="cart-icon cart" @click="is_cart_open = true; setPopover();">Корзина <span
                    v-if="getTotalCount>0">{{ getTotalCount }}</span>
                </div>
                <div class="cart-icon about" data-bs-toggle="modal" data-bs-target="#about">О нас</div>
                <div class="cart-icon callback" data-bs-toggle="modal" data-bs-target="#callback">Напиши нам</div>
                <div class="cart-icon delivery" data-bs-toggle="modal" data-bs-target="#delivery">Доставка</div>
                <div class="cart-icon lottery" data-bs-toggle="modal" data-bs-target="#lottery"
                     @click="lottery_id = null" v-if="lotteries.length > 0">Акции
                </div>
                <div class="cart-icon checkOrder" data-bs-toggle="modal" data-bs-target="#checkOrder">Узнать статус</div>
            </ul>
        </div>
    <div class="cart-container d-sm-block d-none" v-if="!is_cart_open">
        <div class="cart-icon login" data-bs-toggle="modal" data-bs-target="#login" v-if="!user.isAuthorized">
            Войти
        </div>
        <div class="cart-icon login" @click="userStore.logout()" v-else>Выйти</div>
        <div class="cart-icon cart" @click="is_cart_open = true; setPopover();">Корзина <span
            v-if="getTotalCount>0">{{ getTotalCount }}</span>
        </div>
        <div class="cart-icon about" data-bs-toggle="modal" data-bs-target="#about">О нас</div>
        <div class="cart-icon callback" data-bs-toggle="modal" data-bs-target="#callback">Напиши нам</div>
        <div class="cart-icon delivery" data-bs-toggle="modal" data-bs-target="#delivery">Доставка</div>
        <div class="cart-icon lottery" data-bs-toggle="modal" data-bs-target="#lottery"
             @click="lottery_id = null" v-if="lotteries.length > 0">Акции
        </div>
        <div class="cart-icon checkOrder" data-bs-toggle="modal" data-bs-target="#checkOrder">Узнать статус</div>
    </div>

    <ul class="footer-container d-sm-none" @mouseleave="bottom_menu_show = false">
        <li class="p-2 text-center" v-if="!bottom_menu_show" @click="bottom_menu_show = true">Показать меню
            <span class="badge badge-danger" v-if="getTotalCount > 0">{{ getTotalCount }}</span></li>
        <template v-else>
            <li class="p-2 text-center" @click="bottom_menu_show = false">Скрыть меню</li>
            <ul class="bottom_menu">
                <li v-for="foodPart in foodParts" @click="part = foodPart.partId">{{ foodPart.title }}</li>
                <li class="hr"></li>
                <li data-bs-toggle="modal" data-bs-target="#login" v-if="!user.isAuthorized">
                    Войти
                </li>
                <li @click="userStore.logout()" v-else>Выйти</li>
                <li @click="is_cart_open = true">
                    Корзина <span class="badge badge-danger" v-if="countInCart>0">{{ countInCart }}</span>
                </li>
                <li data-bs-toggle="modal" data-bs-target="#about">О нас</li>
                <li data-bs-toggle="modal" data-bs-target="#callback">Напиши нам</li>
                <li data-bs-toggle="modal" data-bs-target="#delivery">Доставка</li>
                <li data-bs-toggle="modal" data-bs-target="#lottery"
                    @click="lottery_id = null" v-if="lotteries.length > 0">Акции
                </li>
                <li data-bs-toggle="modal" data-bs-target="#checkOrder">Узнать статус</li>
            </ul>
        </template>
    </ul>
</template>
