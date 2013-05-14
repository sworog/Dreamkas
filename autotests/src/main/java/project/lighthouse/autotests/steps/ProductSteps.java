package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.pages.product.ProductCardView;
import project.lighthouse.autotests.pages.product.ProductCreatePage;
import project.lighthouse.autotests.pages.product.ProductEditPage;
import project.lighthouse.autotests.pages.product.ProductListPage;

public class ProductSteps extends ScenarioSteps {

    ProductCreatePage productCreatePage;
    ProductEditPage productEditPage;
    ProductCardView productCardView;
    ProductListPage productListPage;
    CommonPage commonPage;

    public ProductSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void isTheProductCreatePage() {
        productCreatePage.open();
    }

    @Step
    public void isTheProductEditPage() {
        productEditPage.open();
    }

    @Step
    public void isTheProductCardViewPage() {
        productCardView.open();
    }

    @Step
    public void isTheProductListPageOpen() {
        productListPage.open();
    }

    @Step
    public void isTheProductCardOpen() {
        productCardView.open();
    }

    @Step
    public void fieldInput(String elementName, String inputText) {
        productCreatePage.input(elementName, inputText);
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
    public void cancelButtonClick() {
        productEditPage.cancelButtonClick();
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
    public void fieldType(ExamplesTable fieldInputTable) {
        productCreatePage.fieldInput(fieldInputTable);
    }

    @Step
    public void checkFieldLength(String elementName, int fieldLength) {
        productCreatePage.checkFieldLength(elementName, fieldLength);
    }

    @Step
    public void generateTestCharData(String elementName, int charNumber) {
        String generatedData = commonPage.generateTestData(charNumber);
        fieldInput(elementName, generatedData);
    }

    @Step
    public void elementClick(String elementName) {
        productCreatePage.elementClick(elementName);
    }

    @Step
    public void checkElementPresence(String elementName, String action) {
        productCreatePage.checkElementPresence(elementName, action);
    }
}
