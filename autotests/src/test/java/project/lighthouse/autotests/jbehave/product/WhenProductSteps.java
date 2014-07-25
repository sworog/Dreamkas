package project.lighthouse.autotests.jbehave.product;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.product.ProductSteps;

public class WhenProductSteps {

    @Steps
    ProductSteps productSteps;

    @When("the user clicks on create product button on group page")
    public void whenTheUserClicksOnCreateProductButtonOnGroupPage() {
        productSteps.createNewProductButtonClick();
    }

    @When("the user inputs values in create new product modal window $examplesTable")
    public void whenTheUserInputsValuesInCreateNewProductModalWindow(ExamplesTable examplesTable) {
        productSteps.createNewProductModalWindowInput(examplesTable);
    }

    @When("the user inputs values in edit product modal window $examplesTable")
    public void whenTheUserInputsValuesInEditProductModalWindow(ExamplesTable examplesTable) {
        productSteps.editNewProductModalWindowInput(examplesTable);
    }

    @When("the user confirms OK in create new product modal window")
    public void whenTheUserConfirmsOKInCreateNewProductModalWindow() {
        productSteps.createNewProductModalWindowConfirmOkClick();
    }

    @When("the user confirms OK in edit product modal window")
    public void whenTheUserConfirmsOKInEditProductModalWindow() {
        productSteps.editProductModalWindowConfirmOkClick();
    }

    @When("the user clicks on the product with name '$name'")
    public void whenTheUserClicksOnTheProductWithName(String name) {
        productSteps.productCollectionProductWithNameClick(name);
    }

    @When("the user clicks on the product with stored name")
    public void whenTheUserClicksOnTheProductWithStoredName() {
        productSteps.productCollectionProductWithNameClickOnProductWithStoredName();
    }

    @When("the user clicks on close icon in create new product modal window")
    public void whenTheUserClicksOnCloseIconInCreateNewProductModalWindow() {
        productSteps.createNewProductModalWindowCloseIconClick();
    }

    @When("the user clicks on close icon in edit product modal window")
    public void whenTheUserClicksOnCloseIconInEditProductModalWindow() {
        productSteps.editProductModalWindowCloseIconClick();
    }

    @When("the user clicks on delete product button in edit product modal window")
    public void whenTheUserClicksOnDeleteProductButtonClickInEditProductModalWindow() {
        productSteps.deleteButtonClick();
    }

    @When("the user clicks on delete product confirm button in edit product modal window")
    public void whenTheUserClicksOnDeleteProductConfirmButtonInEditProductModalWindow() {
        productSteps.confirmDeleteButtonClick();
    }

    @When("the user generates symbols with count '$count' in the edit product modal window '$elementName' field")
    public void whenTheUserGeneratesSymbolsWithCountInEditProductModalWindowField(int count, String elementName) {
        productSteps.editProductModalWindowFieldGenerateText(elementName, count);
    }

    @When("the user generates symbols with count '$count' in the create new product modal window '$elementName' field")
    public void whenTheUserGeneratesSymbolsWithCountInCreateNewProductModalWindowField(int count, String elementName) {
        productSteps.createNewProductModalWindowFieldGenerateText(elementName, count);
    }

    @When("the user inputs value in create new product modal windows '$elementName' field")
    public void whenTheUserImputsInCreateNewProductModelWindowField(String value, String elementName) {
        productSteps.createNewProductModalWindowInput(elementName, value);
    }

    @When("the user inputs value in edit product modal windows '$elementName' field")
    public void whenTheUserImputsInEditProductModelWindowField(String value, String elementName) {
        productSteps.editProductModalWindowInput(elementName, value);
    }

    @When("the user sorts the product list by name")
    public void whenTheUserSortsTheProductListByName() {
        productSteps.sortByNameClick();
    }

    @When("the user sorts the product list by sellingPrice")
    public void whenTheUserSortsTheProductListBySellingPrice() {
        productSteps.sortBySellingPriceClick();
    }

    @When("the user sorts the product list by barcode")
    public void whenTheUserSortsTheProductListByBarCode() {
        productSteps.sortByBarcodeClick();
    }
}
