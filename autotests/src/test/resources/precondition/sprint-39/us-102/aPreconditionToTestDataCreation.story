Meta:
@smoke
@precondition
@sprint_40
@us_102
@id_s39u102s1
@id_s39u102s2
@id_s39u102s3
@id_s39u102s6
@id_s39u102s9
@id_s39u102s10
@id_s39u102s12
@id_s39u102s13
@id_s39u102s14
@id_s39u102s15
@id_s39u102s16
@id_s39u102s17
@id_s39u102regress1
@id_s39u102s18
@id_s39u102s19
@id_s39u102s20
@id_s39u102invoiceProductCreateValidation1
@id_s39u102invoiceProductCreateValidation2
@id_s39u102invoiceProductCreateValidation3
@id_s39u102invoiceProductCreateValidation4
@id_s39u102invoiceProductCreateValidation5
@id_s39u102invoiceProductCreateValidation6
@id_s39u102invoiceProductCreateValidation7
@id_s39u102invoiceProductCreateValidation8
@id_s39u102invoiceProductCreateValidation9
@id_s40u102InvoiceProductValidations0
@id_s40u102InvoiceProductValidations3
@id_s40u102InvoiceProductValidations4

Scenario: Сценарий для подготовки тестовых данных

Given пользователь с адресом электронной почты 's39u102@lighthouse.pro' создает магазин с именем 's39u102-store' и адресом 's39u102-store'
And пользователь с адресом электронной почты 's39u102@lighthouse.pro' создает поставщика с именем 's39u102-supplier', адресом 'address', телефоном 'phone', почтой 'email' и контактным лицом 'contactPerson'
And пользователь с адресом электронной почты 's39u102@lighthouse.pro' создает группу с именем 's39u102-group'
And пользователь с адресом электронной почты 's39u102@lighthouse.pro' создает продукт с именем 's39u102-product1', еденицами измерения 'шт.', штрихкодом 's39u102barcode1', НДС '0', ценой закупки '100' и ценой продажи '110' в группе с именем 's39u102-group'