Meta:
@sprint 19
@us 43

Narrative:
As a заведующим отделом
I want to работать с остатками своего магазина
In order to определять когда и какого объема требуется размещать заказ у поставщика.

Scenario: Store balance verification

Given there is the user with name 'departmentManager-BIC', position 'departmentManager-BIC', username 'departmentManager-BIC', password 'lighthouse', role 'departmentManager'
And there is the user with name 'departmentManager-BIC-2', position 'departmentManager-BIC-2', username 'departmentManager-BIC-2', password 'lighthouse', role 'departmentManager'
And there is the store with number 'BIC-01' managed by department manager named 'departmentManager-BIC'
And there is the store with number 'BIC-02' managed by department manager named 'departmentManager-BIC-2'
And there is the product with 'SBV-01' name, 'SBV-01' sku, 'SBV-01' barcode
And the user opens amount list page
When the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password
Then the user checks the product with 'SBV-01' sku has 'amounts amount' element equal to '0' on amounts page
When the user logs out
Given the user opens amount list page
When the user logs in using 'departmentManager-BIC-2' userName and 'lighthouse' password
Then the user checks the product with 'SBV-01' sku has 'amounts amount' element equal to '0' on amounts page
When the user logs out
Given there is the invoice with sku 'Invoice-BIC-1' in the store with number 'BIC-01' ruled by department manager with name 'departmentManager-BIC'
And the user adds the product to the invoice with name 'Invoice-BIC-1' with sku 'SBV-01', quantity '5', price '10' in the store ruled by 'departmentManager-BIC'
Given the user opens amount list page
When the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password
Then the user checks the product with 'SBV-01' sku has 'amounts amount' element equal to '5' on amounts page
When the user logs out
Given the user opens amount list page
When the user logs in using 'departmentManager-BIC-2' userName and 'lighthouse' password
Then the user checks the product with 'SBV-01' sku has 'amounts amount' element equal to '0' on amounts page
When the user logs out
Given there is the write off with number 'WriteOff-Bic-01' in the store with number 'BIC-01' ruled by user with name 'departmentManager-BIC'
And the user adds the product to the write off with number 'WriteOff-Bic-01' with sku 'SBV-01', quantity '3', price '5, cause 'Причины нет' in the store ruled by 'departmentManager-BIC'
Given the user opens amount list page
When the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password
Then the user checks the product with 'SBV-01' sku has 'amounts amount' element equal to '2' on amounts page
When the user logs out
Given the user opens amount list page
When the user logs in using 'departmentManager-BIC-2' userName and 'lighthouse' password
Then the user checks the product with 'SBV-01' sku has 'amounts amount' element equal to '0' on amounts page
When the user logs out
Given there is the invoice with sku 'Invoice-BIC-2' in the store with number 'BIC-01' ruled by department manager with name 'departmentManager-BIC-2'
And the user adds the product to the invoice with name 'Invoice-BIC-2' with sku 'SBV-01', quantity '3', price '10' in the store ruled by 'departmentManager-BIC-2'
Given the user opens amount list page
When the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password
Then the user checks the product with 'SBV-01' sku has 'amounts amount' element equal to '2' on amounts page
When the user logs out
Given the user opens amount list page
When the user logs in using 'departmentManager-BIC-2' userName and 'lighthouse' password
Then the user checks the product with 'SBV-01' sku has 'amounts amount' element equal to '3' on amounts page
When the user logs out
Given there is the write off with number 'WriteOff-Bic-02' in the store with number 'BIC-01' ruled by user with name 'departmentManager-BIC-2'
And the user adds the product to the write off with number 'WriteOff-Bic-02' with sku 'SBV-01', quantity '2', price '5, cause 'Причины нет' in the store ruled by 'departmentManager-BIC-2'
Given the user opens amount list page
When the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password
Then the user checks the product with 'SBV-01' sku has 'amounts amount' element equal to '2' on amounts page
When the user logs out
Given the user opens amount list page
When the user logs in using 'departmentManager-BIC-2' userName and 'lighthouse' password
Then the user checks the product with 'SBV-01' sku has 'amounts amount' element equal to '1' on amounts page

Scenario: Store last purchase price

Given there is the user with name 'departmentManager-BIC', position 'departmentManager-BIC', username 'departmentManager-BIC', password 'lighthouse', role 'departmentManager'
And there is the user with name 'departmentManager-BIC-2', position 'departmentManager-BIC-2', username 'departmentManager-BIC-2', password 'lighthouse', role 'departmentManager'
And there is the store with number 'BIC-01' managed by department manager named 'departmentManager-BIC'
And there is the store with number 'BIC-02' managed by department manager named 'departmentManager-BIC-2'
And there is the product with 'SBV-02' name, 'SBV-02' sku, 'SBV-02' barcode
And the user opens amount list page
When the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password
Then the user checks the product with 'SBV-02' sku has 'amounts purchasePrice' element equal to '123,00' on amounts page
When the user logs out
Given the user opens amount list page
When the user logs in using 'departmentManager-BIC-2' userName and 'lighthouse' password
Then the user checks the product with 'SBV-02' sku has 'amounts purchasePrice' element equal to '123,00' on amounts page
When the user logs out
Given there is the invoice with sku 'Invoice-BIC-3' in the store with number 'BIC-01' ruled by department manager with name 'departmentManager-BIC'
And the user adds the product to the invoice with name 'Invoice-BIC-3' with sku 'SBV-01', quantity '5', price '101' in the store ruled by 'departmentManager-BIC'
Given the user opens amount list page
When the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password
Then the user checks the product with 'SBV-02' sku has 'amounts purchasePrice' element equal to '101,00' on amounts page
When the user logs out
Given the user opens amount list page
When the user logs in using 'departmentManager-BIC-2' userName and 'lighthouse' password
Then the user checks the product with 'SBV-02' sku has 'amounts purchasePrice' element equal to '123,00' on amounts page
When the user logs out
Given there is the invoice with sku 'Invoice-BIC-4' in the store with number 'BIC-01' ruled by department manager with name 'departmentManager-BIC-2'
And the user adds the product to the invoice with name 'Invoice-BIC-4' with sku 'SBV-01', quantity '5', price '156' in the store ruled by 'departmentManager-BIC-2'
Given the user opens amount list page
When the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password
Then the user checks the product with 'SBV-02' sku has 'amounts purchasePrice' element equal to '101,00' on amounts page
When the user logs out
Given the user opens amount list page
When the user logs in using 'departmentManager-BIC-2' userName and 'lighthouse' password
Then the user checks the product with 'SBV-02' sku has 'amounts purchasePrice' element equal to '156,00' on amounts page

Scenario: Store average price
Given skipped test

Scenario: Department manager who has store can see balance link

Given there is the user with name 'departmentManager-BIC', position 'departmentManager-BIC', username 'departmentManager-BIC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'BIC-01' managed by department manager named 'departmentManager-BIC'
And the user opens the authorization page
When the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password
Then the user checks the dashboard link to 'balance' section is present

Scenario: Department manager who has store can get through link

Given there is the user with name 'departmentManager-BIC', position 'departmentManager-BIC', username 'departmentManager-BIC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'BIC-01' managed by department manager named 'departmentManager-BIC'
And the user opens amount list page
When the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password
Then the user dont see the 403 error

Scenario: Department manager who has no store cant see balance link

Given there is the user with name 'departmentManager-BIC-3', position 'departmentManager-BIC-3', username 'departmentManager-BIC-3', password 'lighthouse', role 'departmentManager'
And the user opens the authorization page
When the user logs in using 'departmentManager-BIC-3' userName and 'lighthouse' password
Then the user checks the dashboard link to 'balance' section is not present

Scenario: Department manager who has no store cant get through link

Given there is the user with name 'departmentManager-BIC-3', position 'departmentManager-BIC-3', username 'departmentManager-BIC-3', password 'lighthouse', role 'departmentManager'
And the user opens amount list page
When the user logs in using 'departmentManager-BIC-3' userName and 'lighthouse' password
Then the user sees the 403 error

Scenario: No balance page for commercialManager on left menu link

Given there is the user with name 'commercialManager-BIC-3', position 'commercialManager-BIC-3', username 'commercialManager-BIC-3', password 'lighthouse', role 'commercialManager'
And the user opens the authorization page
When the user logs in using 'commercialManager-BIC-3' userName and 'lighthouse' password
Then the user checks the dashboard link to 'balance' section is not present

Scenario: No balance page for commercialManager through link

Given there is the user with name 'commercialManager-BIC-3', position 'commercialManager-BIC-3', username 'commercialManager-BIC-3', password 'lighthouse', role 'commercialManager'
And the user opens amount list page
When the user logs in using 'commercialManager-BIC-3' userName and 'lighthouse' password
Then the user sees the 403 error

Scenario: Balance page for storeManager who has store on left menu link

Given there is the user with name 'storeManager-BIC', position 'storeManager-BIC', username 'storeManager-BIC', password 'lighthouse', role 'storeManager'
And there is the store with number 'BIC-011' managed by 'storeManager-BIC'
And the user opens the authorization page
When the user logs in using 'storeManager-BIC' userName and 'lighthouse' password
Then the user checks the dashboard link to 'balance' section is present

Scenario: Balance page for storeManager who has store throuhg link

Given there is the user with name 'storeManager-BIC', position 'storeManager-BIC', username 'storeManager-BIC', password 'lighthouse', role 'storeManager'
And there is the store with number 'BIC-011' managed by 'storeManager-BIC'
And the user opens amount list page
When the user logs in using 'storeManager-BIC' userName and 'lighthouse' password
Then the user dont see the 403 error

Scenario: Balance page for storeManager who has no store on left menu link

Given there is the user with name 'storeManager-BIC-3', position 'storeManager-BIC-3', username 'storeManager-BIC-3', password 'lighthouse', role 'storeManager'
And the user opens the authorization page
When the user logs in using 'storeManager-BIC-3' userName and 'lighthouse' password
Then the user checks the dashboard link to 'balance' section is not present

Scenario: Balance page for storeManager who has no store throuhg link

Given there is the user with name 'storeManager-BIC-3', position 'storeManager-BIC-3', username 'storeManager-BIC-3', password 'lighthouse', role 'storeManager'
And the user opens amount list page
When the user logs in using 'storeManager-BIC-3' userName and 'lighthouse' password
Then the user sees the 403 error


