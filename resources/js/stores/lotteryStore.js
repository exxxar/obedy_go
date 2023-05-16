import {defineStore} from "pinia";
import {reactive, ref} from "vue";

export const useLotteryStore = defineStore('lottery', () => {
    const lotteries = ref([])
    const lottery_id = ref(null)
    const lotteryItem = ref({})

    const loadLotteries = async () => {
        await axios.get(route('lotteries')).then(response => {
            lotteries.value = response.data
        })
    }

    const loadLottery = async () => {
        await axios.get(route('lottery', lottery_id.value)).then(response => {
            lotteryItem.value = response.data
        })
    }

    return {
        lotteries, lottery_id, lotteryItem, loadLotteries, loadLottery
    }
})
