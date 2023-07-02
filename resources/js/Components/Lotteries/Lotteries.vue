<script setup>
import {onMounted} from "vue"
import {useLotteryStore} from "@/stores/lotteryStore"
import {storeToRefs} from "pinia"

const lottery = useLotteryStore()
const { lotteries, lottery_id } = storeToRefs(lottery)

onMounted(() => {
    lottery.loadLotteries()
})
</script>

<template>
    <div class="row mt-2 mb-2 d-flex justify-content-center flex-wrap">
        <div class="col-md-6 col-lg-4 col-12" v-for="item in lotteries">
            <div class="lottery-slide mb-3">
                <img v-lazy="item.image" alt="">
                <p class="inf">Мест:
                    <strong>{{ item.place_count - item.free_place_count }} / {{ item.place_count }}</strong>
                </p>
                <div class="progress" style="height: 30px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
                         :style="'width: ' + (item.place_count - item.free_place_count) / item.place_count * 100 + '%'"></div>
                </div>
                <button class="btn btn-danger mt-2 w-100" @click="lottery_id = item.id">Участвовать</button>
            </div>
        </div>
    </div>
</template>
