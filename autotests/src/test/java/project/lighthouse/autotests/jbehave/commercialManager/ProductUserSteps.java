package project.lighthouse.autotests.jbehave.commercialManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.objects.Category;
import project.lighthouse.autotests.objects.Group;
import project.lighthouse.autotests.steps.commercialManager.ProductSteps;

import java.io.IOException;

public class ProductUserSteps {

    @Steps
    ProductSteps productSteps;

    ExamplesTable fieldInputTable;

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode")
    public void givenTheUserCreatesProductWithParams(String name, String sku, String barcode) throws JSONException, IOException {
        productSteps.createProductPostRequestSend(name, sku, barcode, "kg", "123");
    }

    @Given("there is created product with sku '$sku'")
    public void givenThereIsCreatedProductWithSkuValue(String sku) throws JSONException, IOException {
        givenTheUserCreatesProductWithParams(sku, sku, sku, "kg");
    }

    @Given("there is created product with sku '$sku' and '$purchasePrice' purchasePrice")
    public void givenThereIsCreatedProductWithSkuValue(String sku, String purchasePrice) throws JSONException, IOException {
        givenTheUserCreatesProductWithParamsPrice(sku, sku, sku, "kg", purchasePrice);
    }

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode, '$units' units")
    public void givenTheUserCreatesProductWithParams(String name, String sku, String barcode, String units) throws JSONException, IOException {
        productSteps.createProductPostRequestSend(name, sku, barcode, units, "123");
    }

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode, '$units' units, '$purchasePrice' purchasePrice")
    public void givenTheUserCreatesProductWithParamsPrice(String name, String sku, String barcode, String units, String purchasePrice) throws JSONException, IOException {
        productSteps.createProductPostRequestSend(name, sku, barcode, units, purchasePrice);
    }

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode, '$units' units, '$purchasePrice' purchasePrice in the subcategory named '$subCategoryName'")
    public void createProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice, String subCategoryName) throws JSONException, IOException {
        productSteps.createProductThroughPost(name, sku, barcode, units, purchasePrice, subCategoryName);
    }

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode, '$units' units, '$purchasePrice' purchasePrice of group named '$groupName', category named '$categoryName', subcategory named '$subCategoryName'")
    public void createProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice,
                                         String groupName, String categoryName, String subCategoryName) throws IOException, JSONException {
        productSteps.createProductThroughPost(name, sku, barcode, units, purchasePrice, groupName, categoryName, subCategoryName);
    }

    @Given("there is the product with '$name' name, '$productSku' sku, '$barcode' barcode, '$units' units, '$purchasePrice' purchasePrice of group named '$groupName', category named '$categoryName', subcategory named '$subCategoryName' with '$rounding' rounding")
    @Alias("there is the product with '$name' name, <productSku> sku, '$barcode' barcode, '$units' units, '$purchasePrice' purchasePrice of group named '$groupName', category named '$categoryName', subcategory named '$subCategoryName' with '$rounding' rounding")
    public void createProductThroughPost(String name, String productSku, String barcode, String units, String purchasePrice,
                                         String rounding, String groupName, String categoryName, String subCategoryName) throws IOException, JSONException {
        productSteps.createProductThroughPost(name, productSku, barcode, units, purchasePrice, groupName, categoryName, subCategoryName, rounding);
    }

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode, '$units' units, '$purchasePrice' purchasePrice of group named '$groupName', category named '$categoryName', subcategory named '$subCategoryName' with '$rounding' rounding, retailMarkUpMax '$retailMarkupMax' and retailMarkUpmin '$retailMarkupMin'")
    public void createProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice,
                                         String rounding, String groupName, String categoryName, String subCategoryName, String retailMarkupMax, String retailMarkupMin) throws IOException, JSONException {
        productSteps.createProductThroughPost(name, sku, barcode, units, purchasePrice, groupName, categoryName, subCategoryName, retailMarkupMax, retailMarkupMin, rounding);
    }

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode, '$units' units, '$purchasePrice' purchasePrice, '$rounding' rounding in the subcategory named '$subCategoryName'")
    public void createProductThroughPost(String name, String sku, String barcode, String units, String purchasePrice,
                                         String rounding, String subCategoryName) throws IOException, JSONException {
        productSteps.createProductThroughPost(name, sku, barcode, units, purchasePrice, Group.DEFAULT_NAME, Category.DEFAULT_NAME, subCategoryName, rounding);
    }


    @Given("there is the product with <productSku> and <rounding> in the subcategory named '$subCategoryName'")
    public void createProductThroughPost(String rounding, String productSku, String subCategoryName) throws IOException, JSONException {
        createProductThroughPost(productSku, productSku, productSku, "kg", "1", rounding, subCategoryName);
    }


    @Given("the user is on the product create page")
    public void givenTheUserIsOnTheOrderCreatePage() throws JSONException, IOException {
        productSteps.openProductCreatePage();
    }

    @Given("the user is on the product list page")
    public void givenTheUserIsOnTheProductListPage() throws IOException, JSONException {
        productSteps.openProductListPage();
    }

    @Given("the user is on the product card")
    public void givenTheUserIsOnTheProductCard() {
        productSteps.isTheProductCardOpen();
    }

    @Given("the user navigates to the product with sku '$productSku'")
    @Alias("the user navigates to the product with <productSku>")
    public void givenTheUserNavigatesToTheProduct(String productSku) throws JSONException {
        productSteps.navigateToTheProductPage(productSku);
    }

    @Given("the user navigates to the product with <sku>")
    public void givenTheUserNavigatesToTheProdcutWithSku(String sku) throws JSONException, IOException {
        givenThereIsCreatedProductWithSkuValue(sku, "0,01");
        givenTheUserNavigatesToTheProduct(sku);
    }

    @When("the user inputs '$value' in '$elementName' field")
    public void whenTheUserInputsTextInTheField(String value, String elementName) {
        productSteps.fieldInput(elementName, value);
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

    @When("the user clicks the edit price button")
    public void whenTheUserClicksTheEditPriceButton() {
        productSteps.editProductButtonClick();
    }

    @Then("the user checks the edit price button is not present")
    public void thenUserChecksTheEditPriceButtonIsNotPresent() {
        productSteps.editProductButtonIsNotPresent();
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

    @Then("the user checks '$elementName' field contains only '$charNumber' symbols")
    @Alias("the user checks <elementName> field contains only <charNumber> symbols")
    public void thenTheUserChecksNameFieldContainsOnlyExactSymbols(String elementName, int charNumber) {
        productSteps.checkFieldLength(elementName, charNumber);
    }

    @Then("the user checks '$elementName' '$action' avalaible")
    public void thenUSerChecksElementAvalaiblity(String elementName, String action) {
        productSteps.checkElementPresence(elementName, action);
    }

    @Then("the user waits untill rounding preloader spinner is not visible")
    public void thenTheUserWaitsUntillRoundingPreloaderSpinnerIsNotVisible() {
        productSteps.roundingPreloaderSpinnerWait();
    }
}
