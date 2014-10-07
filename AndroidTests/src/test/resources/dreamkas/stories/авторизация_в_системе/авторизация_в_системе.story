Meta:
@us_113.1
@sprint_42

Narrative:
Как владелец,
Я хочу авторизоваться в Dreamkas android,
Чтобы начать работать с приложением

Scenario: Пользователь успешно авторизуется в системе

Meta:

Given пользователь авторизируется в системе используя адрес электронной почты 'owner@lighthouse.pro' и пароль 'lighthouse'

Then пользователь проверяет, что заголовок 'Dreamkas'

Scenario: Пользователь проверяет описание на экране авторизации

Meta:

Then пользователь проверяет, что описание 'Добро пожаловать в DreamKas.'

Scenario: Пользователь проверяет, что после авторизации находится в нужной активити

Meta:

Given пользователь авторизируется в системе используя адрес электронной почты 'owner@lighthouse.pro' и пароль 'lighthouse'

Then пользователь проверяет, что текущая активити это 'ru.dreamkas.pos.view.LighthouseDemoActivity_'

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