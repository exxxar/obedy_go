<script setup>
import Modal from '@/Components/Basic/Modal.vue'
import CallbackForm from "@/Components/Callback/CallbackForm.vue"

</script>

<template>
    <Modal id="callback" title="Обратная связь">
        <template #body>
            <div class="row">
                <div class="col-12">
                    <CallbackForm :selected-type="1"></CallbackForm>
                </div>
            </div>
        </template>
    </Modal>
</template>
