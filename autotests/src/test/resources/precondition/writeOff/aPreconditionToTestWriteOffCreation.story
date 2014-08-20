Meta:
@smoke
@sprint_40
@us_103
@id_s40u103s2
@id_s40u103s5
@id_s40u103s7
@id_s40u103typeFilters1
@id_s40u103typeFilters2
@id_s40u103typeFilters3
@id_s40u103filterDates1
@id_s40u103filterDates2
@id_s40u103filterDates3

Scenario: Cценарий для создания списания

Given пользователь создает апи объект списания с датой '19.08.2014', магазином с именем 's40u103-store'
And пользователь добавляет к апи объекту списания продукт с именем 's40u103-product1', ценой '11.99', количеством '2' и причиной 'Бой'
And пользователь c электронным адресом 's40u103@lighthouse.pro' сохраняет апи объект списания