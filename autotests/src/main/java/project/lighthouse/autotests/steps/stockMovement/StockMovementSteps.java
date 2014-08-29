package project.lighthouse.autotests.steps.stockMovement;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.TimeoutException;
import project.lighthouse.autotests.elements.bootstrap.SimplePreloader;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.helper.StringGenerator;
import project.lighthouse.autotests.objects.web.stockMovement.invoiceProduct.InvoiceProductCollection;
import project.lighthouse.autotests.objects.web.stockMovement.invoiceProduct.InvoiceProductObject;
import project.lighthouse.autotests.objects.web.stockMovement.stockIn.StockInProductObject;
import project.lighthouse.autotests.objects.web.stockMovement.stockIn.StockInProductCollection;
import project.lighthouse.autotests.objects.web.stockMovement.StockMovementListObjectCollection;
import project.lighthouse.autotests.objects.web.writeOffProduct.WriteOffProductCollection;
import project.lighthouse.autotests.objects.web.writeOffProduct.WriteOffProductObject;
import project.lighthouse.autotests.pages.stockMovement.StockMovementPage;
import project.lighthouse.autotests.pages.stockMovement.modal.invoice.InvoiceCreateModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.invoice.InvoiceEditModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.invoice.InvoiceProductCreateModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.invoice.InvoiceSupplierCreateModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.stockIn.StockInCreateModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.stockIn.StockInEditModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.writeOff.WriteOffCreateModalWindow;
import project.lighthouse.autotests.pages.stockMovement.modal.writeOff.WriteOffEditModalWindow;
import project.lighthouse.autotests.storage.Storage;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class StockMovementSteps extends ScenarioSteps {

    StockMovementPage stockMovementPage;
    InvoiceCreateModalWindow invoiceCreateModalWindow;
    InvoiceEditModalWindow invoiceEditModalWindow;
    InvoiceSupplierCreateModalWindow invoiceSupplierCreateModalWindow;
    InvoiceProductCreateModalWindow invoiceProductCreateModalWindow;
    WriteOffCreateModalWindow writeOffCreateModalWindow;
    WriteOffEditModalWindow writeOffEditModalWindow;
    StockInCreateModalWindow stockInCreateModalWindow;
    StockInEditModalWindow stockInEditModalWindow;

    private String name;

    @Step
    public void stockMovementPageFieldInput(ExamplesTable examplesTable) {
        stockMovementPage.input(examplesTable);
    }

    @Step
    public void stockMovementPageFieldsCheckValues(ExamplesTable examplesTable) {
        stockMovementPage.checkValues(examplesTable);
    }

    @Step
    public void stockMovementPageOpen() {
        stockMovementPage.open();
    }

    @Step
    public void acceptProductsButtonClick() {
        stockMovementPage.addObjectButtonClick();
    }

    @Step
    public void writeOffCreateButtonClick() {
        stockMovementPage.writeOffCreateButtonClick();
    }

    @Step
    public void stockInCreateButtonClick() {
        stockMovementPage.stockInCreateButtonClick();
    }

    @Step
    public void supplierReturnCreateButtonClick() {
        stockMovementPage.supplierReturnButtonClick();
    }

    @Step
    public void invoiceCreateModalWindowInput(ExamplesTable examplesTable) {
        invoiceCreateModalWindow.input(examplesTable);
    }

    @Step
    public void writeOffCreateModalWindowInput(ExamplesTable examplesTable) {
        writeOffCreateModalWindow.input(examplesTable);
    }

    @Step
    public void invoiceCreateModalWindowInput(String elementName, String value) {
        invoiceCreateModalWindow.input(elementName, value);
    }

    @Step
    public void writeOffCreateModalWindowInput(String elementName, String value) {
        writeOffCreateModalWindow.input(elementName, value);
    }

    @Step
    public void invoiceEditModalWindowChecksValues(ExamplesTable examplesTable) {
        invoiceEditModalWindow.checkValues(examplesTable);
    }

    @Step
    public void WriteOffEditModalWindowChecksValues(ExamplesTable examplesTable) {
        writeOffEditModalWindow.checkValues(examplesTable);
    }

    @Step
    public void invoiceEditModalWindowWindowInput(ExamplesTable examplesTable) {
        invoiceEditModalWindow.input(examplesTable);
    }

    @Step
    public void writeOffEditModalWindowWindowInput(ExamplesTable examplesTable) {
        writeOffEditModalWindow.input(examplesTable);
    }

    @Step
    public void clickInvoicePaidCheckBox() {
        invoiceCreateModalWindow.clickPaidCheckBox();
    }

    @Step
    public void invoiceEditModalWindowPaidCheckBoxClick() {
        invoiceEditModalWindow.clickPaidCheckBox();
    }

    @Step
    public void addProductToInvoiceButtonClick() {
        invoiceCreateModalWindow.addProductButtonClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void addProductToWriteOffOffButtonClick() {
        writeOffCreateModalWindow.addProductButtonClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void addProductToStockInButtonClick() {
        stockInCreateModalWindow.addProductButtonClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void stockInEditAddProductToStockInButtonClick() {
        stockInEditModalWindow.addProductButtonClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void invoiceEditModalWindowAddProductToInvoiceButtonClick() {
        invoiceEditModalWindow.addProductButtonClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void writeOffEditModalWindowAddProductToWriteOffButtonClick() {
        writeOffEditModalWindow.addProductButtonClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void acceptInvoiceButtonClick() {
        invoiceCreateModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void acceptWriteOffButtonClick() {
        writeOffCreateModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void acceptStockInButtonClick() {
        stockInCreateModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void saveInvoiceButtonClick() {
        invoiceEditModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void saveWriteOffButtonClick() {
        writeOffEditModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void saveStockInButtonClick() {
        stockInEditModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void stockMovementPageContainStockOperation(ExamplesTable examplesTable) {
        StockMovementListObjectCollection stockMovementObjectCollection = getStockMovementObjectCollection();
        if (stockMovementObjectCollection != null) {
            stockMovementObjectCollection.compareWithExampleTable(examplesTable);
        }
    }

    @Step
    public void stockMovementPageContainExactStockOperation(ExamplesTable examplesTable) {
        StockMovementListObjectCollection stockMovementObjectCollection = getStockMovementObjectCollection();
        if (stockMovementObjectCollection != null) {
            stockMovementObjectCollection.exactCompareExampleTable(examplesTable);
        }
    }

    private StockMovementListObjectCollection getStockMovementObjectCollection() {
        StockMovementListObjectCollection stockMovementObjectCollection = null;
        try {
            stockMovementObjectCollection = stockMovementPage.getStockMovementObjectCollection();
        } catch (TimeoutException e) {
            stockMovementPage.containsText("Не найдено ни одной операции с товарами.");
        } catch (StaleElementReferenceException e) {
            stockMovementObjectCollection = stockMovementPage.getStockMovementObjectCollection();
        }
        return stockMovementObjectCollection;
    }

    private InvoiceProductCollection getInvoiceProductCollection() {
        InvoiceProductCollection invoiceProductCollection;
        try {
            invoiceProductCollection = invoiceCreateModalWindow.getProductCollection();
        } catch (StaleElementReferenceException e) {
            invoiceProductCollection = invoiceCreateModalWindow.getProductCollection();
        }
        return invoiceProductCollection;
    }

    private WriteOffProductCollection getWriteOffProductCollection() {
        WriteOffProductCollection writeOffProductCollection;
        try {
            writeOffProductCollection = writeOffCreateModalWindow.getProductCollection();
        } catch (StaleElementReferenceException e) {
            writeOffProductCollection = writeOffCreateModalWindow.getProductCollection();
        }
        return writeOffProductCollection;
    }

    private StockInProductCollection getStockInProductCollection() {
        StockInProductCollection stockInProductCollection;
        try {
            stockInProductCollection = stockInCreateModalWindow.getProductCollection();
        } catch (StaleElementReferenceException e) {
            stockInProductCollection = stockInCreateModalWindow.getProductCollection();
        }
        return stockInProductCollection;
    }

    @Step
    public void invoiceProductCollectionExactCompare(ExamplesTable examplesTable) {
        getInvoiceProductCollection().exactCompareExampleTable(examplesTable);
    }

    @Step
    public void invoiceWriteOffCollectionExactCompare(ExamplesTable examplesTable) {
        getWriteOffProductCollection().exactCompareExampleTable(examplesTable);
    }

    @Step
    public void stockInCollectionExactCompare(ExamplesTable examplesTable) {
        getStockInProductCollection().exactCompareExampleTable(examplesTable);
    }

    @Step
    public void invoiceProductWithNameDeleteIconClick(String name) {
        InvoiceProductObject invoiceProductObject =
                (InvoiceProductObject) getInvoiceProductCollection().getAbstractObjectByLocator(name);
        invoiceProductObject.clickDeleteIcon();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void writeOffProductWithNameDeleteIconClick(String name) {
        WriteOffProductObject writeOffProduct =
                (WriteOffProductObject) getWriteOffProductCollection().getAbstractObjectByLocator(name);
        writeOffProduct.deleteIconClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void stockInProductWithNameDeleteIconClick(String name) {
        StockInProductObject stockInProduct =
                (StockInProductObject) getStockInProductCollection().getAbstractObjectByLocator(name);
        stockInProduct.clickDeleteIcon();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void assertWriteOffCreateModalWindowsCausePlaceholderText(String text) {
        assertThat(writeOffCreateModalWindow.getItemAttribute("cause", "placeholder"), is(text));
    }

    @Step
    public void assertWriteOffEditModalWindowsProductRowsCount(Integer expectedCount) {
        assertThat(writeOffEditModalWindow.getProductRowsCount(), is(expectedCount));
    }

    @Step
    public void assertStockMovementPageTitle(String title) {
        assertThat(stockMovementPage.getTitle(), is(title));
    }

    @Step
    public void assertInvoiceCreateModalWindowPageTitle(String title) {
        assertThat(invoiceCreateModalWindow.getTitle(), is(title));
    }

    @Step
    public void assertWriteOffCreateModalWindowPageTitle(String title) {
        assertThat(writeOffCreateModalWindow.getTitle(), is(title));
    }

    @Step
    public void assertInvoiceEditModalWindowPageTitle(String title) {
        assertThat(invoiceEditModalWindow.getTitle(), is(title));
    }

    @Step
    public void assertWriteOffEditModalWindowPageTitle(String title) {
        assertThat(writeOffEditModalWindow.getTitle(), is(title));
    }

    @Step
    public void openLastCreatedInvoiceInStockMovementPage() throws JSONException {
        openOperationByNumberInStockMovementPage(Storage.getStockMovementVariableStorage().getLastInvoice().getNumber());
    }

    @Step
    public void openLastCreatedWriteOffInStockMovementPage() throws JSONException {
        openOperationByNumberInStockMovementPage(Storage.getStockMovementVariableStorage().getLastWriteOff().getNumber());
    }

    @Step
    public void openLastCreatedStockInInStockMovementPage() throws JSONException {
        openOperationByNumberInStockMovementPage(Storage.getStockMovementVariableStorage().getLastStockIn().getNumber());
    }

    @Step
    public void openLastCreatedSupplierReturnMovementPage() throws JSONException {
        openOperationByNumberInStockMovementPage(Storage.getStockMovementVariableStorage().getLastSupplierReturn().getNumber());
    }

    @Step
    public void openOperationByNumberInStockMovementPage(String number) {
        StockMovementListObjectCollection stockMovementObjectCollection = stockMovementPage.getStockMovementObjectCollection();
        if (stockMovementObjectCollection != null) {
            stockMovementObjectCollection.clickByLocator(number);
        }
    }

    @Step
    public void stockMovementCollectionDontContainLastCreatedInvoice() throws JSONException {
        String number = Storage.getStockMovementVariableStorage().getLastInvoice().getNumber();
        stockMovementCollectionDoesNotContainNumber(number);
    }

    @Step
    public void stockMovementCollectionDontContainLastCreatedWriteOff() throws JSONException {
        String number = Storage.getStockMovementVariableStorage().getLastWriteOff().getNumber();
        stockMovementCollectionDoesNotContainNumber(number);
    }

    @Step
    public void stockMovementCollectionDontContainLastCreatedStockIn() throws JSONException {
        String number = Storage.getStockMovementVariableStorage().getLastStockIn().getNumber();
        stockMovementCollectionDoesNotContainNumber(number);
    }

    @Step
    public void stockMovementCollectionDontContainLastCreatedSupplierReturn() throws JSONException {
        String number = Storage.getStockMovementVariableStorage().getLastSupplierReturn().getNumber();
        stockMovementCollectionDoesNotContainNumber(number);
    }

    protected void stockMovementCollectionDoesNotContainNumber(String number) throws JSONException {
        StockMovementListObjectCollection stockMovementObjectCollection = getStockMovementObjectCollection();
        if (stockMovementObjectCollection != null) {
            stockMovementObjectCollection.notContains(number);
        }
    }

    @Step
    public void deleteInvoiceLinkClick() {
        invoiceEditModalWindow.deleteButtonClick();
    }

    @Step
    public void deleteWriteOffLinkClick() {
        writeOffEditModalWindow.deleteButtonClick();
    }

    @Step
    public void deleteStockInLinkClick() {
        stockInEditModalWindow.deleteButtonClick();
    }

    @Step
    public void confirmDeleteInvoiceLinkClick() {
        invoiceEditModalWindow.confirmDeleteButtonClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void confirmDeleteWriteOffLinkClick() {
        writeOffEditModalWindow.confirmDeleteButtonClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void confirmDeleteStockInLinkClick() {
        stockInEditModalWindow.confirmDeleteButtonClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void assertInvoiceCreateModalWindowTotalSum(String totalSum) {
        assertThat(invoiceCreateModalWindow.getTotalSum(), is(totalSum));
    }

    @Step
    public void assertWriteOffCreateModalWindowTotalSum(String totalSum) {
        assertThat(writeOffCreateModalWindow.getTotalSum(), is(totalSum));
    }

    @Step
    public void assertStockInCreateModalWindowTotalSum(String totalSum) {
        assertThat(stockInCreateModalWindow.getTotalSum(), is(totalSum));
    }

    @Step
    public void assertInvoiceEditModalWindowTotalSum(String totalSum) {
        assertThat(invoiceEditModalWindow.getTotalSum(), is(totalSum));
    }

    @Step
    public void assertWriteOffEditModalWindowTotalSum(String totalSum) {
        assertThat(writeOffEditModalWindow.getTotalSum(), is(totalSum));
    }

    @Step
    public void assertStockInEditModalWindowTotalSum(String totalSum) {
        assertThat(stockInEditModalWindow.getTotalSum(), is(totalSum));
    }

    @Step
    public void assertInvoiceDateIsNowDate() {
        invoiceCreateModalWindowCheckValue("date", DateTimeHelper.getDate("todayDate"));
    }

    @Step
    public void assertWriteOffDateIsNowDate() {
        writeOffCreateModalWindowCheckValue("date", DateTimeHelper.getDate("todayDate"));
    }

    @Step
    public void assertStockInDateIsNowDate() {
        stockInCreateModalWindowCheckValue("date", DateTimeHelper.getDate("todayDate"));
    }

    @Step
    public void invoiceEditModalWindowCheckValue(String elementName, String value) {
        invoiceEditModalWindow.checkValue(elementName, value);
    }

    @Step
    public void invoiceCreateModalWindowCheckValue(String elementName, String value) {
        invoiceCreateModalWindow.checkValue(elementName, value);
    }

    @Step
    public void writeOffCreateModalWindowCheckValue(String elementName, String value) {
        writeOffCreateModalWindow.checkValue(elementName, value);
    }

    @Step
    public void stockInCreateModalWindowCheckValue(String elementName, String value) {
        stockInCreateModalWindow.checkValue(elementName, value);
    }

    @Step
    public void invoiceCreateModalWindowCheckValueProductName() {
        invoiceCreateModalWindow.checkValue("product.name", name);
    }

    @Step
    public void invoiceCreateModalWindowCheckValue() {
        invoiceCreateModalWindow.checkValue("supplier", name);
    }

    @Step
    public void invoiceCreateModalWindowAddNewSupplierIconClick() {
        invoiceCreateModalWindow.createSupplierButtonClick();
    }

    @Step
    public void invoiceEditModalWindowAddNewSupplierIconClick() {
        invoiceEditModalWindow.createSupplierButtonClick();
    }

    @Step
    public void invoiceSupplierCreateModalWindowInput(ExamplesTable examplesTable) {
        invoiceSupplierCreateModalWindow.input(examplesTable);
    }

    @Step
    public void invoiceSupplierCreateModalWindowConfirmOkClick() {
        invoiceSupplierCreateModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void invoiceCreateModalWindowNewProductCreateClick() {
        invoiceCreateModalWindow.createProductButtonClick();
    }

    @Step
    public void invoiceEditModalWindowNewProductCreateClick() {
        invoiceEditModalWindow.createProductButtonClick();
    }

    @Step
    public void invoiceProductCreateModalWindowInputValues(ExamplesTable examplesTable) {
        invoiceProductCreateModalWindow.input(examplesTable);
    }

    @Step
    public void invoiceProductCreateModalWindowInputValue(String elementValue, String text) {
        invoiceProductCreateModalWindow.input(elementValue, text);
    }

    @Step
    public void invoiceProductCreateModalWindowConfirmButtonClick() {
        invoiceProductCreateModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void supplierCreateModalPageCheckErrorMessage(String elementName, String errorMessage) {
        invoiceSupplierCreateModalWindow.getItems().get(elementName).getFieldErrorMessageChecker().assertFieldErrorMessage(errorMessage);
    }

    @Step
    public void invoiceCreateModalCheckErrorMessage(String elementName, String errorMessage) {
        invoiceCreateModalWindow.getItems().get(elementName).getFieldErrorMessageChecker().assertFieldErrorMessage(errorMessage);
    }

    @Step
    public void invoiceEditModalCheckErrorMessage(String elementName, String errorMessage) {
        invoiceEditModalWindow.getItems().get(elementName).getFieldErrorMessageChecker().assertFieldErrorMessage(errorMessage);
    }

    @Step
    public void writeOffCreateModalCheckErrorMessage(String elementName, String errorMessage) {
        writeOffCreateModalWindow.getItems().get(elementName).getFieldErrorMessageChecker().assertFieldErrorMessage(errorMessage);
    }

    @Step
    public void writeOffEditModalCheckErrorMessage(String elementName, String errorMessage) {
        writeOffEditModalWindow.getItems().get(elementName).getFieldErrorMessageChecker().assertFieldErrorMessage(errorMessage);
    }

    @Step
    public void supplierCreateModalPageInputGeneratedText(String elementName, int count) {
        String generatedString = new StringGenerator(count).generateString("a");
        invoiceSupplierCreateModalWindow.input(elementName, generatedString);
        this.name = generatedString;
    }

    @Step
    public void assertCreateNewProductModalWindowFieldErrorMessage(String elementName, String errorMessage) {
        invoiceProductCreateModalWindow.getItems().get(elementName).getFieldErrorMessageChecker().assertFieldErrorMessage(errorMessage);
    }

    @Step
    public void createNewProductModalWindowFieldGenerateText(String elementName, int number) {
        String generatedString = new StringGenerator(number).generateString("f");
        invoiceProductCreateModalWindow.input(elementName, generatedString);
        this.name = generatedString;
    }

    @Step
    public void acceptFiltersButtonClick() {
        stockMovementPage.acceptFiltersButtonClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void resetFiltersButtonClick() {
        stockMovementPage.resetFiltersButtonClick();
        new SimplePreloader(getDriver()).await();
    }
}
