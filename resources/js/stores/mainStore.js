import { defineStore } from 'pinia'
import { reactive, ref, watch } from "vue"
import { router } from '@inertiajs/vue3'


export const useMainStore = defineStore('main', () => {

    const foodParts = reactive([
        {partId: 1, title: 'Стандарт', image: '/images/logo_obed_go.jpg', slug: 'standard',
            tip: 'Учитывая ключевые сценарии поведения, сплочённость команды профессионалов предопределяет высокую ' +
                'востребованность своевременного выполнения сверхзадачи. В частности, высокотехнологичная концепция ' +
                'общественного уклада выявляет срочную потребность новых принципов формирования ' +
                'материально-технической и кадровой базы. Господа, экономическая повестка сегодняшнего дня ' +
                'предполагает независимые способы реализации экономической целесообразности принимаемых решений!'},
        {partId: 2, title: 'Экспресс', image: '/images/logo_obed_go.jpg', slug: 'express',
            tip: 'Равным образом, глубокий уровень погружения создаёт предпосылки для экономической целесообразности ' +
                'принимаемых решений. Сложно сказать, почему предприниматели в сети интернет разоблачены. Лишь ' +
                'сделанные на базе интернет-аналитики выводы представлены в исключительно положительном свете.'},
        {partId: 3, title: 'Премиум', image: '/images/logo_obed_go.jpg', slug: 'premium',
            tip: 'Однозначно, базовые сценарии поведения пользователей будут описаны максимально подробно. ' +
                'В своём стремлении улучшить пользовательский опыт мы упускаем, что явные признаки победы ' +
                'институционализации превращены в посмешище, хотя само их существование приносит несомненную пользу ' +
                'обществу. Есть над чем задуматься: некоторые особенности внутренней политики представлены в ' +
                'исключительно положительном свете.'},
        {partId: 4, title: 'Собери сам', image: '/images/logo_obed_go.jpg', slug: 'self',
            tip: 'Современные технологии достигли такого уровня, что базовый вектор развития играет важную роль в ' +
                'формировании поставленных обществом задач. Кстати, акционеры крупнейших компаний, вне зависимости от ' +
                'их уровня, должны быть призваны к ответу. Разнообразный и богатый опыт говорит нам, что существующая ' +
                'теория в значительной степени обусловливает важность анализа существующих паттернов поведения.'},
        {partId: 5, title: 'Рецепты', image: '/images/logo_obed_go.jpg', slug: 'special',
            tip: 'Прежде всего, убеждённость некоторых оппонентов, а также свежий взгляд на привычные вещи — ' +
                'безусловно открывает новые горизонты для дальнейших направлений развития. Господа, синтетическое ' +
                'тестирование, в своём классическом представлении, допускает внедрение экономической целесообразности ' +
                'принимаемых решений. А также сторонники тоталитаризма в науке будут указаны как претенденты на роль ' +
                'ключевых факторов.'},
    ])

    const part = ref(0)

    watch(() => part.value, (newValue, oldValue) => {
        if (newValue !== oldValue && newValue !== null)
            selectPart()
    })

    function changePosition(isNext = true) {
        if (part.value === 0 || part.value === null) {
            part.value = 1
            return
        }
        if (isNext) {
            if (part.value === 5)
                part.value = 1
            else
                part.value++
        } else {
            if (part.value === 1)
                part.value = 5
            else
                part.value--
        }
    }

    function selectPart() {
        if(part.value === 0) {
            router.get(route('main'))
            return
        }
        let slug = foodParts.find(foodPart => foodPart.partId ===  part.value).slug ?? ''
        if(slug === 'self' || slug === 'special') {
            router.get(route(slug))
            return
        }
        if(slug === 'standard' || slug === 'express' || slug === 'premium') {
            router.get(route('products', slug))
        }
    }

    function switchFoodPart() {
        if (route().current('main'))
            part.value = 0
        else if (route().current('products'))
            part.value = foodParts.find(foodPart => foodPart.slug === route().params.foodPart).partId
        else if (route().current('self') || route().current('special'))
            part.value = foodParts.find(foodPart => foodPart.slug === route().current()).partId
        else
            part.value = null
    }

    return { foodParts, part, changePosition, selectPart, switchFoodPart }
})
