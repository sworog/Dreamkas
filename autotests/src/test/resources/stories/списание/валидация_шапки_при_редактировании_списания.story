Meta:
@sprint_40
@us_103

Narrative:
Как владелец,
Я хочу создавать, редактировать и удалять списания товаров в магазинах,
Чтобы остатки себестоимости товаров в учетной системе соответствовали действительности

Scenario: Валидация поле дата при редактировании списания - пустое значение

Meta:
@id

GivenStories:
              precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story

Given пользователь создает апи объект списания с датой '19.08.2014', магазином с именем 's39u102-store'
And пользователь добавляет к апи объекту списания продукт с именем 's39u102-product1', ценой '11.99', количеством '2' и причиной 'Бой'
And пользователь c электронным адресом 's39u102@lighthouse.pro' сохраняет апи объект списания

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'