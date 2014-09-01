package project.lighthouse.autotests.jbehave.product;


import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.product.ProductSteps;

public class ThenProductSteps {

    @Steps
    ProductSteps productSteps;

    @Then("the user asserts the product list contain products with values $examplesTable")
    @Alias("пользователь проверяет, что список продуктов содержит продукты с данными $examplesTable")
    public void thenTheUserAssertsTheProductListContainProductsWithValues(ExamplesTable examplesTable) {
        productSteps.productCollectionCompareWithExampleTable(examplesTable);
    }

    @Then("the user asserts the product list contain exact products with values $examplesTable")
    public void thenTheUserAssertsTheProductListContainExactProductsWithValues(ExamplesTable examplesTable) {
        productSteps.productCollectionExactCompareWithExampleTable(examplesTable);
    }

    @Then("the user checks stored values in edit product modal window")
    public void thenTheUserChecksStoredValuesInEditProductModalWindow() {
        productSteps.editProductModalWindowCheckStoredValues();
    }

    @Then("пользователь проверяет заполненные поля в модальном окне редактирования товара $examplesTable")
    public void thenTheUserChecksStoredValuesInEditProductModalWindow(ExamplesTable examplesTable) {
        productSteps.editProductModalWindowCheckValues(examplesTable);
    }

    @Then("the user asserts the group field value is '$value'")
    public void thenTheUserAssertsTheGroupFieldValue(String value) {
        productSteps.assertCreateNewProductModalWindowGroupFieldValue(value);
    }

    @Then("the user asserts the groups list not contain product with name '$name'")
    public void thenTheUserAssertsTheGroupsListNotContainProductWithName(String name) {
        productSteps.productCollectionNotContainProductWithName(name);
    }

    @Then("the user asserts the groups list contain product with name '$name'")
    public void thenTheUserAssertsTheGroupsListContainProductWithName(String name) {
        productSteps.productCollectionContainProductWithName(name);
    }

    @Then("the user asserts the groups list contain product with storedName")
    public void thenTheUserAssertsTheGroupsListContainProductWithStoedName() {
        productSteps.productCollectionContainProductWithStoredName();
    }

    @Then("the user asserts the create product modal window title is '$title'")
    public void thenTheUserAssertsTheCreateProductModalWindowsTitle(String title) {
        productSteps.assertCreateNewProductModalWindowTitle(title);
    }

    @Then("the user asserts the edit product modal window title is '$title'")
    public void thenTheUserAssertsTheEditProductModalWindowsTitle(String title) {
        productSteps.assertEditProductModalWindowTitle(title);
    }

    @Then("the user asserts markUp value is '$value' in create new product window")
    public void thenTheUserAssertsMarkUpValueCreateNewProductWindow(String value) {
        productSteps.assertCreateNewProductModalWindowMarkUpValue(value);
    }

    @Then("the user asserts markUp value is '$value' in edit product window")
    public void thenTheUserAssertsMarkUpValueEditProductWindow(String value) {
        productSteps.assertEditProductModalWindowMarkUpValue(value);
    }

    @Then("the user asserts markUp value is not visible in create new product window")
    public void thenTheUserAssertsMarkUpValueIsNotVisibleInCreateNewProductWindow() {
        productSteps.assertCreateNewProductModalWindowMarkUpIsNotVisible();
    }

    @Then("the user asserts markUp value is not visible in edit product window")
    public void thenTheUserAssertsMarkUpValueIsNotVisibleInEditProductWindow() {
        productSteps.assertEditProductModalWindowMarkUpIsNotVisible();
    }

    @Then("the user checks the create new product modal window '$elementName' field has error message with text '$errorMessage'")
    @Alias("the user checks the create new product modal window '$elementName' field has errorMessage")
    public void thenTheUserChecksTheCreateNewProductModalWindowFieldHasErrorMessage(String elementName, String errorMessage) {
        productSteps.assertCreateNewProductModalWindowFieldErrorMessage(elementName, errorMessage);
    }

    @Then("the user checks the edit product modal window '$elementName' field has error message with text '$errorMessage'")
    @Alias("the user checks the edit product modal window '$elementName' field has errorMessage")
    public void thenTheUserChecksTheEditProductModalWindowFieldHasErrorMessage(String elementName, String errorMessage) {
        productSteps.assertEditProductModalWindowFieldErrorMessage(elementName, errorMessage);
    }

    @Then("user checks delete button label '$label'")
    public void userChecksDeleteButtonLabel(String label) {
        productSteps.assertDeleteButtonLabel(label);
    }
}
