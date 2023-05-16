<script setup>
import {useLotteryStore} from "@/stores/lotteryStore";
import {storeToRefs} from "pinia";
import {onMounted, reactive, ref} from "vue";
import {sendNotify} from "@/app";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";

const lottery = useLotteryStore()
const {lotteryItem, lottery_id} = storeToRefs(lottery)

const current_slot_count = ref(null)
const errors = ref([])

const form = reactive({
    name: '',
    email: '',
    phone: '',
    code: '',
    lottery_id: lottery_id.value,
    selected_place: null
})

onMounted(() => {
    lottery.loadLottery()
    Echo.channel(`lottery.${form.lottery_id}`)
        .listen('ChangeLotteryEvent', () => {
            lottery.loadLottery()
        });
})

const isOccupied = (index) => {
    let tmp = lotteryItem.value.occupied_places
    if (tmp.length === 0)
        return false;
    return tmp.find(item => item === index) != null
}

const openAuthModal = (index) => {
    if (isOccupied(index)) {
        sendNotify('Данный слот уже кем-то занят! Попробуйте занять другой!', 'error')
        return
    }
    form.selected_place = index
    /* if (current_slot_count.value !== null) {
         $('#accept-lottery').modal('show')
         return;
     }*/
    $('#personal-info').modal('show')

}

const pick = () => {
    if (isOccupied(form.selected_place)) {
        sendNotify('Данный слот уже кем-то занят! Попробуйте занять другой!', 'error')
        return
    }
    if (form.selected_place === null) {
        sendNotify('Слот не выбран!', 'error')
        return
    }
    axios.post(route('lottery.pick'), form).then(resp => {

        current_slot_count.value = resp.data.current_slot_count === 0 ? null : resp.data.current_slot_count;

        sendNotify('Спасибо! Слот успешно занят вами!')
        $('#accept-lottery').modal('hide')
        $('#personal-info').modal('hide')
    }).catch((resp) => {
        if(resp.response.data.errors && resp.response.data.errors.lottery_id){
            sendNotify(resp.response.data.errors.lottery_id[0], 'error')
            $('#personal-info').modal('hide')
            lotteryItem.value = {}
        }
        else if(resp.response.data.errors && resp.response.data.errors.selected_place){
            sendNotify(resp.response.data.errors.selected_place[0], 'error')
            $('#personal-info').modal('hide')
            lottery.loadLottery()
        }else {
            errors.value = resp.response.data.errors
        }
    })
}

</script>

<template>
    <div class="row" v-if="lotteryItem">
        <div class="col-sm-12">
            <h2 class="text-danger">{{ lotteryItem.title }} </h2>
            <div v-if="lotteryItem.is_active" class="badge badge-success">
                Лотерея активна (до {{ lotteryItem.date_end }})
            </div>
            <div class="badge badge-danger" v-else>Лотерея не активна</div>
            <p v-if="current_slot_count">Доступное число активаций <strong>{{ current_slot_count }}</strong></p>
            <div class="badge badge-danger" v-if="lotteryItem.is_complete">Лотерея окончена</div>
            <p>{{ lotteryItem.description }}</p>
            <div class="progress" style="height: 30px;">
                <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                     :style="'width: '+(lotteryItem.place_count - lotteryItem.free_place_count)/lotteryItem.place_count*100+'%'"></div>
            </div>
        </div>
        <div class="col-sm-4 col-md-3 col-6 lottery-item-wrapper" v-for="place in lotteryItem.place_count">
            <div class="lottery-item" @click="openAuthModal(place)">
                <img v-lazy="'/images/logo_obed_go.jpg'" alt="" v-if="!isOccupied(place)">
                <img v-lazy="'/images/logo_obed_go_occuped.jpg'" alt="" v-else>
                <p> #{{ place }}</p>
            </div>
        </div>
    </div>

    <div class="row" v-else>
        <div class="col-sm-12 d-flex justify-content-center align-items-center">
            <img v-lazy="'/images/logo_obed_go.jpg'" class="img-fluid" alt="">
        </div>
    </div>

    <Modal id="personal-info" title="Участие в розыгрыше">
        <template #body class="row d-flex justify-content-center">
            <form class="row d-flex justify-content-center">
                <div class="col-12">
                    <TextInput :errors="errors.code" placeholder="Промокод" v-model="form.code"></TextInput>
                </div>
                <h4 class="col-12 mb-2 mt-2">Для получение <span
                    class="text-danger font-weight-bolder">оповещения</span> о выигрыше Вам необходимо сообщить нам свой
                    номер
                    <span class="text-danger font-weight-bolder">телефона</span> и
                    <span class="text-danger font-weight-bolder">электронную почту</span></h4>
                <div class="col-12">
                    <TextInput :errors="errors.name" placeholder="Ваше имя" v-model="form.name"></TextInput>
                </div>
                <div class="col-12">
                    <TextInput :errors="errors.phone" placeholder="Ваш номер телефона" v-model="form.phone" mask="+7 (###) ###-##-##"></TextInput>
                </div>
                <div class="col-12">
                    <TextInput :errors="errors.email" placeholder="Ваша почта" v-model="form.email" type="email"></TextInput>
                </div>
                <div class="col-12 col-sm-6">
                    <button type="button" class="btn btn-outline-success w-100" @click="pick()">Занять место</button>
                </div>
            </form>
        </template>
    </Modal>

  <!---  <Modal id="accept-lottery" :header="false">
        <template #body>
            <div class="row d-flex justify-content-center">
                <p v-if="current_slot_count">Доступное число активаций <strong>{{ current_slot_count }}</strong></p>
            </div>
            <div class="row d-flex justify-content-center">
                <button class="mt-3 btn btn-danger mr-2" @click=" $('#accept-lottery').modal('hide')">Отменить</button>
                <button class="mt-3 btn btn-success" @click="pick()">Подтвердить</button>
            </div>
        </template>
    </Modal> -->


</template>
