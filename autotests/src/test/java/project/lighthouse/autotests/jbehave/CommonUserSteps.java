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
