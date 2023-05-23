<script setup>
import {onMounted, reactive, ref} from "vue"
import {sendNotify, modals} from "@/app"
import {storeToRefs} from "pinia"
import {useLotteryStore} from "@/stores/lotteryStore"
import Modal from "@/Components/Basic/Modal.vue"
import TextInput from "@/Components/Basic/TextInput.vue"
import {useUserStore} from "@/stores/userStore";

const lottery = useLotteryStore()
const {lotteryItem, lottery_id} = storeToRefs(lottery)
const userStore = useUserStore()
const {user} = storeToRefs(userStore)


const current_slot_count = ref(null)
const errors = ref([])

const defaultForm = {
    name: '',
    email: '',
    phone: '',
    code: '',
    lottery_id: lottery_id.value,
    selected_place: null
}

const form = reactive({...defaultForm})

onMounted(() => {
    lottery.loadLottery()
    Echo.channel(`lottery.${form.lottery_id}`)
        .listen('ChangeLotteryEvent', () => {
            lottery.loadLottery()
        })
    changeFormPhoneName()
})

const changeFormPhoneName = () => {
    Object.assign(form, defaultForm)
    Object.assign(form, {
        phone: user.value.phone,
        name: user.value.name
    })
    errors.value = []
}

const isOccupied = (index) => {
    let tmp = lotteryItem.value.occupied_places
    if (tmp.length === 0)
        return false
    return tmp.find(item => item === index) != null
}

const openAuthModal = (index) => {
    if (isOccupied(index)) {
        sendNotify('Данный слот уже кем-то занят! Попробуйте занять другой!', 'error')
        return
    }
    form.selected_place = index
    modals.getOrCreateInstance(document.getElementById('personal-info')).show()
}

const pick = () => {
    axios.post(route('lottery.pick'), form).then(resp => {
        current_slot_count.value = resp.data.current_slot_count === 0 ? null : resp.data.current_slot_count;
        sendNotify('Спасибо! Слот успешно занят вами!')
        modals.getOrCreateInstance(document.getElementById('personal-info')).hide()
        changeFormPhoneName()
        if(current_slot_count.value !== null)
            modals.getOrCreateInstance(document.getElementById('accept-lottery')).show()
    }).catch((resp) => {
        if(resp.response.data.errors && resp.response.data.errors.lottery_id){
            sendNotify(resp.response.data.errors.lottery_id[0], 'error')
            modals.getOrCreateInstance(document.getElementById('personal-info')).hide()
            lotteryItem.value = {}
        }
        else if(resp.response.data.errors && resp.response.data.errors.selected_place){
            sendNotify(resp.response.data.errors.selected_place[0], 'error')
            modals.getOrCreateInstance(document.getElementById('personal-info')).hide()
            lottery.loadLottery()
        }
        else
            errors.value = resp.response.data.errors
    })
}

</script>

<template>
    <div class="row" v-if="lotteryItem">
        <div class="col-12">
            <h2 class="text-danger">{{ lotteryItem.title }} </h2>
            <div v-if="lotteryItem.is_active" class="badge bg-success">Лотерея активна (до {{ lotteryItem.date_end }})</div>
            <div class="badge bg-danger" v-else>Лотерея не активна</div>
            <p v-if="current_slot_count">Доступное число активаций <strong>{{ current_slot_count }}</strong></p>
            <div class="badge bg-danger" v-if="lotteryItem.is_complete">Лотерея окончена</div>
            <p>{{ lotteryItem.description }}</p>
            <div class="progress" style="height: 30px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                     :style="'width: '+(lotteryItem.place_count - lotteryItem.free_place_count)/lotteryItem.place_count*100+'%'"></div>
            </div>
        </div>
        <div class="col-sm-4 col-md-3 col-6 lottery-item-wrapper" role="button" v-for="place in lotteryItem.place_count">
            <div class="lottery-item" @click="openAuthModal(place)">
                <img v-lazy="'/images/logo_obed_go.jpg'" alt="" v-if="!isOccupied(place)">
                <img v-lazy="'/images/logo_obed_go_occuped.jpg'" alt="" v-else>
                <p>#{{ place }}</p>
            </div>
        </div>
    </div>

    <div class="row" v-else>
        <div class="col-12 d-flex justify-content-center align-items-center">
            <img v-lazy="'/images/logo_obed_go.jpg'" class="img-fluid" alt="">
        </div>
    </div>

    <Modal id="personal-info" title="Участие в розыгрыше" :clear-function="changeFormPhoneName">
        <template #body class="row d-flex justify-content-center">
            <form class="row d-flex justify-content-center">
                <div class="col-12">
                    <TextInput :errors="errors.code" placeholder="Промокод" v-model="form.code"></TextInput>
                </div>
                <h4 class="col-12 mb-2 mt-2">Для получение
                    <span class="text-danger fw-bolder">оповещения</span>
                    о выигрыше Вам необходимо сообщить нам свой номер
                    <span class="text-danger fw-bolder">телефона</span> и
                    <span class="text-danger fw-bolder">электронную почту</span>
                </h4>
                <div class="col-12">
                    <TextInput :errors="errors.name" placeholder="Ваше имя" v-model="form.name"></TextInput>
                </div>
                <div class="col-12">
                    <TextInput :errors="errors.phone" placeholder="Ваш номер телефона"
                               v-model="form.phone" mask="+7 (###) ###-##-##"></TextInput>
                </div>
                <div class="col-12">
                    <TextInput :errors="errors.email" placeholder="Ваша почта"
                               v-model="form.email" type="email"></TextInput>
                </div>
                <div class="col-12 col-sm-6">
                    <button type="button" class="btn btn-outline-success w-100 mt-3" @click="pick()">Занять место</button>
                </div>
            </form>
        </template>
    </Modal>

    <Modal id="accept-lottery" :header="false" :footer="true">
        <template #body>
            <div class="row justify-content-center">
                <p>Доступное число активаций <strong>{{ current_slot_count }}</strong></p>
            </div>
        </template>
    </Modal>


</template>
