<script setup>
import Modal from '@/Components/Basic/Modal.vue'
</script>

<template>
    <Modal id="delivery" title="Условия доставки">
        <template #body>
            <ul class="about-list">
                <li>Доставка осуществляется с 12:00 до 14:00 только в будние дни.</li>
                <li>Блюда из разделов "стандарт" и "премиум" заказываются ЗА ДЕНЬ. Предзаказ на понедельник
                    также доступен и в выходные дни.
                </li>
                <li>Блюда из раздела "экспресс" заказываются и доставляются день в день.</li>
                <li>Дополнительные блюда заказываются за сутки!</li>
            </ul>
        </template>
    </Modal>
</template>
