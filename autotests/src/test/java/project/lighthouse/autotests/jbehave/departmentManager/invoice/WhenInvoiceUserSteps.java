package project.lighthouse.autotests.jbehave.departmentManager.invoice;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.steps.departmentManager.invoice.InvoiceSteps;

public class WhenInvoiceUserSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @When("the user inputs values on invoice page $examplesTable")
    public void whenTheUserInputsValuesOnOrderPage(ExamplesTable examplesTable) {
        invoiceSteps.input(examplesTable);
    }

    @When("the user inputs value in the '$elementName' invoice field")
    public void whenTheUserInputsValueInTheInvoiceField(String value, String elementName) {
        invoiceSteps.input(elementName, value);
    }

    @When("the user inputs quantity '$value' on the invoice product with name '$name'")
    @Alias("the user inputs quantity value on the invoice product with name '$name'")
    public void whenTheUserInputsQuantityOnTheInvoiceProductWithName(String value, String name) {
        invoiceSteps.invoiceProductObjectQuantityType(name, value);
    }

    @When("the user inputs price '$value' on the invoice product with name '$name'")
    @Alias("the user inputs price value on the invoice product with name '$name'")
    public void whenTheUserInputsPriceOnTheInvoiceProductWithName(String value, String name) {
        invoiceSteps.invoiceProductObjectPriceType(name, value);
    }

    @When("the user accepts products and saves the invoice")
    public void whenTheUserAcceptsProductsAndSavesTheInvoice() {
        invoiceSteps.acceptProductsButtonClick();
    }

    @When("the user clicks the invoice cancel link button")
    public void whenTheUserClicksTheInvoiceCancelLinkButton() {
        invoiceSteps.cancelLinkClick();
    }

    @When("the user generates symbol data with '$fieldLength' number in the '$elementName' invoice field")
    public void whenTheUserGeneratesSymbolDataWithNumberInTheInvoiceField(int fieldLength, String elementName) {
        invoiceSteps.inputGeneratedData(elementName, fieldLength);
    }

    @When("the user clicks on the search result invoice with number '$number'")
    public void whenTheUserClicksOnTheSearchResultInvoiceWithNumber(String number) {
        invoiceSteps.invoiceListSearchObjectClick(number);
    }

    @When("the user clicks on the search result invoice with last created invoice number")
    public void whenTheUserClicksOnTheSearchResultInvoiceWithLastCreatedInvoiceNumber() {
        invoiceSteps.lastCreatedInvoiceListSearchObjectClick();
    }

    @When("the user searches invoice by last created invoice number")
    public void whenTheUserSearchesInvoiceByLastCreatedInvoiceNumber() {
        invoiceSteps.searchInvoiceByLastCreatedInvoiceNumber();
    }

    @When("the user focuses out on the invoice page")
    public void whenTheUserFocusesOutOnTheInvoicePage() {
        invoiceSteps.invoiceFocusOutClick();
    }

    @When("the user inputs '$value' into active element, which has focus on the page")
    public void whenTheUserInputsValueInToActiveElementWhichHasFocusOnTheInvoicePage(String value) {
        invoiceSteps.typeInToActiveWebElement(value);
    }

    @When("the user clicks on invoice product in last created invoice")
    public void whenTheUserClicksOnTheInvoiceProductInLastCreatedInvoice() throws JSONException {
        invoiceSteps.lastCreatedInvoiceProductObjectClick();
    }

    @When("the user inputs quantity '$value' on the invoice product in last created invoice")
    public void whenTheUserInputsValueOnTheInvoiceProductInLastCreatedInvoice(String value) throws JSONException {
        invoiceSteps.lastCreatedProductObjectQuantityType(value);
    }
}
