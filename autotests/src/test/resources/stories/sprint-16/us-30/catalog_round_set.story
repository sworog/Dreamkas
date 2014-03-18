Meta:
@sprint_16
@us_30

Narrative:
As a как коммерческий директор
I want to установить правило округления розничных цен для группы, категорий и подкатегорий,
In order to управлять политикой цен торговой сети

Scenario: Default values group

Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user creates new group with name 'Group-roundings0'
And the user clicks on the group name 'Group-roundings0'
And the user switches to 'group' properties tab
Then the user checks the price roundings dropdawn default selected value is 'до копеек'

Scenario: Default values category

Given there is the group with name 'Group-roundings'
And the user navigates to the group with name 'Group-roundings'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user clicks create new category button
And the user inputs 'Category-roundings0' in 'name' field of pop up
And the user clicks the create new category button in pop up
And the user clicks on the category name 'Category-roundings0'
And the user switches to 'category' properties tab
Then the user checks the price roundings dropdawn default selected value is 'до копеек'

Scenario: Default values subCategory

Given there is the category with name 'Category-roundings' related to group named 'Group-roundings'
And the user navigates to the category with name 'Category-roundings' related to group named 'Group-roundings'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user creates new subCategory with name 'subCategory-roundings0'
And the user clicks on the subCategory name 'subCategory-roundings0'
And the user switches to 'subCategory' properties tab
Then the user checks the price roundings dropdawn default selected value is 'до копеек'

Scenario: Create new category from group with setted roundings

Given there is the group with name 'Group-roundings1'
And the user navigates to the group with name 'Group-roundings1'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'group' properties tab
And the user set price roundings to 'до рублей'
And the user clicks save mark up button
When the user switches to 'group' main tab
And the user clicks create new category button
And the user inputs 'Category-roundings1' in 'name' field of pop up
And the user clicks the create new category button in pop up
And the user clicks on the category name 'Category-roundings1'
When the user switches to 'category' properties tab
Then the user checks the price roundings dropdawn default selected value is 'до рублей'
When the user clicks on end edition link and ends the edition

Scenario: Create new subCategory from category with setted roundings

Given there is the category with name 'Category-roundings2' related to group named 'Group-roundings2'
And the user navigates to the category with name 'Category-roundings2' related to group named 'Group-roundings2'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'category' properties tab
And the user set price roundings to 'до рублей'
And the user clicks save mark up button
When the user switches to 'category' main tab
And the user creates new subCategory with name 'subCategory-roundings2'
And the user clicks on the subCategory name 'subCategory-roundings2'
And the user switches to 'subCategory' properties tab
Then the user checks the price roundings dropdawn default selected value is 'до рублей'
When the user clicks on end edition link and ends the edition

Scenario: Group round set

Given there is the group with name 'Group-roundings'
And the user navigates to the group with name 'Group-roundings'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'group' properties tab
And the user set price roundings to <value>
And the user clicks save mark up button
Then the user sees success message 'Свойства успешно сохранены'

Examples:
| value |
| до копеек |
| до рублей |
| до 10 копеек |
| до 50 копеек |
| до 99 копеек |

Scenario: Category round set

Given there is the category with name 'Category-roundings' related to group named 'Group-roundings'
And the user navigates to the category with name 'Category-roundings' related to group named 'Group-roundings'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'category' properties tab
And the user set price roundings to <value>
And the user clicks save mark up button
Then the user sees success message 'Свойства успешно сохранены'

Examples:
| value |
| до копеек |
| до рублей |
| до 10 копеек |
| до 50 копеек |
| до 99 копеек |

Scenario: SubCategory round set

Given there is the subCategory with name 'subCategory-roundings' related to group named 'Group-roundings' and category named 'Category-roundings'
And the user navigates to the subCategory 'subCategory-roundings', category 'Category-roundings', group 'Group-roundings' product list page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'subCategory' properties tab
And the user set price roundings to <value>
And the user clicks save mark up button
Then the user sees success message 'Свойства успешно сохранены'

Examples:
| value |
| до копеек |
| до рублей |
| до 10 копеек |
| до 50 копеек |
| до 99 копеек |

Scenario: Group round checks after refresh

Given there is the group with name 'Group-roundings'
And the user navigates to the group with name 'Group-roundings'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'group' properties tab
And the user set price roundings to <value>
And the user clicks save mark up button
Then the user sees success message 'Свойства успешно сохранены'
When the user refreshes the current page
And the user switches to 'group' properties tab
Then the user checks the price rounding selected value is <value>

Examples:
| value |
| до копеек |
| до рублей |
| до 10 копеек |
| до 50 копеек |
| до 99 копеек |


Scenario: Category round checks after refresh

Given there is the category with name 'Category-roundings' related to group named 'Group-roundings'
And the user navigates to the category with name 'Category-roundings' related to group named 'Group-roundings'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'category' properties tab
And the user set price roundings to <value>
And the user clicks save mark up button
Then the user sees success message 'Свойства успешно сохранены'
When the user refreshes the current page
And the user switches to 'category' properties tab
Then the user checks the price rounding selected value is <value>

Examples:
| value |
| до копеек |
| до рублей |
| до 10 копеек |
| до 50 копеек |
| до 99 копеек |

Scenario: SubCategory round checks after refresh

Given there is the subCategory with name 'subCategory-roundings' related to group named 'Group-roundings' and category named 'Category-roundings'
And the user navigates to the subCategory 'subCategory-roundings', category 'Category-roundings', group 'Group-roundings' product list page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'subCategory' properties tab
And the user set price roundings to <value>
And the user clicks save mark up button
Then the user sees success message 'Свойства успешно сохранены'
When the user refreshes the current page
And the user switches to 'subCategory' properties tab
Then the user checks the price rounding selected value is <value>

Examples:
| value |
| до копеек |
| до рублей |
| до 10 копеек |
| до 50 копеек |
| до 99 копеек |