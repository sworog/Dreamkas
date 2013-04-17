package project.lighthouse.autotests.jbehave.section_chief;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.InvoiceSteps;

public class InvoiceUserSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @Given("the user is on the invoice create page")
    public void givenTheUserIsOnTheInvoiceCreatePage() {
        invoiceSteps.openInvoiceCreatePage();
    }

    @Given("the user is on the invoice list page")
    public void givenTheUserIsOnTheInvoiceListPage() {
        invoiceSteps.openInvoiceListPage();
    }

    @When("the user inputs '$inputText' in the invoice '$elementName' field")
    public void whenTheUserInputsTextInTheInvoiceField(String elementName, String inputText) {
        invoiceSteps.input(elementName, inputText);
    }

    @When("the user clicks the invoice create button")
    public void whenTheUserClicksTheInvoiceCreateButton() {
        invoiceSteps.invoiceCreateButtonClick();
    }

    @When("the user clicks close button in the invoice create page")
    public void whenTheUserClicksCloseButtonInTheInvoiceCreatePage() {
        invoiceSteps.invoiceCloseButtonClick();
    }

    @When("the user clicks the create button on the invoice list page")
    public void whenTheUserClicksTheCreateButtonOnTheInvoiceListPage() {
        invoiceSteps.invoiceListItemCreate();
    }

    @When("the user clicks the edit button on the invoice browsing page")
    public void whenTheUserClicksTheEditButtonOnProductCardViewPage() {
        invoiceSteps.editButtonClick();
    }

    @When("the user open the invoice card with '$skuValue' sku")
    public void whenTheUserOpenTheProductCard(String skuValue) {
        invoiceSteps.listItemClick(skuValue);
    }

    @When("the user generates charData with '$charNumber' number in the '$elementName' invoice field")
    public void whenTheUserGeneratesCharData(String elementName, int charNumber) {
        invoiceSteps.generateTestCharData(elementName, charNumber);
    }

    @When("the user navigates to invoice product addition")
    public void whenTheUserNavigatesToInvoiceProductAddition() {
        invoiceSteps.goToTheaAdditionOfProductsLinkClick();
    }

    @When("the user inputs '$value' in the invoice product '$elementName' field")
    public void whenTheUserInputsValueInTheInvoiceProductElementNameField(String value, String elementName) {
        whenTheUserInputsTextInTheInvoiceField(elementName, value);
    }


    @When("the user clicks the add more product button")
    public void whenTheUserClicksTheAddMoreProductButton() {
        invoiceSteps.addOneMoreProductLinkClick();
    }

    @Then("the user checks the invoice with '$skuValue' sku has '$name' equal to '$expectedValue'")
    public void whenTheUSerChecksTheInvoiceWithSkuHasNameValueEqualToExpectedValue(String skuValue, String name, String expectedValue) {
        invoiceSteps.checkInvoiceListItemWithSkuHasExpectedValue(skuValue, name, expectedValue);
    }

    @Then("the user checks the invoice with '$skuValue' sku is present")
    public void whenTheUserChecksTheInvoiceWithSkuIsPresent(String skuValue) {
        invoiceSteps.listItemCheck(skuValue);
    }

    @Then("the user checks the invoice '$elementName' value is '$expectedValue'")
    public void thenTheUserChecksValue(String elementName, String expectedValue) {
        invoiceSteps.checkCardValue(elementName, expectedValue);
    }

    @Then("the user checks invoice '$checkType' elements values $checkValuesTable")
    public void thenTheUserChecksTheElementValues(String checkType, ExamplesTable checkValuesTable) {
        invoiceSteps.checkCardValue(checkType, checkValuesTable);
    }

    @Then("the user checks invoice elements values $checkValuesTable")
    public void thenTheUserChecksTheElementValues(ExamplesTable checkValuesTable) {
        invoiceSteps.checkCardValue("", checkValuesTable);
    }

    @Then("the user checks '$elementName' invoice field contains only '$fieldLength' symbols")
    public void thenTheUserChecksNameFieldContainsOnlyExactSymbols(String elementName, int fieldLength) {
        invoiceSteps.checkFieldLength(elementName, fieldLength);
    }

    @Then("the user checks the '$elementName' is prefilled and equals NowDate")
    public void thenTheUserChecksTheDateIsPrefilledAndEquals(String elementName) {
        invoiceSteps.checkTheDateisNowDate(elementName);
    }

    @Then("the user checks the invoice product with '$value' sku is present")
    public void whenTheUserChecksTheInvoiceProductWithSKuIsPresent(String value) {
        invoiceSteps.invoiceProductListItemCheck(value);
    }

    @Then("the user checks the warning message about changes is present")
    public void thenTheUserChecksTheWarningMessageAboutChangesIsPresent() {
        invoiceSteps.checkFormIsChanged();
    }

    @Then("the user checks the product with '$value' sku has values $checkValuesTable")
    public void thenTheUserChecksTheProductWithSkuHasValues(String value, ExamplesTable checkValuesTable) {
        invoiceSteps.checkListItemWithSkuHasExpectedValue(value, checkValuesTable);
    }

}
