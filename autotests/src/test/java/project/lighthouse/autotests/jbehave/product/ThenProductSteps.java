package project.lighthouse.autotests.jbehave.product;


import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.product.ProductSteps;

public class ThenProductSteps {

    @Steps
    ProductSteps productSteps;

    @Then("the user asserts the product list contain products with values $examplesTable")
    public void thenTheUserAssertsTheProductListContainProductsWithValues(ExamplesTable examplesTable) {
        productSteps.productCollectionCompareWithExampleTable(examplesTable);
    }

    @Then("the user checks stored values in edit product modal window")
    public void thenTheUserChecksStoredValuesInEditProductModalWindow() {
        productSteps.editProductModalWindowCheckStoredValues();
    }

    @Then("the user asserts the groups list not contain product with name '$name'")
    public void thenTheUserAssertsTheGroupsListNotContainProductWithName(String name) {
        productSteps.productCollectionNotContainProductWithName(name);
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
}
