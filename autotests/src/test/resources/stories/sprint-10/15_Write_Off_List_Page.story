15 Просмотр списка списаний

Narrative:
Как заведующий отделом,
Я хочу просматривать плоский список списаний,
Чтобы найти прежде оформленное списание

Scenario: Write off list create
Given there is the product with 'WriteOff-wolc' name, 'WriteOff-wolc' sku, 'WriteOff-wolc' barcode, 'liter' units, '15' purchasePrice
And the user opens write off list page
And the user logs in as 'departmentManager'
When the user creates write off from write off list page
And the user inputs 'WriteOff-wolc' in the 'writeOff number' field on the write off page
And the user inputs '24.10.2012' in the 'writeOff date' field on the write off page
And the user continues the write off creation
And the user inputs 'WriteOff-wolc' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
And the user clicks finish edit button and ends the write off edition
And the user goes to the write off list page by clicking the link
Then the user checks the write off with 'WriteOff-wolc' is present on write off list page
And the user checks the product with 'WriteOff-wolc' sku has 'writeOff list page date' element equal to '24.10.2012' on write off list page
And the user checks the product with 'WriteOff-wolc' sku has 'writeOff list page number' element equal to 'WriteOff-wolc' on write off list page
And the user checks the product with 'WriteOff-wolc' sku has 'writeOff list page sumTotal' element equal to '150' on write off list page
When the user logs out