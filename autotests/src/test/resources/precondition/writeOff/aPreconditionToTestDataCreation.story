Meta:
@smoke
@sprint_40
@us_103
@id_s40u103InvoiceProductValidations0
@id_s40u103InvoiceProductValidations3
@id_s40u103s1
@id_s40u103s2
@id_s40u103s3
@id_s40u103s5
@id_s40u103s7
@id_s40u103typeFilters1
@id_s40u103typeFilters2
@id_s40u103typeFilters3

Scenario: Сценарий для подготовки тестовых данных

Given пользователь с адресом электронной почты 's40u103@lighthouse.pro' создает магазин с именем 's40u103-store' и адресом 's40u103-store'
And пользователь с адресом электронной почты 's40u103@lighthouse.pro' создает поставщика с именем 's40u103-supplier', адресом 'address', телефоном 'phone', почтой 'email' и контактным лицом 'contactPerson'
And пользователь с адресом электронной почты 's40u103@lighthouse.pro' создает группу с именем 's40u103-group'
And пользователь с адресом электронной почты 's40u103@lighthouse.pro' создает продукт с именем 's40u103-product1', еденицами измерения 'шт.', штрихкодом 's40u103barcode1', НДС '0', ценой закупки '100' и ценой продажи '110' в группе с именем 's40u103-group'