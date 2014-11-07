Meta:
@smoke
@precondition
@sprint_43
@us_115.1

Scenario: Сценарий для создания магазина c товаром через консольную команду

Given пользователь с адресом электронной почты 'reports@lighthouse.pro' создает магазин с именем 'store-reports' и адресом 'адрес'
Given пользователь с адресом электронной почты 'reports@lighthouse.pro' создает группу с именем 'reports-group1'
And пользователь с адресом электронной почты 'reports@lighthouse.pro' создает продукт с именем 'reports-product1', еденицами измерения 'шт.', штрихкодом 'reports-barcode-1', НДС '0', ценой закупки '100' и ценой продажи '110' в группе с именем 'reports-group1'