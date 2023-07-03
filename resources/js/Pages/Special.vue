<script setup>
import {computed, ref, watch} from "vue"
import MenuCard from "@/Components/Menu/MenuCard.vue"
import Modal from '@/Components/Basic/Modal.vue'
import PageTitle from "@/Components/Layout/PageTitle.vue"
import SpecialistCard from "@/Components/Specialists/SpecialistCard.vue"

const props = defineProps({
    menus: {
        type: Array,
        default: []
    }
})

const selectSpecialId = ref(null)
const selectSpecialistId = ref(null)
const selectSpecialist = ref(null)

watch(() => selectSpecialId.value, (newValue, oldValue) => {
    if (newValue !== null)
        selectSpecialist.value = props.menus.find(item => item.id === newValue).specialist
    if (newValue === null && selectSpecialistId.value === null)
        selectSpecialist.value = null
});
watch(() => selectSpecialistId.value, (newValue, oldValue) => {
    if (newValue === null)
        selectSpecialist.value = null
})

const purchasedMenus = computed(() => { return props.menus.filter(menu => menu.isUserMenu === true) })
const unpaidMenus = computed(() => { return props.menus.filter(menu => menu.isUserMenu === false) })
</script>

<template>
    <PageTitle title="Рецепты"/>
    <div class="container tab-content pb-3">
        <div class="row justify-content-center gap-4">
            <div class="d-flex justify-content-center position-absolute-custom"
                 v-if="purchasedMenus.length === 0 && unpaidMenus.length === 0">
                <h1 class="text-uppercase text-white">
                    В данный момент этот раздел недоступен!
                </h1>
            </div>
            <div class="d-flex flex-column gap-4" v-if="purchasedMenus.length > 0">
                <h3 class="text-uppercase text-white">Специально для вас</h3>
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-4 col-12" v-for="menu in purchasedMenus">
                        <MenuCard v-model:special-id="selectSpecialId" :menu="menu"></MenuCard>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-column gap-4" v-if="unpaidMenus.length > 0">
                <h3 class="text-uppercase text-white">Вкусные предложения...</h3>
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-lg-4 col-12" v-for="menu in unpaidMenus">
                        <MenuCard v-model:special-id="selectSpecialId" :menu="menu"></MenuCard>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Modal v-if="selectSpecialId !== null" id="specialModal">
        <template #body>
            <MenuCard :in-modal="true"
                      v-model:special-id="selectSpecialId"
                      v-model:specialist-id="selectSpecialistId"
                      :menu="menus.find(item => item.id === selectSpecialId)"></MenuCard>
        </template>
    </Modal>

    <Modal v-if="selectSpecialistId !== null" id="specialistModal">
        <template #body>
            <SpecialistCard :in-modal="true"
                            v-model:specialist-id="selectSpecialistId"
                            :specialist="selectSpecialist"></SpecialistCard>
        </template>
    </Modal>
</template>

<style scoped>
.position-absolute-custom {
    margin: auto;
    position: absolute;
    top: 50%;
    left: 50%;
    -webkit-transform: translate(-50%,-50%);
    -ms-transform: translate(-50%,-50%);
    transform: translate(-50%,-50%);
    text-align: center;
}
</style>
