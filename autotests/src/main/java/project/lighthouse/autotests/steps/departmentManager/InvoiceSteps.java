package project.lighthouse.autotests.steps.departmentManager;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.elements.DateTime;
import project.lighthouse.autotests.elements.preLoader.CheckBoxPreloader;
import project.lighthouse.autotests.pages.departmentManager.invoice.*;

public class InvoiceSteps extends ScenarioSteps {

    InvoiceCreatePage invoiceCreatePage;
    InvoiceListPage invoiceListPage;
    InvoiceBrowsing invoiceBrowsing;
    CommonPage commonPage;

    InvoiceSearchPage invoiceSearchPage;
    InvoiceLocalNavigation invoiceLocalNavigation;

    public InvoiceSteps(Pages pages) {
        super(pages);
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
    public void fieldInput(ExamplesTable examplesTable) {
        invoiceBrowsing.fieldInput(examplesTable);
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

    @Deprecated
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
        invoiceSearchPage.getInvoiceSearchObjectCollection().contains(sku);
    }

    @Step
    public void invoiceCompareWithExampleTable(ExamplesTable examplesTable) {
        invoiceSearchPage.getInvoiceSearchObjectCollection().compareWithExampleTable(examplesTable);

    }

    @Step
    public void searchResultClick(String sku) {
        invoiceSearchPage.getInvoiceSearchObjectCollection().clickByLocator(sku);
    }

    @Step
    public void checkHighlightsText(String expectedHighlightedText) {
        invoiceSearchPage.getInvoiceSearchObjectCollection().containsHighLightText(expectedHighlightedText);
    }

    @Step
    public void objectPropertyClick(String objectLocator, String objectPropertyName) {
        invoiceBrowsing.getInvoiceProductsCollection().clickPropertyByLocator(objectLocator, objectPropertyName);
    }

    @Step
    public void objectPropertyInput(String locator, String propertyName, String value) {
        invoiceBrowsing.getInvoiceProductsCollection().inputPropertyByLocator(locator, propertyName, value);
    }

    @Step
    public void compareWithExampleTable(ExamplesTable examplesTable) {
        invoiceBrowsing.getInvoiceProductsCollection().compareWithExampleTable(examplesTable);
    }

    @Step
    public void itemClick(String itemName) {
        invoiceBrowsing.itemClick(itemName);
    }

    @Step
    public void checkTheStateOfCheckBox(String itemName, String state) {
        String checkBoxState = invoiceBrowsing.getItemAttribute(itemName, "checked");
        switch (state) {
            case "checked":
                if (checkBoxState != null) {
                    if (!checkBoxState.equals("true")) {
                        Assert.fail("CheckBox is not checked!");
                    }
                } else {
                    Assert.fail("CheckBox is not checked!");
                }
                break;
            case "unChecked":
                if (checkBoxState != null) {
                    Assert.fail("CheckBox is not unchecked!");
                }
                break;
        }
    }

    @Step
    public void checkTheCheckBoxText(String itemName, String text) {
        String actualText = invoiceBrowsing.items.get(itemName).getVisibleWebElement().findElement(By.xpath(".//..")).getText();
        Assert.assertEquals(text, actualText);
    }

    @Step
    public void checkBoxPreLoaderWait() {
        new CheckBoxPreloader(getDriver()).await();
    }
}
