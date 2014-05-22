package project.lighthouse.autotests.steps.commercialManager;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.preLoader.PreLoader;
import project.lighthouse.autotests.helper.StringGenerator;
import project.lighthouse.autotests.objects.web.product.ProductObject;
import project.lighthouse.autotests.objects.web.product.barcodes.BarcodeObject;
import project.lighthouse.autotests.pages.commercialManager.product.*;
import project.lighthouse.autotests.pages.departmentManager.catalog.product.ProductInvoicesList;
import project.lighthouse.autotests.pages.departmentManager.catalog.product.ProductReturnList;
import project.lighthouse.autotests.pages.departmentManager.catalog.product.ProductWriteOffList;

import static org.hamcrest.Matchers.is;
import static org.hamcrest.Matchers.notNullValue;
import static org.junit.Assert.assertThat;
import static org.junit.Assert.fail;

public class ProductSteps extends ScenarioSteps {

    ProductCreatePage productCreatePage;
    ProductCardView productCardView;
    ProductListPage productListPage;
    ProductLocalNavigation productLocalNavigation;
    ProductInvoicesList productInvoicesList;
    ProductWriteOffList productWriteOffList;
    ProductReturnList productReturnList;
    ExtraBarcodesPage extraBarcodesPage;

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
        productCreatePage.getItems().get(elementName).getVisibleWebElement().sendKeys(inputText);
    }

    @Step
    public void selectProductType(String value) {
        productCreatePage.getItems().get("type").setValue(value);
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
    public void listItemClick(String name) {
        productListPage.getProductObjectCollection().clickByLocator(name);
    }

    @Step
    public void listItemCheck(String name) {
        productListPage.getProductObjectCollection().contains(name);
    }

    @Step
    public void checkProductWithSkuHasExpectedValue(String name, String element, String expectedValue) {
        ProductObject productObject = (ProductObject) productListPage.getProductObjectCollection().getAbstractObjectByLocator(name);
        Assert.assertEquals(expectedValue, productObject.getPurchasePrice());
    }

    @Step
    public void checkDropDownDefaultValue(String dropDownType, String expectedValue) {
        String selectedValue = productCreatePage.getItems().get(dropDownType).$().getSelectedValue();
        String message = String.format("The default value for '%s' dropDown is not '%s'. The selected value is '%s'", dropDownType, expectedValue, selectedValue);
        assertThat(message, selectedValue, is(expectedValue));
    }

    @Step
    public void editButtonClick() {
        productCardView.editButtonClick();
    }

    @Step
    public void editProductMarkUpAndPriceButtonClick() {
        productCardView.editProductMarkUpAndPriceButtonClick();
    }

    @Step
    public void editProductMarkUpAndPriceButtonIsNotPresent() {
        try {
            productCardView.editProductMarkUpAndPriceButtonClick();
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
        switch (action) {
            case "is":
                productCreatePage.getItems().get(elementName).$().shouldBeVisible();
                break;
            case "is not":
                productCreatePage.getItems().get(elementName).$().shouldNotBeVisible();
                break;
            default:
                Assert.fail(CommonPage.ERROR_MESSAGE);
        }
    }

    @Step
    public void retailPriceHintClick() {
        productCreatePage.retailPriceHintClick();
    }

    @Step
    public void checkElementIsDisabled(String elementName) {
        assertThat(
                "The disabled attribute is not present in the element",
                productCreatePage.getItems().get(elementName).getWebElement().getAttribute("disabled"), notNullValue());
    }

    @Step
    public void checkDropDownDefaultValue(String expectedValue) {
        WebElement element = productCreatePage.getItems().get("rounding").getVisibleWebElement();
        productCreatePage.getCommonActions().checkDropDownDefaultValue(element, expectedValue);
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
        productInvoicesList.getProductInvoiceListObjects().clickByLocator(sku);
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

    @Step
    public void editProductButtonIsNotPresent() {
        try {
            productCardView.editButtonClick();
            Assert.fail("Edit product link is present!");
        } catch (Exception ignored) {
        }
    }

    @Step
    public void newProductCreateButtonIsNotPresent() {
        try {
            productListPage.createNewProductButtonClick();
            fail("Create new product button is present on product list page!");
        } catch (Exception ignored) {
        }
    }

    @Step
    public void productObjectClickByLocator(String locator) {
        productListPage.getProductObjectCollection().clickByLocator(locator);
    }

    @Step
    public void productObjectCollectionContainObjectWithLocator(String locator) {
        productListPage.getProductObjectCollection().contains(locator);
    }

    @Step
    public void productListObjectCollectionCompareWithExamplesTable(ExamplesTable examplesTable) {
        productListPage.getProductObjectCollection().compareWithExampleTable(examplesTable);
    }

    @Step
    public void assertProductListObjectPurchasePrice(String locator, String expectedValue) {
        ProductObject productObject = (ProductObject)
                productListPage.getProductObjectCollection().getAbstractObjectByLocator(locator);
        assertThat(productObject.getPurchasePrice(), is(expectedValue));
    }

    @Step
    public void assertProductListObjectSku(String locator, String sku) {
        ProductObject productObject = (ProductObject)
                productListPage.getProductObjectCollection().getAbstractObjectByLocator(locator);
        assertThat(productObject.getSku(), is(sku));
    }

    @Step
    public void assertSkuFieldIsNotVisible() {
        ((Input) productCreatePage.getItems().get("sku")).shouldBeNotVisible();
    }

    @Step
    public void assertFieldErrorMessage(String elementName, String expectedErrorMessage) {
        productCreatePage.getItems().get(elementName).getFieldErrorMessageChecker().assertFieldErrorMessage(expectedErrorMessage);
    }

    @Step
    public void barCodesLinkClick() {
        productLocalNavigation.barCodesLinkClick();
    }

    @Step
    public void addBarcodeButtonClick() {
        extraBarcodesPage.getAddBarcodeButton().click();
        new PreLoader(getDriver()).await();
    }

    @Step
    public void saveBarcodeButtonClick() {
        extraBarcodesPage.getSaveBarcodeButton().click();
        new PreLoader(getDriver()).await();
    }

    @Step
    public void cancelBarcodeSaveLinkClick() {
        extraBarcodesPage.getCancelLink().click();
    }

    @Step
    public void barcodePageInput(ExamplesTable examplesTable) {
        extraBarcodesPage.fieldInput(examplesTable);
    }

    @Step
    public void barcodeCollectionExactCompareWithExamplesTable(ExamplesTable examplesTable) {
        extraBarcodesPage.getBarcodeObjectCollection().exactCompareExampleTable(examplesTable);
    }

    @Step
    public void barcodeObjectBarcodeType(String locator, String value) {
        ((BarcodeObject) extraBarcodesPage.getBarcodeObjectCollection().getAbstractObjectByLocator(locator))
                .barcodeFieldType(value);
    }

    @Step
    public void quantityObjectBarcodeType(String locator, String value) {
        ((BarcodeObject) extraBarcodesPage.getBarcodeObjectCollection().getAbstractObjectByLocator(locator))
                .quantityFieldType(value);
    }

    @Step
    public void priceObjectBarcodeType(String locator, String value) {
        ((BarcodeObject) extraBarcodesPage.getBarcodeObjectCollection().getAbstractObjectByLocator(locator))
                .priceFieldType(value);
    }

    @Step
    public void priceObjectDelete(String locator) {
        ((BarcodeObject) extraBarcodesPage.getBarcodeObjectCollection().getAbstractObjectByLocator(locator))
                .deleteButtonClick();
    }

    @Step
    public void addExtraBarcodeButtonShouldBeNotVisible() {
        extraBarcodesPage.getAddBarcodeButton().shouldBeNotVisible();
    }

    @Step
    public void saveExtraBarcodeButtonShouldBeNotVisible() {
        extraBarcodesPage.getSaveBarcodeButton().shouldBeNotVisible();
    }

    @Step
    public void cancelSaveExtraBarcodeLinkShouldBeNotVisible() {
        extraBarcodesPage.getCancelLink().shouldBeNotVisible();
    }

    @Step
    public void elementShouldBeNotVisible(String elementName) {
        extraBarcodesPage.elementShouldBeNotVisible(elementName);
    }
}
