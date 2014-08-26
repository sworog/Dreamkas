Meta:
@smoke
@precondition
@sprint_40
@us_103
@us_104
@us_105
@id_s40u103typeFilters1
@id_s40u103typeFilters2
@id_s40u103typeFilters3
@id_s40u103typeFilters4
@id_s40u103typeFilters5
@id_s40u103filterDates1
@id_s40u103filterDates2
@id_s40u103filterDates3

Scenario: Создание пользователя, магазина и ассортимента

Given пользователь запускает консольную команду для создания пользователя с параметрами: адрес электронной почты 'stockMovement@lighthouse.pro' и пароль 'lighthouse'

Given пользователь с адресом электронной почты 'stockMovement@lighthouse.pro' создает магазин с именем 'stockMovement-store1' и адресом 'stockMovement-store1'

Given пользователь с адресом электронной почты 'stockMovement@lighthouse.pro' создает поставщика с именем 'stockMovement-supplier1', адресом 'address', телефоном 'phone', почтой 'email' и контактным лицом 'contactPerson'

Given пользователь с адресом электронной почты 'stockMovement@lighthouse.pro' создает группу с именем 'stockMovement-group1'
And пользователь с адресом электронной почты 'stockMovement@lighthouse.pro' создает продукт с именем 'stockMovement-product1', еденицами измерения 'шт.', штрихкодом 'stockMovementBarcode1', НДС '0', ценой закупки '100' и ценой продажи '110' в группе с именем 'stockMovement-group1'

Given пользователь с адресом электронной почты 'stockMovement@lighthouse.pro' создает магазин с именем 'stockMovement-store2' и адресом 'stockMovement-store2'

Given пользователь с адресом электронной почты 'stockMovement@lighthouse.pro' создает группу с именем 'stockMovement-group2'
And пользователь с адресом электронной почты 'stockMovement@lighthouse.pro' создает продукт с именем 'stockMovement-product2', еденицами измерения 'Пятюня', штрихкодом 'stockMovementBarcode2', НДС '0', ценой закупки '125,5' и ценой продажи '110' в группе с именем 'stockMovement-group2'