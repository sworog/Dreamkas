Meta:
@precondition

Scenario: Создание магазина, поставщика и товара

Given пользователь с адресом электронной почты 'user@lighthouse.pro' создает группу с именем 'user-group1'
And пользователь с адресом электронной почты 'user@lighthouse.pro' создает продукт с именем 'user-product1', еденицами измерения 'шт.', штрихкодом 'post-barcode-1', НДС '0', ценой закупки '100' и ценой продажи '110' в группе с именем 'user-group1'

Given пользователь с адресом электронной почты 'user@lighthouse.pro' создает магазин с именем 'store-user-delete' и адресом 'адрес'
Given пользователь с адресом электронной почты 'user@lighthouse.pro' создает поставщика с именем 'user-supplier-delete', адресом 'address', телефоном 'phone', почтой 'email' и контактным лицом 'contactPerson'
