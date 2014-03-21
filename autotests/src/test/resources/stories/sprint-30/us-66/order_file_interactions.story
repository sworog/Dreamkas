Meta:
@sprint_30
@us_66
@order

Narrative:
As a заведующий отделом
I want to скачать файл с заказом,
In order оправить его по электронной почте поставщику или распечатать

Scenario: Order file link is clickable

Meta:
@id_s30u66s1
@smoke

GivenStories: precondition/sprint-30/us-66/aPreconditionToStoryUs66.story

Given there is the order in the store by 'departmentManager-s30u66'
And the user opens last created order page
And the user logs in using 'departmentManager-s30u66' userName and 'lighthouse' password

Then the user checks the download file link is clickable

Scenario: No order file download link in create order page

Meta:
@id_s30u66s2

GivenStories: precondition/sprint-30/us-66/aPreconditionToStoryUs66.story

Given there is the order in the store by 'departmentManager-s30u66'
And the user opens last created order page
And the user logs in using 'departmentManager-s30u66' userName and 'lighthouse' password

Then the user checks the download file link is not visible

Scenario: Order file verify data with one product

Meta:
@id_s30u66s3
@smoke

GivenStories: precondition/sprint-30/us-66/aPreconditionToStoryUs66.story

Given there is the order in the store by 'departmentManager-s30u66'
And the user opens last created order page
And the user logs in using 'departmentManager-s30u66' userName and 'lighthouse' password

Then the user checks the downloaded file contains required data by user with name 'departmentManager-s30u66'
