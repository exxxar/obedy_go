<script setup>
import CallbackForm from "@/Components/Callback/CallbackForm.vue"
import PageTitle from "@/Components/Layout/PageTitle.vue"

</script>

<template>
    <PageTitle/>
    <div class="w-100 row m-0 d-flex flex-wrap justify-content-center mb-5">
        <div class="col-lg-6 col-md-6 col-sm-8 col-12">
            <h4 class="text-white">
                <em>В данный момент этот раздел не доступен, но это не должно Вас огорчать - теперь вы можете
                    <span class="text-danger">оформить заказ голосом!</span>
                </em>
            </h4>
            <CallbackForm/>
        </div>
    </div>

    <div class="w-100"></div>
</template>
