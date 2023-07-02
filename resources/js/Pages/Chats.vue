<script setup>
import GalleryModal from "@/Components/Modals/GalleryModal.vue"
import {computed, nextTick, onMounted, reactive, ref, watch} from "vue"
import {useChatStore} from "@/stores/chatStore"
import {storeToRefs} from "pinia"
import {useUserStore} from "@/stores/userStore"
import {modals, sendNotify} from "@/app"
import PageTitle from "@/Components/Layout/PageTitle.vue"

const chatStore = useChatStore()
const {filterChats, currentChat, searchString} = storeToRefs(chatStore)
const userStore = useUserStore()
const {user} = storeToRefs(userStore)

const defaultMessage = () => ({
    message: '',
    files: []
})

const form = reactive(defaultMessage())

const messageImages = ref([])
const messageImageIndex = ref(0)

onMounted(() => {
    chatStore.getChats()
    if (route().params.chatId !== undefined)
        chatStore.getChat(route().params.chatId)
    if (user.value.isAuthorized) {
        Echo.private('chat.' + user.value.id)
            .listenToAll((event, data) => {
                switch (event) {
                    case 'SeenMessageEvent' : {
                        chatStore.makeSeenMessage(data.chatId, data.messageId)
                        break;
                    }
                    case 'SeenAllMessagesEvent' : {
                        chatStore.updateChat(data.chatId)
                        break;
                    }
                }
            });
    }

    document.getElementById('chatSearch').addEventListener("click", (e) => {
        e.target.setAttribute('type', 'text')
    }, {once: true})
})

const messageDates = computed(() => {
    return currentChat.value.data === null ? [] : new Set(currentChat.value.data.messages.map(message => message.date))
})

const messagesByDate = (date) => {
    return currentChat.value.data.messages.filter(message => message.date === date)
}


const sendMessage = async () => {
    await chatStore.sendMessage(form).then((response) => {
        Object.assign(form, defaultMessage())
    })
}

const handleFileChange = async (evt) => {
    if (evt.target.files.length > 0) {
        if (evt.target.files.length + form.files.length <= 10) {
            let count = 0
            Array.prototype.slice.call(form.files).concat(Array.prototype.slice.call(evt.target.files)).forEach((file) => {
                if (/(image\/)/g.test(file.type))
                    count += 1;
            })
            if (count <= 9) {
                Object.entries(evt.target.files).forEach(([key, value]) => {
                    form.files.push(value)
                    nextTick(() => {
                        document.getElementById('messageFile-' + key).setAttribute('href', window.URL.createObjectURL(value))
                    })
                })
            } else
                sendNotify('Максимальное количество прикреплённых изображений - 9!', 'error')
        } else
            sendNotify('Максимальное количество прикреплённых файлов - 10! Вы ещё можете прикрепить ' + (10 - form.files.length), 'error')
    }
    evt.target.value = ''
}

const openGallery = async (images, index) => {
    messageImages.value = images
    messageImageIndex.value = index
    await nextTick(() => {
        let gallery = document.getElementById('gallery');
        modals.getOrCreateInstance(gallery).show()
        gallery.addEventListener('hidden.bs.modal', (event) => {
            messageImages.value = []
            messageImageIndex.value = 0
        }, {once: true})
    })
}

const scrollBottomAndSeenNewMessages = (id) => {
    let element = document.getElementById('current-chat-body-' + id)
    element.scroll({
        top: element.scrollHeight,
        behavior: 'smooth'
    });
    currentChat.value.data.unseenMessageCount = 0
}

const checkScroll = computed(() => {
    let chat = document.getElementById('current-chat-body-'+currentChat.value.data.id)
    return chat !== null && chat.offsetWidth > chat.clientWidth
})

</script>
<template>
    <PageTitle title="Чаты"/>
    <div class="container p-0 z-10">
        <div class="w-100 row m-0 d-flex flex-wrap justify-content-center mb-5">
            <div class="col-md-5 col-lg-4 col-12 chat">
                <div class="card mb-sm-3 mb-md-0 contacts_card">
                    <div class="card-header">
                        <div class="input-group">
                            <input type="password" placeholder="Поиск..." autocomplete="off"
                                   readonly onfocus="this.removeAttribute('readonly')"
                                   name="chatSearch" id="chatSearch" class="form-control chat-search" v-model="searchString">
                        </div>
                    </div>
                    <div class="card-body contacts_body">
                        <ul class="contacts">
                            <li class="active" v-for="chat in filterChats" @click="chatStore.getChat(chat.id)">
                                <div class="d-flex cursor-pointer bd-highlight">
                                    <div class="img_cont">
                                        <img :src="chat.interlocutor.image"
                                             class="rounded-circle object-fit-cover user_img">
                                    </div>
                                    <div class="user_info text-truncate">
                                        <span>{{ chat.interlocutor.name }}</span>
                                        <p class="text-truncate" v-if="chat.lastMessage === null">В чате пока
                                            нет сообщений</p>
                                        <template v-else>
                                            <div class="d-flex gap-2 align-items-center">
                                                <p class="text-truncate">
                                                    <strong v-if="chat.lastMessage.isUserMessage">Вы: </strong>
                                                    {{
                                                        chat.lastMessage.message === null ? 'Вложения' : chat.lastMessage.message
                                                    }}
                                                </p>
                                                <template v-if="chat.lastMessage.isUserMessage">
                                                    <p v-if="!chat.lastMessage.isSeen">
                                                        <font-awesome-icon icon="fa-solid fa-check" size="lg"/>
                                                    </p>
                                                    <p v-else>
                                                        <font-awesome-icon icon="fa-solid fa-check-double" size="lg"/>
                                                    </p>
                                                </template>
                                                <div
                                                    class="message-counter d-flex justify-content-center align-items-center"
                                                    v-if="chat.unseenMessageCount > 0 && !chat.lastMessage.isUserMessage">
                                                    <p class="text-white">{{ chat.unseenMessageCount }}</p>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
            <div class="col-md-7 col-lg-8 col-12 chat">
                <div class="card overflow-auto"
                     :class="currentChat.data !== null ? '' : 'justify-content-center'">
                    <template v-if="currentChat.data !== null">
                        <div class="card-header msg_head">
                            <div class="d-flex bd-highlight">
                                <div class="img_cont">
                                    <img :src="currentChat.data.interlocutor.image"
                                         class="rounded-circle object-fit-cover user_img">
                                </div>
                                <div class="user_info">
                                    <span>{{ currentChat.data.interlocutor.name }}</span>
                                    <p>{{ currentChat.data.messageCount }} сообщений</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body msg_card_body"
                             :id="'current-chat-body-'+currentChat.data.id"
                             :class="currentChat.data.messages.length > 0 ? '' : 'd-flex flex-column justify-content-center'">
                            <template v-if="currentChat.data.messages.length > 0">
                                <div v-if="currentChat.data.unseenMessageCount > 0 && checkScroll"
                                     class="cursor-pointer position-absolute bottom-0 end-0 z-10"
                                     style="transform: translate(-50%, -200%);"
                                    @click="scrollBottomAndSeenNewMessages(currentChat.data.id)">
                                    <div class="img_cont_msg">
                                        <div class="d-flex flex-column justify-content-center align-items-center rounded-circle bg-danger h-100 fw-bold">
                                            <p class="text-white lh-1" style="font-size: 12px">{{ currentChat.data.unseenMessageCount }}</p>
                                            <font-awesome-icon class="text-white"
                                                               icon="fa-solid fa-chevron-down" beat size="lg"/>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column" v-for="date in messageDates">
                                    <div class="d-flex justify-content-center mb-3">{{ date }}</div>
                                    <div v-for="message in messagesByDate(date)"
                                         class="d-flex justify-content-start mb-4"
                                         :class="message.isUserMessage ? 'flex-row-reverse' : ''">
                                        <div class="img_cont_msg">
                                            <img
                                                :src="message.isUserMessage ? currentChat.data.user.image : currentChat.data.interlocutor.image"
                                                class="rounded-circle object-fit-cover user_img_msg">
                                        </div>
                                        <div
                                            :id="'message-'+message.id"
                                            :class="message.isUserMessage ? 'msg_container_send' : 'msg_container'">
                                            <div
                                                class="file-container d-flex flex-wrap gap-2 justify-content-center"
                                                :class="message.message !== null ? 'mb-2' : ''"
                                                v-if="message.files.length > 0 || message.images.length > 0">
                                                <div
                                                    class="d-flex flex-wrap gap-1 justify-content-center"
                                                    v-if="message.images.length > 0">
                                                    <div class="col-auto"
                                                         v-for="(image, index) in message.images">
                                                        <img v-lazy="image" @click="openGallery(message.images, index)"
                                                             class="cursor-pointer img-fluid rounded image-container">
                                                    </div>
                                                </div>
                                                <div class="col-12 d-flex flex-wrap gap-2"
                                                     v-if="message.files.length > 0">
                                                    <div class="col-auto overflow-hidden file-link-container"
                                                         v-for="file in message.files">
                                                        <a class="d-flex gap-2 align-items-center text-break
                                                            justify-content-between link" :href="file.path"
                                                            target="_blank">
                                                            <p>{{ file.name }}</p>
                                                            <font-awesome-icon icon="fa-solid fa-download"/>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <p v-if="message.message !== null">{{ message.message }}</p>
                                            <div class="d-flex gap-2"
                                                 :class="message.isUserMessage ? 'msg_time_send' : 'msg_time'">
                                                <span>{{ message.time }}</span>
                                                <template v-if="message.isUserMessage">
                                                    <span v-if="!message.isSeen">
                                                        <font-awesome-icon icon="fa-solid fa-check" size="lg"/>
                                                    </span>
                                                    <span v-else>
                                                        <font-awesome-icon icon="fa-solid fa-check-double" size="lg"/>
                                                    </span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <p v-else class="text-center">В чате пока нет сообщений</p>
                        </div>
                        <div class="card-footer">
                            <div class="input-group">
                                <input type='file' id="actual-btn" @change="handleFileChange($event)" multiple hidden/>
                                <label class="input-group-text attach_btn" for="actual-btn">
                                    <font-awesome-icon icon="fa-solid fa-paperclip"/>
                                </label>
                                <textarea name="message" class="form-control type_msg" v-model="form.message"
                                          placeholder="Напишите ваше сообщение..."></textarea>
                                <button class="input-group-text send_btn" @click="sendMessage"
                                        :disabled="form.message === null && form.files.length === 0">
                                    <font-awesome-icon icon="fa-solid fa-location-arrow"/>
                                </button>
                            </div>
                        </div>
                        <div class="d-flex flex-column fixed-height-min-max-125px" v-if="form.files.length > 0">
                            <ul class="list-group overflow-x-hidden bg-white">
                                <li class="list-group-item d-flex justify-content-between align-items-center bg-white px-4"
                                    v-for="(file, index) in form.files">
                                    <a :id="'messageFile-'+index" href="#" target="_blank">{{ file.name }}</a>
                                    <span @click="form.files.splice(index, 1)">
                                        <font-awesome-icon class="cursor-pointer"
                                                           icon="fa-solid fa-location-arrow" size="lg"/>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </template>
                    <p v-else class="text-center">Выберите кому хотели бы написать</p>
                </div>
            </div>
        </div>
    </div>
    <GalleryModal v-if="messageImages.length > 0" :images="messageImages" :active="messageImageIndex"></GalleryModal>
</template>
