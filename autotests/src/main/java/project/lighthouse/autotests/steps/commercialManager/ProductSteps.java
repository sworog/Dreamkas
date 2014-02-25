package project.lighthouse.autotests.steps.commercialManager;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.helper.StringGenerator;
import project.lighthouse.autotests.pages.commercialManager.product.ProductCardView;
import project.lighthouse.autotests.pages.commercialManager.product.ProductCreatePage;
import project.lighthouse.autotests.pages.commercialManager.product.ProductListPage;
import project.lighthouse.autotests.pages.commercialManager.product.ProductLocalNavigation;
import project.lighthouse.autotests.pages.departmentManager.catalog.product.ProductInvoicesList;
import project.lighthouse.autotests.pages.departmentManager.catalog.product.ProductReturnList;
import project.lighthouse.autotests.pages.departmentManager.catalog.product.ProductWriteOffList;

public class ProductSteps extends ScenarioSteps {

    ProductCreatePage productCreatePage;
    ProductCardView productCardView;
    ProductListPage productListPage;
    ProductLocalNavigation productLocalNavigation;
    ProductInvoicesList productInvoicesList;
    ProductWriteOffList productWriteOffList;
    ProductReturnList productReturnList;

    @Step
    public void isTheProductCardOpen() {
        productCardView.open();
    }

    @Step
    public void fieldInput(String elementName, String inputText) {
        productCreatePage.input(elementName, inputText);
    }

    /**
     * The method is workaround for {@link #fieldInput(String, String)} method in 'Retail mark up range inheritance' scenario.
     * The test fails if {@link #fieldInput(String, String)} method in use.
     *
     * @param elementName
     * @param inputText
     */
    @Step
    public void fieldInputBySendKeysMethod(String elementName, String inputText) {
        productCreatePage.items.get(elementName).getVisibleWebElement().sendKeys(inputText);
    }

    @Step
    public void selectDropDown(String elementName, String value) {
        productCreatePage.selectByValue(elementName, value);
    }

    @Step
    public void createButtonClick() {
        productCreatePage.createButtonClick();
    }

    @Step
    public void checkCardValue(String elementName, String expectedValue) {
        productCardView.checkCardValue(elementName, expectedValue);
    }

    @Step
    public void checkCardValue(ExamplesTable checkValuesTable) {
        productCardView.checkCardValue(checkValuesTable);
    }

    @Step
    public void createNewProductButtonClick() {
        productListPage.createNewProductButtonClick();
    }

    @Step
    public void listItemClick(String skuValue) {
        productListPage.listItemClick(skuValue);
    }

    @Step
    public void listItemCheck(String skuValue) {
        productListPage.listItemCheck(skuValue);
    }

    @Step
    public void listItemCheckIsNotPresent(String skuValue) {
        productListPage.listItemCheckIsNotPresent(skuValue);
    }

    @Step
    public void checkProductWithSkuHasExpectedValue(String skuValue, String name, String expectedValue) {
        productListPage.checkProductWithSkuHasExpectedValue(skuValue, name, expectedValue);
    }

    @Step
    public void checkDropDownDefaultValue(String dropDownType, String expectedValue) {
        productCreatePage.checkDropDownDefaultValue(dropDownType, expectedValue);
    }

    @Step
    public void editButtonClick() {
        productCardView.editButtonClick();
    }

    @Step
    public void editProductButtonClick() {
        productCardView.editProductButtonClick();
    }

    @Step
    public void editProductButtonIsNotPresent() {
        try {
            productCardView.editProductButtonClick();
            Assert.fail("Edit product button is clicked and present!");
        } catch (Exception ignored) {
        }
    }

    @Step
    public void fieldType(ExamplesTable fieldInputTable) {
        productCreatePage.fieldInput(fieldInputTable);
    }

    @Step
    public void checkFieldLength(String elementName, int fieldLength) {
        productCreatePage.checkFieldLength(elementName, fieldLength);
    }

    @Step
    public void generateTestCharData(String elementName, int charNumber) {
        String generatedData = new StringGenerator(charNumber).generateTestData();
        fieldInput(elementName, generatedData);
    }

    @Step
    public void elementClick(String elementName) {
        productCreatePage.itemClick(elementName);
    }

    @Step
    public void checkElementPresence(String elementName, String action) {
        productCreatePage.checkElementPresence(elementName, action);
    }

    @Step
    public void retailPriceHintClick() {
        productCreatePage.retailPriceHintClick();
    }

    @Step
    public void checkElementIsDisabled(String elementName) {
        productCreatePage.checkElementIsDisabled(elementName);
    }

    @Step
    public void checkDropDownDefaultValue(String expectedValue) {
        productCreatePage.checkDropDownDefaultValue(expectedValue);
    }

    @Step
    public void productInvoicesLinkClick() {
        productLocalNavigation.productInvoicesLinkClick();
    }

    @Step
    public void checkProductInvoiceListObject(ExamplesTable examplesTable) {
        productInvoicesList.getProductInvoiceListObjects().compareWithExampleTable(examplesTable);
    }

    @Step
    public void productInvoiceListClick(String sku) {
        productInvoicesList.invoiceSkuClick(sku);
    }

    @Step
    public void productWriteOffsLinkClick() {
        productLocalNavigation.productWriteOffsLinkClick();
    }

    @Step
    public void productWriteOffLinkIsNotPresent() {
        try {
            productLocalNavigation.productWriteOffsLinkClick();
            Assert.fail("the product local navigation writeoffs link is present!");
        } catch (Exception ignored) {
        }
    }

    @Step
    public void checkProductWriteOffListObject(ExamplesTable examplesTable) {
        productWriteOffList.getProductInvoiceListObjects().compareWithExampleTable(examplesTable);
    }

    @Step
    public void productWriteOffListObjectClick(String number) {
        productWriteOffList.productWriteOffListObjectClick(number);
    }

    @Step
    public void productReturnsLinkClick() {
        productLocalNavigation.productReturnsLinkClick();
    }

    @Step
    public void productReturnsTabIsNotVisible() {
        try {
            productLocalNavigation.productReturnsLinkClick();
            Assert.fail("Products return tab is visible!");
        } catch (Exception ignored) {
        }
    }

    @Step
    public void checkProductReturnListObject(ExamplesTable examplesTable) {
        productReturnList.getReturnListObjectCollection().compareWithExampleTable(examplesTable);
    }
}
