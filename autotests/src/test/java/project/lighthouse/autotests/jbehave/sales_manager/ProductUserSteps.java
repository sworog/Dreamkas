package project.lighthouse.autotests.jbehave.sales_manager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.ProductSteps;

public class ProductUserSteps {

    @Steps
    ProductSteps productSteps;

    @Given("the user is on the product create page")
    public void givenTheUserIsOnTheOrderCreatePage() {
        productSteps.isTheProductCreatePage();
    }

    @Given("the user is on the order edit page")
    public void givenTheUserIsOnTheOrderEditPage() {
        productSteps.isTheProductEditPage();
    }

    @Given("the user is on the order card view")
    public void givenTheUserIsOnTheOrderCardView() {
        productSteps.isTheProductCardViewPage();
    }

    @Given("the user is on the product list page")
    public void givenTheUserIsOnTheProductListPage() {
        productSteps.isTheProductListPageOpen();
    }

    @Given("the user is on the product card")
    public void givenTheUserIsOnTheProductCard() {
        productSteps.isTheProductCardOpen();
    }

    @When("the user inputs '$inputText' in '$elementName' field")
    public void whenTheUserInputsTextInTheField(String inputText, String elementName) {
        productSteps.fieldInput(elementName, inputText);
    }

    @When("the user inputs values in element fields $fieldInputTable")
    public void whenTheUserInputsInElementFields(ExamplesTable fieldInputTable) {
        productSteps.fieldType(fieldInputTable);
    }

    @When("the user selects '$value' in '$elementName' dropdown")
    public void whenTheUserSelectsValueInDropDown(String value, String elementName) {
        productSteps.selectDropDown(elementName, value);
    }

    @When("the user clicks the create button")
    public void whenTheUserClicksOnCreateButton() {
        productSteps.createButtonClick();
    }

    @When("the user clicks the edit button")
    public void whenTheUserClicksOnEditButton() {
        productSteps.createButtonClick();
    }

    @When("the user clicks the cancel button")
    public void whenTheUserClickCancelEditButton() {
        productSteps.cancelButtonClick();
    }

    @When("the user creates new product from product list page")
    public void whenTheUSerCreatesNewProduct() {
        productSteps.createNewProductButtonClick();
    }

    @When("the user open the product card with '$skuValue' sku")
    public void whenTheUserOpenTheProductCard(String skuValue) {
        productSteps.listItemClick(skuValue);
    }

    @When("the user clicks the edit button on product card view page")
    public void whenTheUserClicksTheEditButtonOnProductCardViewPage() {
        productSteps.editButtonClick();
    }

    @When("the user generates charData with '$charNumber' number in the '$elementName' field")
    public void whenTheUserGeneratesCharData(String elementName, int charNumber) {
        productSteps.generateTestCharData(elementName, charNumber);
    }

    @When("the user clicks '$elementName' to make it avalaible")
    @Alias("the user clicks '$elementName' element")
    public void whenTheUserClicksElement(String elementName) {
        productSteps.elementClick(elementName);
    }

    @Then("the user checks the '$elementName' value is '$expectedValue'")
    public void thenTheUserChecksValue(String elementName, String expectedValue) {
        productSteps.checkCardValue(elementName, expectedValue);
    }

    @Then("the user checks the elements values $checkValuesTable")
    public void thenTheUserChecksTheElementValues(ExamplesTable checkValuesTable) {
        productSteps.checkCardValue(checkValuesTable);
    }

    @Then("the user checks the product with '$skuValue' sku is present")
    public void thenTheUSerChecksTheProductWithSkuIsPresent(String skuValue) {
        productSteps.listItemCheck(skuValue);
    }

    @Then("the user checks the product with '$skuValue' sku is not present")
    public void thenTheUSerChecksTheProductWithSkuIsNotPresent(String skuValue) {
        productSteps.listItemCheckIsNotPresent(skuValue);
    }

    @Then("the user checks the product with '$skuValue' sku has '$name' equal to '$expectedValue'")
    public void checkProductWithSkuHasExpectedValue(String skuValue, String name, String expectedValue) {
        productSteps.checkProductWithSkuHasExpectedValue(skuValue, name, expectedValue);
    }

    @Then("the user checks default value for '$dropDownType' dropdown equal to '$expectedValue'")
    public void thenTheUSerChecksDefaultValueForDropDown(String dropDownType, String expectedValue) {
        productSteps.checkDropDownDefaultValue(dropDownType, expectedValue);
    }

    @Then("the user checks '$elementName' field contains only '$fieldLength' symbols")
    public void thenTheUserChecksNameFieldContainsOnlyExactSymbols(String elementName, int fieldLength) {
        productSteps.checkFieldLength(elementName, fieldLength);
    }

    @Then("the user checks '$elementName' '$action' avalaible")
    public void thenUSerChecksElementAvalaiblity(String elementName, String action) {
        productSteps.checkElementPresence(elementName, action);
    }
}
