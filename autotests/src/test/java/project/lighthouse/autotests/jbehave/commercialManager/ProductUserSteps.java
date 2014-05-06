package project.lighthouse.autotests.jbehave.commercialManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.*;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.objects.api.Category;
import project.lighthouse.autotests.objects.api.Group;
import project.lighthouse.autotests.objects.api.SubCategory;
import project.lighthouse.autotests.steps.api.commercialManager.CatalogApiSteps;
import project.lighthouse.autotests.steps.commercialManager.ProductSteps;

import java.io.IOException;

public class ProductUserSteps {

    @Steps
    ProductSteps productSteps;

    @Steps
    CatalogApiSteps catalogApiSteps;

    ExamplesTable fieldInputTable;

    @Given("the user is on the product create page")
    public void givenTheUserIsOnTheOrderCreatePage() throws JSONException, IOException {
        catalogApiSteps.createSubCategoryThroughPost(Group.DEFAULT_NAME, Category.DEFAULT_NAME, SubCategory.DEFAULT_NAME);
        catalogApiSteps.navigateToSubCategoryProductCreatePageUrl(SubCategory.DEFAULT_NAME);
    }

    @Given("the user is on the product list page")
    public void givenTheUserIsOnTheProductListPage() throws IOException, JSONException {
        catalogApiSteps.createSubCategoryThroughPost(Group.DEFAULT_NAME, Category.DEFAULT_NAME, SubCategory.DEFAULT_NAME);
        catalogApiSteps.navigateToSubCategoryProductListPageUrlWihEditModeOn(SubCategory.DEFAULT_NAME, Category.DEFAULT_NAME, Group.DEFAULT_NAME);
    }

    @Given("the user is on the product card")
    public void givenTheUserIsOnTheProductCard() {
        productSteps.isTheProductCardOpen();
    }

    @When("the user inputs '$value' in '$elementName' field")
    public void whenTheUserInputsTextInTheField(String value, String elementName) {
        productSteps.fieldInput(elementName, value);
    }

    @When("the user inputs '$value' in '$element' element field")
    public void whenTheUserInputsTextInTheFieldAlias(String value, String element) {
        productSteps.fieldInput(element, value);
    }

    @When("the user inputs <productName> in name element field")
    public void whenTheUserInputsTextInTheNameField(String productName) {
        productSteps.fieldInput("name", productName);
    }

    @When("the user inputs <exampleValue> in <exampleElement> element field")
    public void whenTheUserInputsTextInTheFieldExampleAlias(String exampleValue, String exampleElement) {
        productSteps.fieldInput(exampleElement, exampleValue);
    }

    @When("the user inputs '$value' in '$elementName' field by sendKeys method")
    public void whenTheUserInputsTextInTheFieldBySendKeysMethods(String value, String elementName) {
        productSteps.fieldInputBySendKeysMethod(elementName, value);
    }

    @When("the user inputs <inputText> in <elementName> field")
    public void aliasTheUserInputsTextInTheField(String inputText, String elementName) {
        productSteps.fieldInput(elementName, inputText);
    }

    @When("the user inputs <value> in <elementName> field")
    public void aliasTheUserInputsValueInTheField(String value, String elementName) {
        productSteps.fieldInput(elementName, value);
    }

    @When("the user inputs <value> in sku field")
    public void whenTheUserInputsValueInSkuField(String value) {
        productSteps.fieldInput("sku", value);
    }

    @When("the user inputs values in element fields $fieldInputTable")
    public void whenTheUserInputsInElementFields(ExamplesTable fieldInputTable) {
        productSteps.fieldType(fieldInputTable);
        this.fieldInputTable = fieldInputTable;
    }

    @Then("the user checks the stored input values")
    public void thenTheUserChecksTheStoredInputValues() {
        thenTheUserChecksTheElementValues(fieldInputTable);
    }

    @When("the user selects product type '$value'")
    public void whenTheUserSelectsValueInDropDown(String value) {
        productSteps.selectProductType(value);
    }

    @When("the user selects '$value' in '$elementName' dropdown")
    public void whenTheUserSelectsValueInDropDown(String value, String elementName) {
        productSteps.selectDropDown(elementName, value);
    }

    @When("the user selects '$value' in '$element' element dropdown")
    public void whenTheUserSelectsValueInDropDownAlias(String value, String element) {
        productSteps.selectDropDown(element, value);
    }

    @When("the user clicks the create button")
    public void whenTheUserClicksOnCreateButton() {
        productSteps.createButtonClick();
    }

    @When("the user clicks the edit button")
    public void whenTheUserClicksOnEditButton() {
        productSteps.createButtonClick();
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

    @When("the user clicks the edit price button")
    public void whenTheUserClicksTheEditPriceButton() {
        productSteps.editProductMarkUpAndPriceButtonClick();
    }

    @Then("the user checks the edit price button is not present")
    public void thenUserChecksTheEditPriceButtonIsNotPresent() {
        productSteps.editProductMarkUpAndPriceButtonIsNotPresent();
    }

    @When("the user generates charData with '$charNumber' number in the '$elementName' field")
    @Alias("the user generates charData with <charNumber> number in the <elementName> field")
    public void whenTheUserGeneratesCharData(String elementName, int charNumber) {
        productSteps.generateTestCharData(elementName, charNumber);
    }

    @When("the user clicks '$elementNameToClick' to make it avalaible")
    @Alias("the user clicks '$elementNameToClick' element")
    public void whenTheUserClicksElement(String elementNameToClick) {
        productSteps.elementClick(elementNameToClick);
    }

    @When("the user clicks retailPriceHint to make retailPrice available")
    public void whenTheUserClicksHintToMakeOtherHintAvailable() {
        productSteps.retailPriceHintClick();
    }

    @Then("the user checks '$elementName' element is disabled")
    public void whenTheUserChecksElementIsDisabled(String elementName) {
        productSteps.checkElementIsDisabled(elementName);
    }

    @Then("the user checks the '$elementName' value is '$expectedValue'")
    @Alias("the user checks the <elementName> value is <expectedValue>")
    public void thenTheUserChecksValue(String elementName, String expectedValue) {
        productSteps.checkCardValue(elementName, expectedValue);
    }

    @Then("the user checks the <elementName> value is <value>")
    public void thenAliasTheUserChecksValue(String elementName, String value) {
        productSteps.checkCardValue(elementName, value);
    }

    @Then("the user checks the rounding value is <expectedValue>")
    public void AliasTheUserChecksValue(String expectedValue) {
        productSteps.checkCardValue("rounding", expectedValue);
    }

    @Then("the user checks the '$elementNameToCheck' is <expectedValue>")
    public void AliastTheUserChecksValue(String elementNameToCheck, String expectedValue) {
        productSteps.checkCardValue(elementNameToCheck, expectedValue);
    }

    @Then("the user checks the elements values $checkValuesTable")
    public void thenTheUserChecksTheElementValues(ExamplesTable checkValuesTable) {
        productSteps.checkCardValue(checkValuesTable);
    }

    @Then("the user checks the product with '$productName' name is present")
    @Alias("the user checks the product with <productName> name is present")
    public void thenTheUserChecksTheProductWithNameIsPresent(String productName) {
        productSteps.listItemCheck(productName);
    }

    @Then("the user checks the product with '$skuValue' sku is not present")
    public void thenTheUSerChecksTheProductWithSkuIsNotPresent(String skuValue) {
        productSteps.listItemCheckIsNotPresent(skuValue);
    }

    @Then("the user checks the product with '$name' name has '$element' equal to '$expectedValue'")
    public void checkProductWithNameHasExpectedValue(String skuValue, String element, String expectedValue) {
        productSteps.checkProductWithSkuHasExpectedValue(skuValue, element, expectedValue);
    }

    @Then("the user checks default value for '$dropDownType' dropdown equal to '$expectedValue'")
    public void thenTheUSerChecksDefaultValueForDropDown(String dropDownType, String expectedValue) {
        productSteps.checkDropDownDefaultValue(dropDownType, expectedValue);
    }

    @Then("the user checks '$elementName' field contains only '$charNumber' symbols")
    @Alias("the user checks <elementName> field contains only <charNumber> symbols")
    public void thenTheUserChecksNameFieldContainsOnlyExactSymbols(String elementName, int charNumber) {
        productSteps.checkFieldLength(elementName, charNumber);
    }

    @Then("the user checks '$elementName' '$action' avalaible")
    public void thenUSerChecksElementAvalaiblity(String elementName, String action) {
        productSteps.checkElementPresence(elementName, action);
    }

    @Then("the user checks the product rounding value is <value>")
    @Alias("the user checks the product price roundings dropdawn default selected value is '$value'")
    public void thenTheUserChecksTheProductRounding(String value) {
        productSteps.checkDropDownDefaultValue(value);
    }

    @When("the user clicks the product local navigation invoices link")
    public void whenTheUSerClicksTheProductLocalNavigationInvoicesLink() {
        productSteps.productInvoicesLinkClick();
    }

    @Then("the user checks the product invoices list contains entry $examplesTable")
    public void thenTheUserChecksTheProductInvoicesListContainsEntry(ExamplesTable examplesTable) {
        productSteps.checkProductInvoiceListObject(examplesTable);
    }

    @When("the user clicks invoice sku '$sku'")
    public void whenTheUserClicksInvoiceSku(String sku) {
        productSteps.productInvoiceListClick(sku);
    }
}
