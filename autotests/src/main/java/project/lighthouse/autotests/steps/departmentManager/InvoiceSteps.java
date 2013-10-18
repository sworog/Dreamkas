package project.lighthouse.autotests.steps.departmentManager;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.elements.DateTime;
import project.lighthouse.autotests.objects.InvoiceSearchObject;
import project.lighthouse.autotests.pages.commercialManager.product.ProductApi;
import project.lighthouse.autotests.pages.departmentManager.invoice.*;

import java.io.IOException;
import java.util.Map;

public class InvoiceSteps extends ScenarioSteps {

    InvoiceCreatePage invoiceCreatePage;
    InvoiceListPage invoiceListPage;
    InvoiceBrowsing invoiceBrowsing;
    InvoiceApi invoiceApi;
    ProductApi productApi;
    CommonPage commonPage;

    InvoiceSearchPage invoiceSearchPage;
    InvoiceLocalNavigation invoiceLocalNavigation;

    public InvoiceSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void createInvoiceThroughPost(String invoiceName) throws JSONException, IOException {
        invoiceApi.createInvoiceThroughPostAndNavigateToIt(invoiceName);
    }

    @Step
    public void createInvoiceThroughPostWithData(String invoiceName, String productName, String productSku, String productBarCode, String productUnits) throws JSONException, IOException {
        productApi.сreateProductThroughPost(productName, productSku, productBarCode, productUnits, "123");
        invoiceApi.createInvoiceThroughPostWithProductAndNavigateToIt(invoiceName, productSku);
    }

    @Step
    public void createInvoiceThroughPost(String invoiceName, String storeName, String userName) throws IOException, JSONException {
        invoiceApi.createInvoiceThroughPost(invoiceName, storeName, userName);
    }

    @Step
    public void createInvoiceThroughPost(String storeName, String userName, ExamplesTable examplesTable) throws IOException, JSONException {
        invoiceApi.createInvoiceThroughPost(storeName, userName, examplesTable);
    }

    @Step
    public void navigateToTheInvoicePage(String invoiceName) throws JSONException {
        invoiceApi.navigateToTheInvoicePage(invoiceName);
    }

    @Step
    public void addProductToInvoice(String invoiceName, String productSku, String quantity, String price, String userName) throws IOException, JSONException {
        invoiceApi.addProductToInvoice(invoiceName, productSku, quantity, price, userName);
    }

    @Step
    public void openInvoiceCreatePage() {
        invoiceCreatePage.open();
    }

    @Step
    public void openInvoiceListPage() {
        invoiceListPage.open();
    }

    @Step
    public void invoiceListItemCreate() {
        invoiceListPage.invoiceListItemCreate();
    }

    @Step
    public void input(String elementName, String inputText) {
        invoiceCreatePage.input(elementName, inputText);
    }

    @Step
    public void listItemCheck(String skuValue) {
        invoiceListPage.listItemCheck(skuValue);
    }

    @Step
    public void checkInvoiceListItemWithSkuHasExpectedValue(String skuValue, String elementName, String expectedValue) {
        invoiceListPage.checkInvoiceListItemWithSkuHasExpectedValue(skuValue, elementName, expectedValue);
    }

    @Step
    public void checkCardValue(String elementName, String expectedValue) {
        invoiceBrowsing.checkCardValue(elementName, expectedValue);
    }

    @Step
    public void checkCardValue(String checkType, String elementName, String expectedValue) {
        invoiceBrowsing.checkCardValue(checkType, elementName, expectedValue);
    }

    @Step
    public void checkCardValue(String checkType, ExamplesTable checkValuesTable) {
        invoiceBrowsing.checkCardValue(checkType, checkValuesTable);
    }

    @Step
    public void editButtonClick() {
        invoiceBrowsing.editButtonClick();
    }

    @Step
    public void listItemClick(String skuValue) {
        invoiceListPage.listItemClick(skuValue);
    }

    @Step
    public void generateTestCharData(String elementName, int charNumber) {
        String generatedData = commonPage.generateTestData(charNumber);
        input(elementName, generatedData);
    }

    @Step
    public void checkFieldLength(String elementName, int fieldLength) {
        invoiceCreatePage.checkFieldLength(elementName, fieldLength);
    }

    @Step
    public void checkTheDateisNowDate(String elementName) {
        String NowDate = DateTime.getTodayDate(DateTime.DATE_TIME_PATTERN);
        invoiceBrowsing.shouldContainsText(elementName, NowDate);
    }

    @Step
    public void goToTheaAdditionOfProductsLinkClick() {
        invoiceBrowsing.goToTheaAdditionOfProductsLinkClick();
    }

    @Step
    public void addOneMoreProductLinkClick() {
        invoiceBrowsing.addOneMoreProductLinkClick();
    }

    @Step
    public void invoiceProductListItemCheck(String value) {
        invoiceBrowsing.listItemCheck(value);
    }

    @Step
    public void invoiceProductListItemClick(String value) {
        invoiceBrowsing.listItemClick(value);
    }

    @Step
    public void checkListItemWithSkuHasExpectedValue(String value, ExamplesTable checkValuesTable) {
        invoiceBrowsing.checkListItemWithSkuHasExpectedValue(value, checkValuesTable);
    }

    @Step
    public void elementClick(String elementName) {
        invoiceBrowsing.elementClick(elementName);
    }

    @Step
    public void acceptChangesButtonClick() throws InterruptedException {
        invoiceBrowsing.acceptChangesButtonClick();
    }

    @Step
    public void discardChangesButtonClick() {
        invoiceBrowsing.discardChangesButtonClick();
    }

    @Step
    public void acceptDeleteButtonClick() throws InterruptedException {
        invoiceBrowsing.acceptDeleteButtonClick();
    }

    @Step
    public void discardDeleteButtonClick() {
        invoiceBrowsing.discardDeleteButtonClick();
    }

    @Step
    public void invoiceStopEditButtonClick() {
        invoiceBrowsing.writeOffStopEditButtonClick();
    }

    @Step
    public void invoiceStopEditlinkClick() {
        invoiceBrowsing.writeOffStopEditlinkClick();
    }

    @Step
    public void childrenElementClick(String elementName, String elementClassName) {
        invoiceBrowsing.childrenElementClick(elementName, elementClassName);
    }

    @Step
    public void childrentItemClickByFindByLocator(String parentElementName, String elementName) {
        invoiceBrowsing.childrentItemClickByFindByLocator(parentElementName, elementName);
    }

    @Step
    public void addNewInvoiceProductButtonClick() {
        invoiceBrowsing.addNewInvoiceProductButtonClick();
    }

    @Step
    public void childrenItemNavigateAndClickByFindByLocator(String elementName) {
        invoiceBrowsing.childrenItemNavigateAndClickByFindByLocator(elementName);
    }

    public void tryTochildrenItemNavigateAndClickByFindByLocator(String elementName) {
        invoiceBrowsing.tryTochildrenItemNavigateAndClickByFindByLocator(elementName);
    }

    @Step
    public void checkItemIsNotPresent(String elementName) {
        invoiceBrowsing.checkItemIsNotPresent(elementName);
    }

    @Step
    public void searchInput(String searchInput) {
        invoiceSearchPage.input("skuOrSupplierInvoiceSku", searchInput);
    }

    @Step
    public void searchButtonClick() {
        invoiceSearchPage.searchButtonClick();
    }

    @Step
    public void searchLinkClick() {
        invoiceLocalNavigation.searchLinkClick();
    }

    @Step
    public void checkFormResultsText(String text) {
        Assert.assertEquals(text, invoiceSearchPage.getFormResultsText());
    }

    @Step
    public void checkHasInvoice(String sku) {
        if (!invoiceSearchPage.getInvoiceSearchObjectHashMap().containsKey(sku)) {
            Assert.fail(String.format("There is no invoice with such sku '%s'", sku));
        }
    }

    @Step
    public void checkInvoiceProperties(String sku, ExamplesTable examplesTable) {
        InvoiceSearchObject invoiceSearchObject = invoiceSearchPage.getInvoiceSearchObjectHashMap().get(sku);
        for (Map<String, String> row : examplesTable.getRows()) {
            String elementName = row.get("elementName");
            String elementValue = row.get("elementValue");
            switch (elementName) {
                case "sku":
                    Assert.assertEquals(elementValue, invoiceSearchObject.getSku());
                    break;
                case "acceptanceDate":
                    Assert.assertEquals(elementValue, invoiceSearchObject.getAcceptanceDate());
                    break;
                case "supplier":
                    Assert.assertEquals(elementValue, invoiceSearchObject.getSupplier());
                    break;
                case "accepter":
                    Assert.assertEquals(elementValue, invoiceSearchObject.getAccepter());
                    break;
                case "legalEntity":
                    Assert.assertEquals(elementValue, invoiceSearchObject.getLegalEntity());
                    break;
                case "supplierInvoiceSku":
                    Assert.assertEquals(elementValue, invoiceSearchObject.getSupplierInvoiceSku());
                    break;
                case "supplierInvoiceDate":
                    Assert.assertEquals(elementValue, invoiceSearchObject.getSupplierInvoiceDate());
                    break;
                default:
                    Assert.fail(String.format("No such elementName '%s'", elementName));
                    break;
            }
        }
    }

    @Step
    public void searchResultClick(String sku) {
        invoiceSearchPage.searchResultClick(sku);
    }

    @Step
    public void checkHighlightsText(String expectedHighlightedText) {
        Boolean isEqual = false;
        for (String actualHighlightText : invoiceSearchPage.getHighlightTexts()) {
            if (actualHighlightText.equals(expectedHighlightedText)) {
                isEqual = true;
            }
        }
        if (!isEqual) {
            String errorMessage = String.format("Actual: '%s', Expected: '%s'", invoiceSearchPage.getHighlightTexts(), expectedHighlightedText);
            Assert.fail(errorMessage);
        }
    }
}
