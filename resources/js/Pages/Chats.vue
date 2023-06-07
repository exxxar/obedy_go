<script setup>
import BaseLayout from "@/Layouts/BaseLayout.vue"
import GalleryModal from "@/Components/Modals/GalleryModal.vue"
import {nextTick, onMounted, reactive, ref} from "vue"
import axios from "axios"
import {sendNotify} from "@/app"

const props = defineProps({
    chats: {
        type: Array,
        default: []
    }
})

const currentChat = reactive({
    data: null
})
const currentChatId = ref(null)

const defaultMessage = {
    chatId: '',
    message: '',
    images: [],
    files: []
}

const form = reactive({...defaultMessage})

onMounted(() => {
    if(route().params.chatId !== undefined)
        getChat(route().params.chatId)
})


const getChat = async (id) => {
    await axios.get(route('chat.messages', {id : id})).then(resp => {
        console.log(resp)
        currentChat.data = resp.data
        currentChatId.value = id
    }).catch(error => {
        console.log(error)
        if (error.response.status === 403)
            sendNotify('Вы не имеете доступа к выбранному чату', 'error')

        if (error.response.status === 404)
            sendNotify('Выбранный чат не найден', 'error')

    })
}

const sendMessage = async () => {
    form.chatId = currentChatId.value
    await axios.get(route('send.message', form)).then(resp => {
        currentChat.data.messages.push(resp.data)
    }).catch(error => {
        sendNotify('Произошла ошибка при отправке сообщения', 'error')
    })
}

const handleFileChange = async (evt) => {
    if (evt.target.files.length > 0) {
        Object.entries(evt.target.files).forEach(([key, value]) => {
            form.files.push(value)
            nextTick(() => {
                document.getElementById('messageFile-'+key).setAttribute('href', window.URL.createObjectURL(value))
            })
        })
    }
    evt.target.value = ''
}

</script>
<template>
    <BaseLayout>
        <template #content>
            <div class="container p-0">
                <div class="w-100 row m-0 d-flex flex-wrap justify-content-center mb-5">
                    <div class="col-md-5 col-lg-4 col-12 chat">
                        <div class="card mb-sm-3 mb-md-0 contacts_card">
                            <div class="card-header">
                                <div class="input-group">
                                    <input type="text" placeholder="Поиск..." autocomplete="off"
                                           name="chatSearch" id="chatSearch" class="form-control chat-search">
                                    <span class="input-group-text search_btn"><i class="fas fa-search"></i></span>
                                </div>
                            </div>
                            <div class="card-body contacts_body">
                                <ul class="contacts">
                                    <li class="active" v-for="chat in chats" @click="getChat(chat.id)">
                                        <div class="d-flex bd-highlight">
                                            <div class="img_cont">
                                                <img :src="chat.interlocutor.image" class="rounded-circle object-fit-cover user_img">
                                            </div>
                                            <div class="user_info text-truncate">
                                                <span>{{ chat.interlocutor.name }}</span>
                                                <p class="text-truncate">
                                                    {{ chat.lastMessage === null ? 'В чате пока нет сообщений' : chat.lastMessage }}</p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer"></div>
                        </div>
                    </div>
                    <div class="col-md-7 col-lg-8 col-12 chat">
                        <div  class="card overflow-auto" :class="currentChat.data !== null ? '' : 'justify-content-center'">
                            <template v-if="currentChat.data !== null">
                                <div class="card-header msg_head">
                                    <div class="d-flex bd-highlight">
                                        <div class="img_cont">
                                            <img :src="currentChat.data.interlocutor.image"
                                                 class="rounded-circle object-fit-cover user_img">
                                        </div>
                                        <div class="user_info">
                                            <span>{{currentChat.data.interlocutor.name}}</span>
                                            <p>{{currentChat.data.messageCount}} сообщений</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body msg_card_body"
                                     :class="currentChat.data.messages.length > 0 ? '' : 'd-flex flex-column justify-content-center'">
                                    <template v-if="currentChat.data.messages.length > 0">
                                        <div class="d-flex flex-column">
                                            <div class="d-flex justify-content-center mb-3">04.06.2023</div>
                                            <div class="d-flex justify-content-start mb-4">
                                                <div class="img_cont_msg">
                                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png"
                                                         class="rounded-circle user_img_msg">
                                                </div>
                                                <div class="msg_cotainer">
                                                    Hi, how are you samim?
                                                    <span class="msg_time">8:40 AM, Today</span>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end mb-4">
                                                <div class="msg_cotainer_send">
                                                    Hi jassa i am good tnx how about you?
                                                    <span class="msg_time_send">8:55 AM, Today</span>
                                                </div>
                                                <div class="img_cont_msg">
                                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png"
                                                         class="rounded-circle user_img_msg">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <div class="d-flex justify-content-center mb-3">05.06.2023</div>
                                            <div class="d-flex justify-content-start mb-4">
                                                <div class="img_cont_msg">
                                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png"
                                                         class="rounded-circle user_img_msg">
                                                </div>
                                                <div class="msg_cotainer">
                                                    <div
                                                        class="file-container d-flex flex-wrap gap-2 justify-content-center mb-2">
                                                        <div class="col-12 d-flex flex-wrap gap-1 justify-content-center">
                                                            <div class="col-32 ratio-4x3">
                                                                <img
                                                                    src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png"
                                                                    class="img-fluid img-thumbnail rounded"
                                                                    data-bs-toggle="modal" data-bs-target="#gallery">
                                                            </div>
                                                            <div class="col-32 ratio-4x3">
                                                                <img
                                                                    src="https://therichpost.com/wp-content/uploads/2020/06/avatar1.png"
                                                                    class="img-fluid img-thumbnail rounded"
                                                                    data-bs-toggle="modal" data-bs-target="#gallery">
                                                            </div>
                                                            <div class="col-32 ratio-4x3">
                                                                <img
                                                                    src="https://therichpost.com/wp-content/uploads/2020/06/avatar1.png"
                                                                    class="img-fluid img-thumbnail rounded"
                                                                    data-bs-toggle="modal" data-bs-target="#gallery">
                                                            </div>
                                                            <div class="col-32 ratio-4x3">
                                                                <img
                                                                    src="https://therichpost.com/wp-content/uploads/2020/06/avatar1.png"
                                                                    class="img-fluid img-thumbnail rounded">
                                                            </div>
                                                            <div class="col-32 ratio-4x3">
                                                                <img
                                                                    src="https://therichpost.com/wp-content/uploads/2020/06/avatar1.png"
                                                                    class="img-fluid img-thumbnail rounded">
                                                            </div>
                                                            <div class="col-32 ratio-4x3">
                                                                <img
                                                                    src="https://therichpost.com/wp-content/uploads/2020/06/avatar1.png"
                                                                    class="img-fluid img-thumbnail rounded">
                                                            </div>
                                                            <div class="col-32 ratio-4x3">
                                                                <img
                                                                    src="https://therichpost.com/wp-content/uploads/2020/06/avatar1.png"
                                                                    class="img-fluid img-thumbnail rounded">
                                                            </div>
                                                            <div class="col-32 ratio-4x3">
                                                                <img
                                                                    src="https://therichpost.com/wp-content/uploads/2020/06/avatar1.png"
                                                                    class="img-fluid img-thumbnail rounded">
                                                            </div>
                                                            <div class="col-32 ratio-4x3">
                                                                <img
                                                                    src="https://therichpost.com/wp-content/uploads/2020/06/avatar1.png"
                                                                    class="img-fluid img-thumbnail rounded">
                                                            </div>
                                                        </div>
                                                        <div class="col-12 d-flex flex-wrap gap-2">
                                                            <div class="col-auto overflow-hidden file-link-container">
                                                                <a class="d-flex gap-2 align-items-center text-break justify-content-between link"
                                                                   href="awfwafwafwafawfawf.docx" target="_blank">
                                                                    <p>
                                                                        afwafwafawfawfafwafwafawfawfafwafwafawfawfafwafwafawfawfafwafwafawfawfafwafwafawfawfafwafwafawfawfafwafwafawfawfafwafwafawfawfafwafwafawfawfafwafwafawfawfafwafwafawfawfafwafwafawfawfafwafwafawfawfafwafwafawfawfafwafwafawfawf.docx
                                                                    </p>
                                                                    <i class="fa-solid fa-download"></i>
                                                                </a>
                                                            </div>
                                                            <div class="col-auto file-link-container">
                                                                <a class="d-flex gap-2 align-items-center text-break justify-content-between link"
                                                                   href="file.docx" target="_blank">
                                                                    <p>file.docx</p>
                                                                    <i class="fa-solid fa-download"></i>
                                                                </a>
                                                            </div>
                                                            <div class="col-auto file-link-container">
                                                                <a class="d-flex gap-2 align-items-center text-break justify-content-between link"
                                                                   href="fff.pdf" target="_blank">
                                                                    <p>fff.pdf</p>
                                                                    <i class="fa-solid fa-download"></i>
                                                                </a>
                                                            </div>
                                                            <div class="col-auto file-link-container">
                                                                <a class="d-flex gap-2 align-items-center text-break justify-content-between link"
                                                                   href="awfwafawfawf.pdf" target="_blank">
                                                                    <p>awfwafawfawf.pdf</p>
                                                                    <i class="fa-solid fa-download"></i>
                                                                </a>
                                                            </div>
                                                            <div class="col-auto file-link-container">
                                                                <a class="d-flex gap-2 align-items-center text-break justify-content-between link"
                                                                   href="efgrshtdjy.pdf" target="_blank">
                                                                    <p>efgrshtdjy.pdf</p>
                                                                    <i class="fa-solid fa-download"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    I am good too, thank you for your chat template
                                                    I am good too, thank you for your chat template
                                                    I am good too, thank you for your chat template
                                                    I am good too, thank you for your chat template
                                                    I am good too, thank you for your chat template
                                                    I am good too, thank you for your chat template
                                                    <span class="msg_time">9:00 AM, Today</span>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end mb-4">
                                                <div class="msg_cotainer_send">
                                                    You are welcome
                                                    <span class="msg_time_send">9:05 AM, Today</span>
                                                </div>
                                                <div class="img_cont_msg">
                                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png"
                                                         class="rounded-circle user_img_msg">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start mb-4">
                                                <div class="img_cont_msg">
                                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png"
                                                         class="rounded-circle user_img_msg">
                                                </div>
                                                <div class="msg_cotainer">
                                                    I am looking for your next templates
                                                    <span class="msg_time">9:07 AM, Today</span>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-end mb-4">
                                                <div class="msg_cotainer_send">
                                                    Ok, thank you have a good day
                                                    <span class="msg_time_send">9:10 AM, Today</span>
                                                </div>
                                                <div class="img_cont_msg">
                                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png"
                                                         class="rounded-circle user_img_msg">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-start mb-4">
                                                <div class="img_cont_msg">
                                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png"
                                                         class="rounded-circle user_img_msg">
                                                </div>
                                                <div class="msg_cotainer">
                                                    Bye, see you
                                                    <span class="msg_time">9:12 AM, Today</span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                    <p v-else class="text-center">В чате пока нет сообщений</p>
                                </div>
                                <div class="card-footer">
                                    <div class="input-group">
                                        <input type='file' id="actual-btn" @change="handleFileChange($event, index)" multiple hidden>
                                        <label class="input-group-text attach_btn" for="actual-btn">
                                            <i class="fas fa-paperclip"></i>
                                        </label>
                                        <textarea name="message" class="form-control type_msg" v-model="form.message"
                                                  placeholder="Напишите ваше сообщение..."></textarea>
                                        <span class="input-group-text send_btn" @click="sendMessage"><i class="fas fa-location-arrow"></i></span>
                                    </div>
                                </div>
                                <ul class="list-group fixed-max-height-125px overflow-x-hidden bg-white"
                                    v-if="form.files.length > 0">
                                    <li class="list-group-item d-flex justify-content-between align-items-center bg-white px-4"
                                        v-for="(file, index) in form.files">
                                        <a :id="'messageFile-'+index" href="#" target="_blank">{{file.name}}</a>
                                        <span @click="form.files.splice(index, 1)"><i class="fa-solid fa-xmark fa-lg cursor-pointer"></i></span>
                                    </li>
                                </ul>
                            </template>
                            <p v-else class="text-center">Выберите кому хотели бы написать</p>
                        </div>
                    </div>
                </div>
                <GalleryModal></GalleryModal>
            </div>
        </template>
    </BaseLayout>
</template>
