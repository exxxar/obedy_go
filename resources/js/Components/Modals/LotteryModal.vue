<script setup>
import Lotteries from "@/Components/Lotteries/Lotteries.vue"
import {useLotteryStore} from "@/stores/lotteryStore.js"
import LotteryGame from "@/Components/Lotteries/LotteryGame.vue"
import Modal from '@/Components/Basic/Modal.vue'
import {storeToRefs} from "pinia";

const {lottery_id} = storeToRefs(useLotteryStore())
</script>

<template>
    <Modal id="lottery" class_size="modal-lg" title="Розыгрыши и призы">
        <template #body>
            <Lotteries v-if="!lottery_id"/>
            <LotteryGame v-else/>
        </template>
    </Modal>
</template>

