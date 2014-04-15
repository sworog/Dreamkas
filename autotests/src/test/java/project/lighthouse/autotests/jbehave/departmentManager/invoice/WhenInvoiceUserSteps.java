package project.lighthouse.autotests.jbehave.departmentManager.invoice;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
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

    @When("the user generates symbol data with '$fieldLength' number in the '$elementName' invoice field")
    public void whenTheUserGeneratesSymbolDataWithNumberInTheInvoiceField(int fieldLength, String elementName) {
        invoiceSteps.inputGeneratedData(elementName, fieldLength);
    }

    @When("the user clicks on the search result invoice with number '$number'")
    public void whenTheUserClicksOnTheSearchResultInvoiceWithNumber(String number) {
        invoiceSteps.invoiceListSearchObjectClick(number);
    }

    @When("the user focuses out on the invoice page")
    public void whenTheUserFocusesOutOnTheInvoicePage() {
        invoiceSteps.invoiceFocusOutClick();
    }

    @When("the user inputs '$value' into active element, which has focus on the page")
    public void whenTheUserInputsValueInToActiveElementWhichHasFocusOnTheInvoicePage(String value) {
        invoiceSteps.typeInToActiveWebElement(value);
    }
}
