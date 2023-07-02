import {defineStore} from "pinia"
import axios from "axios"
import {sendNotify} from "@/app"
import {computed, nextTick, reactive, ref} from "vue"


export const useChatStore = defineStore('chat', () => {

    const currentChat = reactive({
        data: null
    })
    const currentChatId = ref(null)

    const searchString = ref('')

    const chats = ref([])

    const page = ref(1)

    let observerSeenMessage = null
    let observerGetMessages = null

    const filterChats = computed(() => {
        if(searchString.value !== '')
            return chats.value.filter(chat => chat.interlocutor.name.toLowerCase().indexOf(searchString.value.toLowerCase()) !== -1)
        return chats.value
    })

    const getChats = async () => {
        await axios.get(route('chats.get')).then(resp => {
            chats.value = resp.data

        }).catch(error => {
        })
    }

    const getChat = async (id) => {
        observerSeenMessage = null
        await axios.get(route('chat.messages', {id: id, page: page.value})).then(resp => {
            if (currentChat.data !== null && currentChat.data.id === id) {
                resp.data.messages.sort((a, b) => {
                    return parseInt(b.id) - parseInt(a.id);
                })
                resp.data.messages.forEach(message => {
                    let messageIndex = currentChat.data.messages.findIndex(mes => mes.id === message.id)
                    if (messageIndex === -1) {
                        currentChat.data.messages.unshift(message)
                    }
                })

                let element = document.getElementById('current-chat-body-' + currentChat.data.id)
                let prevScrollTop = element.scrollTop
                let prevScrollHeight = element.scrollHeight
                nextTick(() => {
                    let element = document.getElementById('current-chat-body-' + currentChat.data.id)
                    element.scrollTop = prevScrollTop + (element.scrollHeight - prevScrollHeight)
                })
            } else {
                resp.data.messages.sort((a, b) => {
                    return parseInt(a.id) - parseInt(b.id);
                })
                currentChat.data = resp.data
                currentChatId.value = id
                page.value = 1
                nextTick(() => {
                    let element = document.getElementById('current-chat-body-' + currentChat.data.id)
                    element.scrollTop = element.scrollHeight
                    let chatIndex = chats.value.findIndex(chat => chat.id === currentChatId.value)
                    if (chatIndex !== -1) {
                        chats.value[chatIndex].unseenMessageCount = 0
                        currentChat.data.unseenMessageCount = 0
                    }
                })
            }
            if (currentChat.data.messages.length > 0)
                chatObserveGetMessage(currentChat.data.messages[0].id)

        }).catch(error => {
            if (error.response.status === 403)
                sendNotify('Вы не имеете доступа к выбранному чату', 'error')
            if (error.response.status === 404)
                sendNotify('Выбранный чат не найден', 'error')
        })
    }

    const sendMessage = async (form) => {
        let formData = new FormData()
        formData.append('chatId', currentChatId.value)
        formData.append('message', form.message)
        for (let i = 0; i < form.files.length; i++) {
            formData.append('files[' + i + ']', form.files[i])
        }
        await axios.post(route('send.message'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            },
        }).then(resp => {
            newMessage(resp.data)

            nextTick(() => {
                let element = document.getElementById('current-chat-body-' + currentChatId.value)
                element.scrollTop = element.scrollHeight
            })

            return Promise.resolve(resp);
        }).catch(error => {
            sendNotify('Произошла ошибка при отправке сообщения', 'error')
        })
    }

    const newMessage = async (chatData) => {
        let chatIndex = chats.value.findIndex(chat => chat.id === chatData.id)
        if (chatIndex !== -1) {
            chats.value[chatIndex] = chatData
        }else{
            chats.value.unshift(chatData)
        }
        if (currentChat.data !== null && currentChat.data.id === chatData.id) {
            if (currentChat.data.messages.findIndex(message => message.id === chatData.lastMessage.id) === -1) {
                currentChat.data.messages.push(chatData.lastMessage)
                currentChat.data.messageCount +=1
                if (!chatData.lastMessage.isUserMessage) {
                    await chatObserveSeenMessage(chatData.lastMessage.id)
                    currentChat.data.unseenMessageCount +=1
                }
            }
        }

    }

    const updateChat = (chatId) => {
        if (currentChat.data !== null && currentChat.data.id === chatId) {
            currentChat.data.messages.forEach(message => {
                message.isSeen = true
            })
        }
    }

    const makeSeenMessage = (chatId, messageId) => {
        let chatIndex = chats.value.findIndex(chat => chat.id === chatId)
        if (chatIndex !== -1 && chats.value[chatIndex].lastMessage !== null) {
            if (chats.value[chatIndex].lastMessage.id === messageId)
                chats.value[chatIndex].lastMessage.isSeen = true
        }
        if (currentChat.data !== null && currentChat.data.id === chatId) {
            let messageIndex = currentChat.data.messages.findIndex(item => item.id === parseInt(messageId))
            currentChat.data.messages[messageIndex].isSeen = true
        }
    }

    const chatObserveSeenMessage = async (lastMessageId) => {
        if (observerSeenMessage === null) {
            observerSeenMessage = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = entry.target
                        let messageId = target.id.split("-")[1]
                        let messageIndex = currentChat.data.messages.findIndex(item => item.id === parseInt(messageId))
                        if (!currentChat.data.messages[messageIndex].isSeen) {
                            let chatIndex = chats.value.findIndex(chat => chat.id === currentChat.data.id)
                            if (chatIndex !== -1) {
                                if (chats.value[chatIndex].unseenMessageCount > 1) {
                                    chats.value[chatIndex].unseenMessageCount -= 1
                                    currentChat.data.unseenMessageCount -= 1
                                } else {
                                    chats.value[chatIndex].unseenMessageCount = 0
                                    currentChat.data.unseenMessageCount = 0
                                }
                            }
                            axios.put(route('seen.message', {id: messageId}))
                                .then((resp) => {
                                    currentChat.data.messages[messageIndex].isSeen = true
                                })
                        }
                        observer.unobserve(target)
                    }
                })
            }, {
                root: null,
                rootMargin: '0px',
                threshold: 0.5
            })
        }
        await nextTick(() => {
            const el = document.getElementById('message-' + lastMessageId)
            if (el !== null)
                observerSeenMessage.observe(el)
        })
    }

    const chatObserveGetMessage = async (firstMessageId) => {
        if (observerGetMessages === null) {
            observerGetMessages = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const target = entry.target
                        if (currentChat.data.messages.length < currentChat.data.messageCount) {
                            page.value += 1
                            getChat(currentChatId.value)
                        }
                        observer.unobserve(target)
                    }
                })
            }, {
                root: null,
                rootMargin: '0px',
                threshold: 0.8
            })
        }
        await nextTick(() => {
            const el = document.getElementById('message-' + firstMessageId)
            if (el !== null)
                observerGetMessages.observe(el)
        })
    }

    return {
        chats, filterChats, searchString, currentChat, getChats, getChat, sendMessage, newMessage, updateChat, makeSeenMessage
    }
})
