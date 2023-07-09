<script setup>
import {computed} from 'vue'
import PageTitle from "@/Components/Layout/PageTitle.vue"

const props = defineProps({status: Number})

const title = computed(() => {
    return {
        503: '503: Ресурс недоступен',
        500: '500: Произошла ошибка, свяжитесь с серверным специалистом',
        404: '404: Страница не найдена',
        403: '403: Запрещено',
    }[props.status]
})

const description = computed(() => {
    return {
        503: 'Извините, мы проводим кое-какие ремонтные работы. Пожалуйста, зайдите еще раз в ближайшее время.',
        500: 'Упс, что-то пошло не так на наших серверах.',
        404: 'Извините, страница, которую вы ищете, не была найдена',
        403: 'Извините, вам запрещен доступ к этой странице.',
    }[props.status]
})
</script>

<template>
    <div class="row justify-content-center w-100 absolute-center">
        <PageTitle :title="title" :error="true" :description="description"/>
    </div>
    <span class="errorPage__templeweed-container">
        <img src="/images/tembleweed.png" class="errorPage__tembleweed">
    </span>
    <div class="errorPage__terrain"></div>
</template>

<style scoped>
.absolute-center {
    margin: auto;
    position: absolute;
    top: 40%; left: 50%;
    -webkit-transform: translate(-50%,-50%);
    -ms-transform: translate(-50%,-50%);
    transform: translate(-50%,-50%);
}
img.errorPage__tembleweed {
    animation: templeWeed 5s infinite 1s;
}

.errorPage__templeweed-container {
    position: absolute;
    bottom: 10%;
    width: 10vw;
    transform: translateX(-100%);
    animation: moveWeed 3s infinite linear;
}


@keyframes moveWeed {
    0% {
        left: 0;
        transform: translateX(-100%) rotate(0);
    }

    100% {
        left: 130%;
        transform: translateX(100%) rotate(540deg);
    }
}

@keyframes templeWeed {
    0% {
        transform: translateY(0);
    }

    30% {
        transform: translateY(-3vw);
    }

    60% {
        transform: translateY(-4vw);
    }

    90% {
        transform: translateY(0);
    }

    90% {
        transform: translateY(0);
    }

}
</style>
