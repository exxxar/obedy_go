<template>
    <div>
        <h6 class="text-center">Запиши голосовое сообщение<br><strong class="text-danger">Удерживай кнопку для записи!</strong></h6>
        <div class="d-flex justify-content-center mt-2">
            <vue-record-audio :mode="'hold'" @result="onResult"></vue-record-audio>
        </div>

        <div class="row d-flex justify-content-center mt-2" v-if="recordings.length>0">
            <div class="col-12">
                <div class="recorded-audio">
                    <div v-for="(record, index) in recordings" :key="index" class="recorded-item">
                        <div class="audio-container">
                            <audio :src="record.src" controls/>
                        </div>
                        <div>
                            <a @click="removeRecord(index)" class="button is-dark"><i class="fas fa-trash"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</template>

<script>
import VueRecordAudio from "@/Components/Basic/vue-record/VueRecordAudio.vue"
import {sendNotify} from "@/app";

export default {
    components: {VueRecordAudio},
    props: ["phone","name","cansend"],
    data() {
        return {
            recommendations:false,
            recordings: []
        }
    },
    watch:{
        cansend:function (newVal) {
            if (newVal)
                this.send();
        }
    },
    methods: {
        send() {

            let formData = new FormData()

            formData.append("phone", this.phone)
            formData.append("name", this.name)

            for (let i = 0; i < this.recordings.length; i++) {
                let file = this.recordings[i].data;
                console.log(file);
                formData.append('files[' + i + ']', file)
            }

            axios.post('../api/v2/obedy/voice', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                }
            ).then(function () {
                sendNotify('Голосовое сообщение успешно отправлено!')
                this.recordings = []
            })
                .catch(function () {
                })
            this.recordings = []
        },
        removeRecord(index) {
            this.recordings.splice(index, 1)
        },
        onResult(data) {
            this.recordings.push({
                src: window.URL.createObjectURL(data),
                data: data
            })
        },
    }
}
</script>

