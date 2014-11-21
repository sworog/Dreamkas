Meta:
@us_130

Narrative:
Как владелец магазина,
Я хочу видеть предупреждение, закрывая форму приёмки, оприходования, списания, возврата поставщику, создания товара, создания поставщика, создания магазина, о том, что введённые данные не будут сохранены,
Чтобы точно понимать последствия своих действий

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Scenario: Появление сообщения о потери данных при редактировании магазина при изменении поля наименование

Meta:
@smoke

GivenStories: precondition/пользователь/создание_юзера.story,
              precondition/магазин/создание_магазина.story

Given пользователь открывает страницу со списком магазинов
And пользователь авторизуется в системе используя адрес электронной почты 'user@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на магазин с именем 'store-user'
And пользователь* находится в модальном окне 'редактирования магазина'
And пользователь* вводит значение 'text' в поле с именем 'name'

Then пользователь закрывает модальное окно по кнопке крестик и проверяет, что текст алерта гласит 'Изменения не будут сохранены. Отменить изменения?'

Scenario: Появление сообщения о потери данных при редактировании магазина при изменении поля адресс

Meta:

GivenStories: precondition/пользователь/создание_юзера.story,
              precondition/магазин/создание_магазина.story

Given пользователь открывает страницу со списком магазинов
And пользователь авторизуется в системе используя адрес электронной почты 'user@lighthouse.pro' и пароль 'lighthouse'

When пользователь нажимает на магазин с именем 'store-user'
And пользователь* находится в модальном окне 'редактирования магазина'
And пользователь* вводит значение 'text' в поле с именем 'address'

Then пользователь закрывает модальное окно по кнопке крестик и проверяет, что текст алерта гласит 'Изменения не будут сохранены. Отменить изменения?'