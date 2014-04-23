package project.lighthouse.autotests.jbehave.departmentManager.invoice;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.steps.departmentManager.invoice.InvoiceSteps;

public class ThenInvoiceUserSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @Then("the user asserts the invoice field label with name '$elementName'")
    public void thenTheUserAssertsTheInvoiceFieldLabelWithName(String elementName) {
        invoiceSteps.assertFieldLabel(elementName);
    }

    @Then("the user waits for the invoice product edition preloader finish")
    public void thenTheUserWaitsForTheInvoiceProductEditionPreloaderFinish() {
        invoiceSteps.invoiceProductEditionPreLoaderWait();
    }

    @Then("the user checks the invoice products list contains exact entries $examplesTable")
    public void thenTheUserChecksTheInvoiceProductsListContainsExactEntries(ExamplesTable examplesTable) throws JSONException {
        invoiceSteps.invoiceProductsCollectionExactCompare(examplesTable);
    }

    @Then("the user checks the invoice total sum is '$expectedValue'")
    public void thenTheUserChecksTheInvoiceTotalSum(String expectedValue) {
        invoiceSteps.assertInvoiceTotalSum(expectedValue);
    }

    @Then("the user checks the invoice vat sum is '$expectedValue'")
    public void thenTheUserChecksTheInvoiceVatSum(String expectedValue) {
        invoiceSteps.assertInvoiceVatSum(expectedValue);
    }

    @Then("the user checks stored values on invoice page")
    public void thenTheUserChecksStoredValuesOnInvoicePage() {
        invoiceSteps.checkValues();
    }

    @Then("the user checks values on the invoice page $examplesTable")
    public void thenTheUserChecksValuesOnTheInvoicePage(ExamplesTable examplesTable) throws JSONException {
        invoiceSteps.checkValues(examplesTable);
    }

    @Then("the user checks the invoice product found by name '$locator' has quantity equals to expectedValue")
    public void thenTheUserChecksTheInvoiceProductFoundByNameHasQuantity(String locator, String expectedValue) {
        invoiceSteps.assertInvoiceProductObjectQuantity(locator, expectedValue);
    }

    @Then("the user checks the last created invoice product found has quantity equals to expectedValue")
    public void thenTheUserChecksTheInvoiceProductFoundByNameHasQuantity(String expectedValue) throws JSONException {
        invoiceSteps.assertLastCreatedInvoiceProductObjectQuantity(expectedValue);
    }

    @Then("the user checks the invoice product found by name '$locator' has price equals to expectedValue")
    public void thenTheUserChecksTheInvoiceProductFoundByNameHasPrice(String locator, String expectedValue) {
        invoiceSteps.assertInvoiceProductObjectPrice(locator, expectedValue);
    }

    @Then("the user checks the last created invoice product has price equals to expectedValue")
    public void thenTheUserChecksTheInvoiceProductFoundByNameHasPrice(String expectedValue) throws JSONException {
        invoiceSteps.assertLastCreatedInvoiceProductObjectPrice(expectedValue);
    }

    @Then("the user checks the acceptanceDate field is prefilled by nowDate")
    public void thenTheUserChecksTheAcceptanceFieldIsPreFilledByNowDate() {
        invoiceSteps.assertAcceptanceDateFieldContainsNowDate();
    }

    @Then("the user checks the invoice is formed by order")
    public void thenTheUserChecksTheInvoiceIsFormedByOrder() {
        invoiceSteps.assertInvoiceOrderInfo();
    }

    @Then("the user asserts '$elementName' invoice field data has '$fieldLength' symbols length")
    public void thenTheUserAssertsInvoiceFieldDataHasSymbolsLength(String elementName, int fieldLength) {
        invoiceSteps.assertFieldLength(elementName, fieldLength);
    }

    @Then("the user asserts the invoice number is '$number'")
    public void thenTheUserAssertsTheInvoiceNumber(String number) {
        invoiceSteps.assertInvoiceNumber(number);
    }

    @Then("the user checks the autocomplete placeholder text is '$expectedPlaceHolder'")
    public void thenTheUserChecksTheAutoCompletePlaceHolderText(String expectedPlaceHolder) {
        invoiceSteps.assertAutoCompletePlaceHolder(expectedPlaceHolder);
    }

    @Then("the user checks the download agreement button should be not visible on the invoice page")
    public void thenTheUserChecksTheDownLoadAgreementButtonShouldBeNotVisibleOnTheInvoicePage() {
        invoiceSteps.downloadAgreementButtonShouldBeNotVisible();
    }

    @Then("the user checks the download agreement button should be visible on the invoice page")
    public void thenTheUserChecksTheDownLoadAgreementButtonShouldBeVisibleOnTheInvoicePage() {
        invoiceSteps.downloadAgreementButtonShouldBeVisible();
    }

    @Then("the user asserts the autocomplete invoice field has focus")
    public void thenTheUserAssertsTheAutocompleteInvoiceFieldHasFocus() {
        invoiceSteps.assertActiveElementIsAutoComplete();
    }

    @Then("the user checks the invoice products list do not contain product with name '$name'")
    public void thenTheUserChecksTheInvoiceProductsListDoNotContainProductWithName(String name) {
        invoiceSteps.collectionDoNotContainInvoiceProductObjectByLocator(name);
    }

    @Then("the user checks the invoice products list do not contain last added product")
    public void thenTheUserChecksTheInvoiceProductsListDoNotContainProductWithName() throws JSONException {
        invoiceSteps.collectionDoNotContainlastAddedInvoiceProductObject();
    }

    @Then("the user asserts invoice search results contains invoice with number '$number'")
    public void thenTheUserAssertsInvoiceSearchResultsContainsInvoiceWithNumber(String number) {
        invoiceSteps.invoiceListSearchObjectContains(number);
    }

    @Then("the user checks invoice search results contains exact values $examplesTable")
    public void thenTheUserChecksInvoiceSearchResultsContainsExactValues(ExamplesTable examplesTable) {
        invoiceSteps.invoiceListSearchObjectExactCompareWith(examplesTable);
    }

    @Then("the user asserts invoice search results contains highlighted text '$text' of invoice with number '$number'")
    public void thenTheUserAssertsInvoiceSearchResultsContainsInvoiceWithNumberAndHighlightedText(
            String text,
            String number) {
        invoiceSteps.invoiceListSearchObjectContainsHighLightedTextByLocator(number, text);
    }

    @Then("the user checks the form results text is '$text'")
    public void thenTheUserChecksTheFormREsultText(String text) {
        invoiceSteps.checkFormResultsText(text);
    }

    @Then("the user waits for checkBoxPreLoader finish")
    public void thenTheUserWaitsForCheckBoxPreLoaderFinish() {
        invoiceSteps.checkBoxPreLoaderWait();
    }

    @Then("the user checks the include vat checkbox is '$state'")
    public void thenTheUserChecksTheIncludeVatCheckBoxState(String state) {
        invoiceSteps.checkTheStateOfCheckBox(state);
    }
}
