<script setup>
import BaseLayout from "@/Layouts/BaseLayout.vue"
import GalleryModal from "@/Components/Modals/GalleryModal.vue"

</script>
<template>
    <BaseLayout>
        <template #content>
            <div class="w-100 row m-0 d-flex flex-wrap justify-content-center mb-5">
                <div class="col-md-4 col-xl-3 chat"><div class="card mb-sm-3 mb-md-0 contacts_card">
                    <div class="card-header">
                        <div class="input-group">
                            <input type="text" placeholder="Search..." autocomplete="off"
                                   name="chatSearch" id="chatSearch" class="form-control chat-search">
                            <span class="input-group-text search_btn"><i class="fas fa-search"></i></span>
                        </div>
                    </div>
                    <div class="card-body contacts_body">
                        <ul class="contacts">
                            <li class="active">
                                <div class="d-flex bd-highlight">
                                    <div class="img_cont">
                                        <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png" class="rounded-circle user_img">
                                    </div>
                                    <div class="user_info">
                                        <span>jassa</span>
                                        <p>Kalid is online</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex bd-highlight">
                                    <div class="img_cont">
                                        <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png" class="rounded-circle user_img">
                                    </div>
                                    <div class="user_info">
                                        <span>jassa</span>
                                        <p>Taherah left 7 mins ago</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex bd-highlight">
                                    <div class="img_cont">
                                        <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png" class="rounded-circle user_img">
                                    </div>
                                    <div class="user_info">
                                        <span>jassa Mann</span>
                                        <p>Sami is online</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex bd-highlight">
                                    <div class="img_cont">
                                        <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png" class="rounded-circle user_img">
                                    </div>
                                    <div class="user_info">
                                        <span>jassa Mann</span>
                                        <p>Nargis left 30 mins ago</p>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="d-flex bd-highlight">
                                    <div class="img_cont">
                                        <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png" class="rounded-circle user_img">
                                    </div>
                                    <div class="user_info">
                                        <span>jassa Mann</span>
                                        <p>Rashid left 50 mins ago</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer"></div>
                </div></div>
                <div class="col-md-8 col-xl-6 chat">
                    <div class="card">
                        <div class="card-header msg_head">
                            <div class="d-flex bd-highlight">
                                <div class="img_cont">
                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png" class="rounded-circle user_img">
                                </div>
                                <div class="user_info">
                                    <span>Chat with jassa</span>
                                    <p>1767 Messages</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body msg_card_body">
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-center mb-3">04.06.2023</div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png" class="rounded-circle user_img_msg">
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
                                        <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png" class="rounded-circle user_img_msg">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-center mb-3">05.06.2023</div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png" class="rounded-circle user_img_msg">
                                    </div>
                                    <div class="msg_cotainer">
                                        <div class="file-container d-flex flex-wrap gap-2 justify-content-center mb-2">
                                            <div class="col-12 d-flex flex-wrap gap-1 justify-content-center">
                                                <div class="col-32 ratio-4x3">
                                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png"
                                                         class="img-fluid img-thumbnail rounded"
                                                         data-bs-toggle="modal" data-bs-target="#gallery">
                                                </div>
                                                <div class="col-32 ratio-4x3">
                                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar1.png"
                                                         class="img-fluid img-thumbnail rounded"
                                                         data-bs-toggle="modal" data-bs-target="#gallery">
                                                </div>
                                                <div class="col-32 ratio-4x3">
                                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar1.png"
                                                         class="img-fluid img-thumbnail rounded"
                                                         data-bs-toggle="modal" data-bs-target="#gallery">
                                                </div>
                                                <div class="col-32 ratio-4x3">
                                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar1.png"
                                                         class="img-fluid img-thumbnail rounded">
                                                </div>
                                                <div class="col-32 ratio-4x3">
                                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar1.png"
                                                         class="img-fluid img-thumbnail rounded">
                                                </div>
                                                <div class="col-32 ratio-4x3">
                                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar1.png"
                                                         class="img-fluid img-thumbnail rounded">
                                                </div>
                                                <div class="col-32 ratio-4x3">
                                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar1.png"
                                                         class="img-fluid img-thumbnail rounded">
                                                </div>
                                                <div class="col-32 ratio-4x3">
                                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar1.png"
                                                         class="img-fluid img-thumbnail rounded">
                                                </div>
                                                <div class="col-32 ratio-4x3">
                                                    <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar1.png"
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
                                        <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png" class="rounded-circle user_img_msg">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png" class="rounded-circle user_img_msg">
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
                                        <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png" class="rounded-circle user_img_msg">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-start mb-4">
                                    <div class="img_cont_msg">
                                        <img src="https://therichpost.com/wp-content/uploads/2020/06/avatar2.png" class="rounded-circle user_img_msg">
                                    </div>
                                    <div class="msg_cotainer">
                                        Bye, see you
                                        <span class="msg_time">9:12 AM, Today</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="input-group">
                                <span class="input-group-text attach_btn"><i class="fas fa-paperclip"></i></span>
                                <textarea name="" class="form-control type_msg" placeholder="Type your message..."></textarea>
                                <span class="input-group-text send_btn"><i class="fas fa-location-arrow"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <GalleryModal></GalleryModal>
        </template>
    </BaseLayout>
</template>
