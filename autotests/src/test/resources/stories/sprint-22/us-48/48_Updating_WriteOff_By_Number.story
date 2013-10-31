Meta:
@sprint 22
@us 48

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Nothing found

Given there is the user with name 'departmentManager-SCPBC', position 'departmentManager-SCPBC', username 'departmentManager-SCPBC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SCPBC' managed by department manager named 'departmentManager-SCPBC'
And the user opens write off list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
And the user clicks the local navigation writeOff search link
And the user searches writeOff by number 'Янехочуискатьсписанияяхочуплатье'
And the user clicks the writeOff search buttton and starts the search
Then the user checks the form results text is 'Мы не смогли найти списание с номером Янехочуискатьсписанияяхочуплатье'

Scenario: WriteOff found by number

Given there is the user with name 'departmentManager-SCPBC', position 'departmentManager-SCPBC', username 'departmentManager-SCPBC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SCPBC' managed by department manager named 'departmentManager-SCPBC'
And there is the writeOff in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC' with values
| elementName | elementValue |
| number | SCPBC-10 |
| date | 02.04.2013 |
And the user opens write off list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
And the user clicks the local navigation writeOff search link
And the user searches writeOff by number 'SCPBC-10'
And the user clicks the writeOff search buttton and starts the search
Then the user checks the form results text is 'Нашлось списание'
And the user checks the writeOff with sku 'SCPBC-10' in search results
And the user checks the writeOff search result list contains entry
| number | date |
| SCPBC-10 | 02.04.2013 |
And the user checks writeOff highlighted text is 'Списание № SCPBC-10 от 02.04.2013'

Scenario: WriteOff with product found by number

Given there is the user with name 'departmentManager-SCPBC', position 'departmentManager-SCPBC', username 'departmentManager-SCPBC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SCPBC' managed by department manager named 'departmentManager-SCPBC'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'SCPBC-name-11' name, 'SCPBC-sku-11' sku, 'SCPBC-barcode-11' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And there is the writeOff in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC' with values
| elementName | elementValue |
| number | SCPBC-11 |
| date | 02.04.2013 |
And the user adds the product to the write off with number 'SCPBC-11' with sku 'SCPBC-sku-11', quantity '1', price '12,34, cause 'Плохо продавался' in the store ruled by 'departmentManager-SCPBC'
And the user opens write off list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
And the user clicks the local navigation writeOff search link
And the user searches writeOff by number 'SCPBC-11'
And the user clicks the writeOff search buttton and starts the search
Then the user checks the form results text is 'Нашлось списание'
And the user checks the writeOff with sku 'SCPBC-11' in search results
Then the user checks write off elements values
| elementName | value |
| writeOff number review | SCPBC-11 |
| writeOff date review | 02.04.2013 |
And the user checks the write off product with 'SCPBC-sku-11' sku is present
And the user checks the product with 'SCPBC-sku-11' sku has elements on the write off page
| elementName | value |
| writeOff product name review | SCPBC-name-11 |
| writeOff product sku review | SCPBC-sku-11 |
| writeOff product barCode review | SCPBC-barcode-11 |
| writeOff product quantity review | 1 |
| writeOff product price review | 12,34 |
| writeOff cause review | Плохо продавался |
Then the user checks write off elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 12,34 |

Scenario: WriteOff with product found by number click

Given there is the user with name 'departmentManager-SCPBC', position 'departmentManager-SCPBC', username 'departmentManager-SCPBC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SCPBC' managed by department manager named 'departmentManager-SCPBC'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'SCPBC-name-11' name, 'SCPBC-sku-11' sku, 'SCPBC-barcode-11' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And there is the writeOff in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC' with values
| elementName | elementValue |
| number | SCPBC-12 |
| date | 02.04.2013 |
And the user adds the product to the write off with number 'SCPBC-12' with sku 'SCPBC-sku-11', quantity '1', price '12,34, cause 'Плохо продавался' in the store ruled by 'departmentManager-SCPBC'
And the user opens write off list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
And the user clicks the local navigation writeOff search link
And the user searches writeOff by number 'SCPBC-12'
And the user clicks the writeOff search buttton and starts the search
Then the user checks the form results text is 'Нашлось списание'
And the user checks the writeOff with sku 'SCPBC-12' in search results
When the user clicks on the search result writeOff with number 'SCPBC-12'
Then the user checks write off elements values
| elementName | value |
| writeOff number review | SCPBC-12 |
| writeOff date review | 02.04.2013 |
And the user checks the write off product with 'SCPBC-sku-11' sku is present
And the user checks the product with 'SCPBC-sku-11' sku has elements on the write off page
| elementName | value |
| writeOff product name review | SCPBC-name-11 |
| writeOff product sku review | SCPBC-sku-11 |
| writeOff product barCode review | SCPBC-barcode-11 |
| writeOff product quantity review | 1 |
| writeOff product price review | 12,34 |
| writeOff cause review | Плохо продавался |
Then the user checks write off elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 12,34 |