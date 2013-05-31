package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Pending;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.steps.CommonSteps;

import java.io.IOException;

public class CommonUserSteps {

    @Steps
    CommonSteps commonSteps;

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode")
    public void givenTheUserCreatesProductWithParams(String name, String sku, String barcode) throws JSONException, IOException {
        commonSteps.createProductPostRequestSend(name, sku, barcode, "kg", "123");
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
        commonSteps.createProductPostRequestSend(name, sku, barcode, units, "123");
    }

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode, '$units' units, '$purchasePrice' purchasePrice")
    public void givenTheUserCreatesProductWithParamsPrice(String name, String sku, String barcode, String units, String purchasePrice) throws JSONException, IOException {
        commonSteps.createProductPostRequestSend(name, sku, barcode, units, purchasePrice);
    }

    @Given("there is the invoice with '$sku' sku")
    public void givenThereIsTheInvoiceWithSku(String sku) throws JSONException, IOException {
        commonSteps.createInvoiceThroughPost(sku);
    }

    @Given("there is the invoice '$invoiceSku' with product '$productName' name, '$productSku' sku, '$productBarCode' barcode, '$productUnits' units")
    public void givenThereIsInvoiceWithProduct(String invoiceSku, String productName, String productSku, String productBarCode, String productUnits) throws JSONException, IOException {
        givenTheUserCreatesProductWithParams(productName, productSku, productBarCode, productUnits);
        commonSteps.createInvoiceThroughPostWithData(invoiceSku, productName);
    }

    @Given("there is the write off with number '$writeOffNumber'")
    public void givenThereIsTheWriteOffWithNumber(String writeOffNumber) throws IOException, JSONException {
        commonSteps.createWriteOffThroughPost(writeOffNumber);
    }

    @Given("there is the class with name '$klassName'")
    public void givenThereIsTheClassWithName(String klassName) throws IOException, JSONException {
        commonSteps.createKlassThroughPost(klassName);
    }

    @Given("there is the group with name '$groupName' related to class '$klassName'")
    public void givenThereIsTheGroupWithNameRelatedToKlass(String groupName, String klassName) throws IOException, JSONException {
        commonSteps.createGroupThroughPost(groupName, klassName);
    }

    @Given("there is the write off with '$writeOffNumber' number with product '$productSku' with quantity '$quantity', price '$price' and cause '$cause'")
    public void givenThereIsTheWriteOffWithProduct(String writeOffNumber, String productSku, String quantity, String price, String cause)
            throws IOException, JSONException {
        commonSteps.createWriteOffThroughPost(writeOffNumber, productSku, productSku, productSku, "kg", "15", quantity, price, cause);
    }

    @Given("the user navigates to new write off with '$writeOffNumber' number with product '$productSku' with quantity '$quantity', price '$price' and cause '$cause'")
    public void givenThereIsTheWriteOffWithProductWithNavigation(String writeOffNumber, String productSku, String productUnits, String purchasePrice, String quantity, String price, String cause)
            throws IOException, JSONException {
        commonSteps.createWriteOffAndNavigateToIt(writeOffNumber, productSku, productSku, productSku, productUnits, purchasePrice, quantity, price, cause);
    }

    @Given("navigate to new write off with '$writeOffNumber' number")
    public void givenThereIsTheWriteOffWithProductWithNavigation(String writeOffNumber) throws IOException, JSONException {
        commonSteps.createWriteOffAndNavigateToIt(writeOffNumber);
    }

    @Given("the user navigates to the write off with number '$writeOffNumber'")
    public void givenNavigateToTheWriteOffWithNumber(String writeNumber) throws JSONException {
        commonSteps.navigatoToWriteOffPage(writeNumber);
    }

    @Given("the user navigates to the klass with name '$klassName'")
    public void givenTheUserNavigatesToTheKlassName(String klassName) throws JSONException {
        commonSteps.navigateToKlassPage(klassName);
    }

    @Given("starting average price calculation")
    public void givenStartingAveragePriceCalculation() {
        commonSteps.averagePriceCalculation();
    }

    @Then("the user checks that he is on the '$pageObjectName'")
    public void TheTheUserChecksThatHeIsOnTheProductListPage(String pageObjectName) {
        commonSteps.checkTheRequiredPageIsOpen(pageObjectName);
    }

    @Then("the user sees error messages $errorMessageTable")
    public void ThenTheUserSeesErrorMessages(ExamplesTable errorMessageTable) {
        commonSteps.checkErrorMessages(errorMessageTable);
    }

    @Then("the user sees no error messages")
    public void ThenTheUserSeesNoErrorMessages() {
        commonSteps.checkNoErrorMessages();
    }

    @Then("the user sees no error messages $errorMessageTable")
    public void ThenTheUserSeesNoErrorMessages(ExamplesTable errorMessageTable) {
        commonSteps.checkNoErrorMessages(errorMessageTable);
    }

    @Then("the users checks no autocomplete results")
    public void thenTheUserChecksNoAutocompleteResults() {
        commonSteps.checkAutoCompleteNoResults();
    }

    @Then("the users checks autocomplete results contains $checkValuesTable")
    public void thenTheUSerChecksAutocompleteResultsContainsValuesTable(ExamplesTable checkValuesTable) {
        commonSteps.checkAutoCompleteResults(checkValuesTable);
    }

    @Then("the user checks alert text is equal to '$expectedText'")
    public void thenTheUserChecksAlertTextIsEqualTo(String expectedText) {
        commonSteps.checkAlertText(expectedText);
    }

    @Then("the user checks there is no alert on the page")
    public void thenTheUserChecksNoAlertOnThePage() {
        commonSteps.NoAlertIsPresent();
    }

    @When("test pending")
    @Pending
    public void whenTheUserClicksCloseButtonInTheInvoiceCreatePage() {
        // PENDING
    }

}
