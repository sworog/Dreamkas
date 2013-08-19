package project.lighthouse.autotests.steps.commercialManager;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.objects.Category;
import project.lighthouse.autotests.objects.Group;
import project.lighthouse.autotests.objects.SubCategory;
import project.lighthouse.autotests.pages.commercialManager.catalog.CatalogApi;
import project.lighthouse.autotests.pages.commercialManager.product.*;

import java.io.IOException;

public class ProductSteps extends ScenarioSteps {

    ProductCreatePage productCreatePage;
    ProductEditPage productEditPage;
    ProductCardView productCardView;
    ProductListPage productListPage;
    CommonPage commonPage;
    ProductApi productApi;
    CatalogApi catalogApi;

    public ProductSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void createProductPostRequestSend(String name, String sku, String barcode, String units, String purchasePrice) throws JSONException, IOException {
        productApi.сreateProductThroughPost(name, sku, barcode, units, purchasePrice);
    }

    @Step
    public void createProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice, String subCategoryName) throws JSONException, IOException {
        productApi.createProductThroughPost(name, sku, barcode, units, purchasePrice, subCategoryName);
    }

    @Step
    public void createProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice,
                                         String groupName, String categoryName, String subCategoryName) throws IOException, JSONException {
        productApi.createProductThroughPost(name, sku, barcode, units, purchasePrice, groupName, categoryName, subCategoryName);
    }

    @Step
    public void navigateToTheProductPage(String productSku) throws JSONException {
        productApi.navigateToTheProductPage(productSku);
    }

    @Step
    public void openProductCreatePage() throws JSONException, IOException {
        catalogApi.createSubCategoryThroughPost(Group.DEFAULT_NAME, Category.DEFAULT_NAME, SubCategory.DEFAULT_NAME);
        catalogApi.navigateToSubCategoryProductCreatePageUrl(SubCategory.DEFAULT_NAME);
    }

    @Step
    public void openProductListPage() throws IOException, JSONException {
        catalogApi.createSubCategoryThroughPost(Group.DEFAULT_NAME, Category.DEFAULT_NAME, SubCategory.DEFAULT_NAME);
        catalogApi.navigateToSubCategoryProductListPageUrlWihEditModeOn(SubCategory.DEFAULT_NAME, Category.DEFAULT_NAME, Group.DEFAULT_NAME);
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

    @Step
    public void retailPriceHintClick() {
        productCreatePage.retailPriceHintClick();
    }
}
