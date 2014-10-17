Meta:
@us_113.1
@sprint_42

Narrative:
Как владелец,
Я хочу авторизоваться в Dreamkas android,
Чтобы начать работать с приложением

Scenario: Пользователь успешно авторизуется в системе

Meta:

GivenStories: precondition/ресет_и_перезапуск_приложения.story,
              precondition/создание_пользователя.story

Given пользователь авторизируется в системе используя адрес электронной почты 'androidpos@lighthouse.pro' и пароль 'lighthouse'

Then пользователь проверяет, что заголовок 'Смена магазина'

Scenario: Пользователь проверяет описание на экране авторизации

Meta:

GivenStories: precondition/ресет_и_перезапуск_приложения.story,
              precondition/создание_пользователя.story

Then пользователь проверяет, что описание 'Добро пожаловать в DreamKas.'

Scenario: Пользователь проверяет, что после авторизации находится в нужной активити

Meta:

GivenStories: precondition/ресет_и_перезапуск_приложения.story,
              precondition/создание_пользователя.story

Given пользователь авторизируется в системе используя адрес электронной почты 'androidpos@lighthouse.pro' и пароль 'lighthouse'

Then пользователь проверяет, что текущая активити это 'ru.dreamkas.pos.view.activities.MainActivity_'

Scenario: Отправление пустого поля емейл

Meta:
@skip
@ignore

!-- Cant inspect text field hint

Scenario: Отправление пустого поля пароль

Meta:
@skip
@ignore

!-- Cant inspect text field hint

Scenario: Отправление пустого поля пароль и емейл

Meta:
@skip
@ignore

!-- Cant inspect text field hint

Scenario: Авторизация с несуществующими кредами

Meta:
@skip
@ignore

!-- Appium cant handle toast message