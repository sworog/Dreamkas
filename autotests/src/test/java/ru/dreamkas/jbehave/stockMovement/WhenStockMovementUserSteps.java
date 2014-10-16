package ru.dreamkas.jbehave.stockMovement;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Aliases;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import ru.dreamkas.steps.stockMovement.StockMovementSteps;

public class WhenStockMovementUserSteps {

    @Steps
    StockMovementSteps stockMovementSteps;

    @When("the user clicks on the accept products button")
    @Alias("пользователь нажимает на кнопку Принять от поставщика")
    public void whenTheUserClicksOnTheAcceptProductButton() {
        stockMovementSteps.acceptProductsButtonClick();
    }

    @When("пользователь нажимает на кнопку Списать на странице товародвижения")
    public void whenTheUserClicksOnTheCreateWriteOffButton() {
        stockMovementSteps.writeOffCreateButtonClick();
    }

    @When("пользователь нажимает на кнопку Оприходовать на странице товародвижения")
    public void whenUserClicksOnTheCreateSupplierReturnButton() {
        stockMovementSteps.stockInCreateButtonClick();
    }

    @When("пользователь нажимает на кнопку Вернуть поставщику на странице товародвижения")
    public void whenTheUserClicksOnTheCreateStockInButton() {
        stockMovementSteps.supplierReturnCreateButtonClick();
    }

    @When("the user inputs values on the create new invoice modal window $examplesTable")
    @Alias("пользователь вводит данные в модальном окне создания накладной $examplesTable")
    public void whenTheUserInputsOnTheCreateNewInvoiceModalWindow(ExamplesTable examplesTable) {
        stockMovementSteps.invoiceCreateModalWindowInput(examplesTable);
    }

    @When("пользователь вводит данные в модальном окне создания списания $examplesTable")
    public void whenTheUserInputsOnTheCreateNewWriteOffModalWindow(ExamplesTable examplesTable) {
        stockMovementSteps.writeOffCreateModalWindowInput(examplesTable);
    }

    @When("пользователь вводит value в поле с именем '$elementName' в модальном окне создания накладной")
    public void whenTheUserInputsOnTheCreateNewInvoiceModalWindow(String elementName, String value) {
        stockMovementSteps.invoiceCreateModalWindowInput(elementName, value);
    }

    @When("пользователь вводит value в поле с именем '$elementName' в модальном окне создания списания")
    public void whenTheUserInputsOnTheCreateNewWriteOffModalWindow(String elementName, String value) {
        stockMovementSteps.writeOffCreateModalWindowInput(elementName, value);
    }

    @When("the user inputs values on the edit invoice modal window $examplesTable")
    @Alias("пользователь вводит данные в модальном окне редактирования накладной $examplesTable")
    public void whenTheUserInputsOnTheEditInvoiceModalWindow(ExamplesTable examplesTable) {
        stockMovementSteps.invoiceEditModalWindowWindowInput(examplesTable);
    }

    @When("пользователь вводит данные в модальном окне редактирования списания $examplesTable")
    public void whenTheUserInputsOnTheEditWriteOffModalWindow(ExamplesTable examplesTable) {
        stockMovementSteps.writeOffEditModalWindowWindowInput(examplesTable);
    }

    @When("the user clicks on the paid check box")
    @Alias("пользователь нажимает на галочку Оплачено")
    public void whenUserClicksOnInvoicePaidCheckBox() {
        stockMovementSteps.clickInvoicePaidCheckBox();
    }

    @Alias("пользователь нажимает на галочку Оплачено в возврате поставщику")
    public void whenUserClicksOnSupplerReturnPaidCheckBox() {
        stockMovementSteps.clickInvoicePaidCheckBox();
    }

    @When("the user clicks on the paid check box in the edit invoice modal window")
    @Alias("пользователь нажимает на галочку Оплачено в модальном окне редактирования накладной")
    public void whenTheUserClicksOnThePaidCheckBoxInTheEditInvoiceModalWindow() {
        stockMovementSteps.invoiceEditModalWindowPaidCheckBoxClick();
    }

    @When("the user clicks on the add new invoice product button")
    @Alias("пользователь нажимает на кнопку добавления нового товара в накладную")
    public void whenTheUserClicksOnTheAddNewInvoiceProductButton() {
        stockMovementSteps.addProductToInvoiceButtonClick();
    }

    @When("пользователь нажимает на кнопку добавления нового товара в списание")
    public void whenTheUserClicksOnTheAddNewWriteOffProductButton() {
        stockMovementSteps.addProductToWriteOffOffButtonClick();
    }

    @When("пользователь нажимает на кнопку добавления нового товара в оприходование")
    public void whenTheUserClicksOnTheAddNewStockInProductButton() {
        stockMovementSteps.addProductToStockInButtonClick();
    }

    @When("пользователь нажимает на кнопку добавления нового товара в оприходование в модальном окне редактирования оприходования")
    public void whenTheUserClicksOnTheAddNewStockInProductButtonInTheStockInEditModalWindow() {
        stockMovementSteps.stockInEditAddProductToStockInButtonClick();
    }

    @When("the user clicks on the add new invoice product button in the edit invoice modal window")
    @Alias("пользователь нажимает на кнопку добавления нового товара в накладную в модальном окне редактирования накладной")
    public void whenTheUserClicksOnTheAddNewInvoiceProductButtonInTheEditModalWIndow() {
        stockMovementSteps.invoiceEditModalWindowAddProductToInvoiceButtonClick();
    }

    @When("пользователь нажимает на кнопку добавления нового товара в списание в модальном окне редактирования списания")
    public void whenTheUserClicksOnTheAddNewWriteOffProductButtonInTheEditModalWIndow() {
        stockMovementSteps.writeOffEditModalWindowAddProductToWriteOffButtonClick();
    }

    @When("пользователь нажимает на кнопку Списать, чтобы списать накладную с товарами")
    public void whenTheUserClicksOnTheWriteOffAcceptButton() {
        stockMovementSteps.acceptWriteOffButtonClick();
    }

    @When("пользователь нажимает на кнопку Оприходовать, чтобы оприходовать товары")
    public void whenTheUserClicksOnTheStockInAcceptButton() {
        stockMovementSteps.acceptStockInButtonClick();
    }

    @When("the user clicks on the invoice accept button")
    @Alias("пользователь нажимает на кнопку Принять, чтобы принять накладную с товарами")
    public void whenTheUserClicksOnTheInvoiceAcceptButton() {
        stockMovementSteps.acceptInvoiceButtonClick();
    }

    @When("the user clicks on the invoice save button in the edit invoice modal window")
    @Alias("пользователь нажимает на кнопку сохранения накладной в модальном окне редактирования накладной")
    public void whenTheUserClicksOnTheInvoiceSaveButton() {
        stockMovementSteps.saveInvoiceButtonClick();
    }

    @When("пользователь нажимает на кнопку сохранения списания в модальном окне редактирования списания")
    public void whenTheUserClicksOnTheWriteOffSaveButton() {
        stockMovementSteps.saveWriteOffButtonClick();
    }

    @When("пользователь нажимает на кнопку сохранения оприходования в модальном окне редактирования оприходования")
    public void whenTheUserClicksOnTheStockInSaveButton() {
        stockMovementSteps.saveStockInButtonClick();
    }

    @When("the user clicks on the last created invoice from builder steps on the stock movement page")
    @Alias("пользователь нажимает на последнюю созданнаю накладную с помощью конструктора накладных на странице товародвижения")
    public void whenTheUserClicksOnTheLastCreatedInvoiceFromBuilderStepsOnTheStockMovementPage() throws JSONException {
        stockMovementSteps.openLastCreatedInvoiceInStockMovementPage();
    }

    @When("пользователь нажимает на последнее созданное списание с помощью конструктора списаний на странице товародвижения")
    public void whenTheUserClicksOnTheLastCreatedWriteOffFromBuilderStepsOnTheStockMovementPage() throws JSONException {
        stockMovementSteps.openLastCreatedWriteOffInStockMovementPage();
    }

    @When("пользователь нажимает на последнее созданное оприходование с помощью конструктора оприходований на странице товародвижения")
    public void whenTheUserClicksOnTheLastCreatedStockInFromBuilderStepsOnTheStockMovementPage() throws JSONException {
        stockMovementSteps.openLastCreatedStockInInStockMovementPage();
    }

    @When("пользователь нажимает на последнее созданный возврат поставщику с помощью конструктора оприходований на странице товародвижения")
    public void whenUserClicksOnTheLastCreatedSupplierReturnFromBuilderStepsOnTheStockMovementPage() throws JSONException {
        stockMovementSteps.openLastCreatedSupplierReturnMovementPage();
    }

    @When("the user clicks on the invoice with number '$number' on the stock movement page")
    @Aliases(values = {
            "пользователь нажимает на накладную с номером '$number' на странице товародвижения",
            "пользователь нажимает на списание с номером '$number' на странице товародвижения",
            "пользователь нажимает на оприходование с номером '$number' на странице товародвижения",
            "пользователь нажимает на возврат поставщику с номером '$number' на странице товародвижения"
    })
    public void whenTheUserClicksOnTheInvoiceWithName(String number) throws JSONException {
        stockMovementSteps.openOperationByNumberInStockMovementPage(number);
    }

    @When("the user clicks on delete invoice button in edit invoice modal window")
    @Alias("пользователь нажимает на кнопку удаления накладной в модальном окне редактирования накладной")
    public void whenTheUserClicksOnDeleteInvoiceButtonInEditInvoiceModalWindow() {
        stockMovementSteps.deleteInvoiceLinkClick();
    }

    @When("пользователь нажимает на кнопку удаления списания в модальном окне редактирования списания")
    public void whenTheUserClicksOnDeleteInvoiceButtonInEditWriteOffModalWindow() {
        stockMovementSteps.deleteWriteOffLinkClick();
    }

    @When("пользователь нажимает на кнопку удаления оприходования в модальном окне редактирования оприходования")
    public void whenTheUserClicksOnDeleteStockInButtonInEditWriteOffModalWindow() {
        stockMovementSteps.deleteStockInLinkClick();
    }

    @When("the user clicks on delete invoice confirm button in edit invoice modal window")
    @Alias("пользователь подтверждает удаление накладной в модальном окне редактирования накладной")
    public void whenTheUserClicksOnDeleteInvoiceConfirmButtonInEditInvoiceModalWindow() {
        stockMovementSteps.confirmDeleteInvoiceLinkClick();
    }

    @When("пользователь подтверждает удаление списания в модальном окне редактирования списания")
    public void whenTheUserClicksOnDeleteWriteOffConfirmButtonInEditInvoiceModalWindow() {
        stockMovementSteps.confirmDeleteWriteOffLinkClick();
    }

    @When("пользователь подтверждает удаление оприходования в модальном окне редактирования оприходования")
    public void whenTheUserClicksOnDeleteStockInConfirmButtonInEditInvoiceModalWindow() {
        stockMovementSteps.confirmDeleteStockInLinkClick();
    }

    @When("the user deletes the product with name '$name' in the edit invoice modal window")
    @Alias("пользователь удаляет товар с названием '$name' в модальном окне редактирования накладной")
    public void whenTheUserDeletesTheProductWithNameInTheEditInvoiceModalWindow(String name) {
        stockMovementSteps.invoiceProductWithNameDeleteIconClick(name);
    }

    @When("пользователь удаляет товар с названием '$name' в модальном окне редактирования списания")
    public void whenTheUserDeletesTheProductWithNameInTheEditWriteOffModalWindow(String name) {
        stockMovementSteps.writeOffProductWithNameDeleteIconClick(name);
    }

    @When("пользователь удаляет товар с названием '$name' в модальном окне редактирования оприходования")
    public void whenTheUserDeletesTheProductWithNameInTheEditStockInModalWindow(String name) {
        stockMovementSteps.stockInProductWithNameDeleteIconClick(name);
    }

    @When("пользователь нажимает на плюсик рядом с полем выбора поставщика, чтобы создать нового поставщика")
    public void whenTheUserClicksOnAddNewSupplierIconInOrderToCreateNewSupplier() {
        stockMovementSteps.invoiceCreateModalWindowAddNewSupplierIconClick();
    }

    @When("пользователь нажимает на плюсик рядом с полем выбора поставщика в модальном окне редактирования накладной, чтобы создать нового поставщика")
    public void whenTheUserClicksOnAddNewSupplierIconInOrderToCreateNewSupplierInTheInvoiceEditModalWindow() {
        stockMovementSteps.invoiceEditModalWindowAddNewSupplierIconClick();
    }

    @When("пользователь заполняет поля в модальном окне создания нового поставщика $examplesTable")
    public void whenTheUserInputsFieldsInTheSupplierCreateModalWindow(ExamplesTable examplesTable) {
        stockMovementSteps.invoiceSupplierCreateModalWindowInput(examplesTable);
    }

    @When("пользователь нажимает на кнопку Добавить в окне создания нового поставщика")
    public void whenTheUserClicksOnAddNewSupplierButtonInTheCreateNewSupplierModalWindow() {
        stockMovementSteps.invoiceSupplierCreateModalWindowConfirmOkClick();
    }

    @When("пользователь нажимает на плюсик рядом с автокомплитным полем выбора товара, чтобы создать новый товар")
    public void whenTheUserClicksOnPlusIconInOrderToCreateNewProductInTheInvoiceCreateModalWindow() {
        stockMovementSteps.invoiceCreateModalWindowNewProductCreateClick();
    }

    @When("пользователь нажимает на плюсик рядом с автокомплитным полем выбора товара в модальном окне редактирования накладной, чтобы создать новый товар")
    public void whenTheUserClicksOnPlusIconInOrderToCreateNewProductInTheInvoiceУвшеModalWindow() {
        stockMovementSteps.invoiceEditModalWindowNewProductCreateClick();
    }

    @When("пользователь заполняет поля в модальном окне создания нового товара $examplesTable")
    public void whenTheUserInputsValuesInTheInvoiceProductCreateModalWindow(ExamplesTable examplesTable) {
        stockMovementSteps.invoiceProductCreateModalWindowInputValues(examplesTable);
    }

    @When("пользователь вводит значение value в поле с именем '$elementName' в модальном окне создания товара")
    public void whenTheUserInputsValuesInTheInvoiceProductCreateModalWindow(String value, String elementName) {
        stockMovementSteps.invoiceProductCreateModalWindowInputValue(elementName, value);
    }

    @When("пользователь нажимает на кнопку Добавить в окне создания нового товара")
    public void whenTheUserClicksOnAddNewProductButtonInTheInvoiceCreateNewProductModalWindow() {
        stockMovementSteps.invoiceProductCreateModalWindowConfirmButtonClick();
    }

    @When("пользователь вводит данные в поля на странице товародвижения $examplesTable")
    public void whenTheUserFieldInputValuesOnTheStockMovementPage(ExamplesTable examplesTable) {
        stockMovementSteps.stockMovementPageFieldInput(examplesTable);
    }

    @When("пользователь генерирует текст с кол-во символов '$count' в поле с именем '$name' в модальном окне создания поставщика в накладной")
    public void whrnTheUserGeneratesSymbolsWithCountInTheCreateStoreModalWindowFieldWithName(int count, String name) {
        stockMovementSteps.supplierCreateModalPageInputGeneratedText(name, count);
    }

    @When("пользователь генерирует текст с кол-во символов '$count' в поле с именем '$name' в модальном окне создания товара в накладной")
    public void whenTheUserGeneratesSymbolsWithCountInCreateNewProductModalWindowField(int count, String elementName) {
        stockMovementSteps.createNewProductModalWindowFieldGenerateText(elementName, count);
    }

    @When("пользователь нажимает на кнопку Применить фильтры на странице товародвижения")
    public void whenTheUserClicksOnAcceptFiltersButton() {
        stockMovementSteps.acceptFiltersButtonClick();
    }

    @When("пользователь нажимает на кнопку Сбросить фильтры на странице товародвижения")
    public void whenTheUserClicksOnResetFiltersButton() {
        stockMovementSteps.resetFiltersButtonClick();
    }
}
