package project.lighthouse.autotests.steps.departmentManager.invoice;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.openqa.selenium.By;
import org.openqa.selenium.TimeoutException;
import project.lighthouse.autotests.elements.items.DateTime;
import project.lighthouse.autotests.elements.preLoader.PreLoader;
import project.lighthouse.autotests.elements.preLoader.ProductEditionPreLoader;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.helper.StringGenerator;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.helper.exampleTable.invoice.InvoiceExampleTableUpdater;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.objects.web.invoice.InvoiceProductObject;
import project.lighthouse.autotests.objects.web.search.InvoiceListSearchObject;
import project.lighthouse.autotests.pages.departmentManager.invoice.InvoicePage;
import project.lighthouse.autotests.pages.departmentManager.invoice.deprecated.InvoiceSearchPage;
import project.lighthouse.autotests.storage.Storage;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class InvoiceSteps extends ScenarioSteps {

    InvoicePage invoicePage;
    InvoiceSearchPage invoiceSearchPage;

    private ExamplesTable examplesTable;

    @Step
    public void assertFieldLabel(String elementName) {
        invoicePage.checkFieldLabel(elementName);
    }

    @Step
    public void input(ExamplesTable examplesTable) {
        invoicePage.fieldInput(examplesTable);
        this.examplesTable = examplesTable;
    }

    @Step
    public void input(String elementName, String value) {
        invoicePage.input(elementName, value);
    }

    @Step
    public void checkValues() {
        invoicePage.checkValues(examplesTable);
    }

    @Step
    public void checkValues(ExamplesTable examplesTable) throws JSONException {
        ExamplesTable updatedExamplesTable = new InvoiceExampleTableUpdater(examplesTable).updateValuesStoredVertically();
        invoicePage.checkValues(updatedExamplesTable);
    }

    private InvoiceProductObject getInvoiceProductObject(String locator) {
        return (InvoiceProductObject) invoicePage.getInvoiceProductsCollection().getAbstractObjectByLocator(locator);
    }

    @Step
    public void invoiceProductObjectQuantityType(String locator, String value) {
        getInvoiceProductObject(locator).quantityType(value);
    }

    @Step
    public void lastCreatedProductObjectQuantityType(String value) throws JSONException {
        invoiceProductObjectQuantityType(
                Storage.getInvoiceVariableStorage().getProduct().getName(),
                value);
    }

    @Step
    public void assertInvoiceProductObjectQuantity(String locator, String expectedQuantity) {
        assertThat(getInvoiceProductObject(locator).getQuantity(), is(expectedQuantity));
    }

    @Step
    public void assertLastCreatedInvoiceProductObjectQuantity(String expectedQuantity) throws JSONException {
        assertInvoiceProductObjectQuantity(
                Storage.getInvoiceVariableStorage().getProduct().getName(),
                expectedQuantity);
    }

    @Step
    public void assertInvoiceProductObjectPrice(String locator, String expectedPrice) {
        assertThat(getInvoiceProductObject(locator).getPrice(), is(expectedPrice));
    }

    @Step
    public void assertLastCreatedInvoiceProductObjectPrice(String expectedPrice) throws JSONException {
        assertInvoiceProductObjectPrice(
                Storage.getInvoiceVariableStorage().getProduct().getName(),
                expectedPrice);
    }

    @Step
    public void invoiceProductObjectPriceType(String locator, String value) {
        getInvoiceProductObject(locator).priceType(value);
    }

    @Step
    public void invoiceProductObjectPriceType(String value) throws JSONException {
        invoiceProductObjectPriceType(Storage.getInvoiceVariableStorage().getProduct().getName(), value);
    }

    @Step
    public void invoiceProductEditionPreLoaderWait() {
        new ProductEditionPreLoader(getDriver()).await();
    }

    @Step
    public void invoiceProductsCollectionExactCompare(ExamplesTable examplesTable) throws JSONException {
        ExamplesTable updatedExamplesTable = new InvoiceExampleTableUpdater(examplesTable).updateValuesStoredHorizontally();
        invoicePage.getInvoiceProductsCollection().exactCompareExampleTable(updatedExamplesTable);
    }

    @Step
    public void assertInvoiceTotalSum(String expected) {
        assertThat(invoicePage.getTotalSum(), is(expected));
    }

    @Step
    public void assertInvoiceVatSum(String expected) {
        assertThat(invoicePage.getVatSum(), is(expected));
    }

    @Step
    public void acceptProductsButtonClick() {
        invoicePage.acceptProductsButtonClick();
        new PreLoader(getDriver()).await();
    }

    @Step
    public void cancelLinkClick() {
        invoicePage.cancelLinkClick();
    }

    @Step
    public void assertInvoiceOrderInfo() {
        String expectedInvoiceOrderInfoText =
                String.format(
                        "на основании заказа №10001 от %s",
                        new DateTimeHelper(0).convertDateByPattern("dd.MM.yyyy"));
        assertThat(invoicePage.getInvoiceOrderInfo(), is(expectedInvoiceOrderInfoText));
    }

    @Step
    public void invoiceOrderLinkClick() {
        invoicePage.orderOnLinkClick();
    }

    @Step
    public void openStoreInvoiceCreatePage(Store store) throws JSONException {
        String url = String.format(
                "%s/stores/%s/invoices/create",
                UrlHelper.getWebFrontUrl(),
                store.getId());
        getDriver().navigate().to(url);
    }

    @Step
    public void assertAcceptanceDateFieldContainsNowDate() {
        String nowDate = DateTimeHelper.getTodayDate(DateTime.DATE_TIME_PATTERN);
        invoicePage.checkValue("acceptanceDate", nowDate);
    }

    @Step
    public void inputGeneratedData(String elementName, int charNumber) {
        String generatedData = new StringGenerator(charNumber).generateTestData();
        input(elementName, generatedData);
    }

    @Step
    public void assertFieldLength(String elementName, int fieldLength) {
        invoicePage.checkFieldLength(elementName, fieldLength);
    }

    @Step
    public void assertInvoiceNumber(String expectedNumber) {
        assertThat(invoicePage.getInvoiceNumber(), is(expectedNumber));
    }

    @Step
    public void invoiceListSearchObjectClick(String locator) {
        invoiceSearchPage.getInvoiceListSearchObjectCollection().clickByLocator(locator);
    }

    @Step
    public void invoiceListSearchObjectContains(String locator) {
        invoiceSearchPage.getInvoiceListSearchObjectCollection().contains(locator);
    }

    @Step
    public void invoiceListSearchObjectExactCompareWith(ExamplesTable examplesTable) {
        invoiceSearchPage.getInvoiceListSearchObjectCollection().exactCompareExampleTable(examplesTable);
    }

    @Step
    public void invoiceListSearchObjectContainsHighLightedTextByLocator(String locator, String text) {
        InvoiceListSearchObject invoiceListSearchObject = (InvoiceListSearchObject) invoiceSearchPage.getInvoiceListSearchObjectCollection().getAbstractObjectByLocator(locator);
        assertThat(invoiceListSearchObject.getHighLightedText(), is(text));
    }

    @Step
    public void lastCreatedInvoiceListSearchObjectClick() {
        invoiceListSearchObjectClick(Storage.getInvoiceVariableStorage().getNumber());
    }

    @Step
    public void searchInvoiceByLastCreatedInvoiceNumber() {
        invoiceSearchPage.input("skuOrSupplierInvoiceSku", Storage.getInvoiceVariableStorage().getNumber());
    }

    @Step
    public void assertAutoCompletePlaceHolder(String expectedPlaceHolder) {
        assertThat(
                invoicePage.getItemAttribute("invoice product autocomplete", "placeholder"),
                is(expectedPlaceHolder));
    }

    @Step
    public void downloadAgreementButtonShouldBeVisible() {
        invoicePage.getDownloadAgreementFileButton().shouldBeVisible();
    }

    @Step
    public void downloadAgreementButtonShouldBeNotVisible() {
        invoicePage.getDownloadAgreementFileButton().shouldBeNotVisible();
    }

    @Step
    public void invoiceFocusOutClick() {
        invoicePage.findVisibleElement(By.className("form__totalSum")).click();
    }

    @Step
    public void typeInToActiveWebElement(String value) {
        invoicePage.$(getDriver().switchTo().activeElement()).type(value);
    }

    @Step
    public void assertActiveElementIsAutoComplete() {
        assertThat(
                getDriver().switchTo().activeElement(),
                is(invoicePage.getItems().get("invoice product autocomplete").getWebElement())
        );
    }

    @Step
    public void invoiceProductObjectClick(String locator) {
        invoicePage.getInvoiceProductsCollection().clickByLocator(locator);
    }

    @Step
    public void lastCreatedInvoiceProductObjectClick() throws JSONException {
        invoiceProductObjectClick(
                Storage.getInvoiceVariableStorage().getProduct().getName());
    }

    @Step
    public void invoiceProductObjectDeleteIconClick(String locator) {
        getInvoiceProductObject(locator).deleteIconClick();
    }

    @Step
    public void lastAddedInvoiceProductObjectDeleteIconClick() throws JSONException {
        invoiceProductObjectDeleteIconClick(
                Storage.getInvoiceVariableStorage().getProduct().getName());
    }

    @Step
    public void collectionDoNotContainInvoiceProductObjectByLocator(String locator) {
        try {
            invoicePage.getInvoiceProductsCollection().notContains(locator);
        } catch (TimeoutException ignored) {
        }
    }

    @Step
    public void collectionDoNotContainlastAddedInvoiceProductObject() throws JSONException {
        collectionDoNotContainInvoiceProductObjectByLocator(
                Storage.getInvoiceVariableStorage().getProduct().getName());
    }
}
