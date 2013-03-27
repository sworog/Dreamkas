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
    public void GivenTheUserIsOnTheInvoiceCreatePage() {
       invoiceSteps.OpenInvoiceCreatePage();
    }

    @Given("the user is on the invoice list page")
    public void GivenTheUserIsOnTheInvoiceListPage() {
        invoiceSteps.OpenInvoiceListPage();
    }

    @When("the user inputs '$inputText' in the invoice '$elementName' field")
    public void WhenTheUserInputsTextInTheInvoiceField(String elementName, String inputText){
        invoiceSteps.Input(elementName, inputText);
    }

    @When("the user clicks the invoice create button")
    public void WhenTheUserClicksTheInvoiceCreateButton(){
        invoiceSteps.InvoiceCreateButtonClick();
    }

    @When("the user clicks close button in the invoice create page")
    public void WhenTheUserClicksCloseButtonInTheInvoiceCreatePage(){
        invoiceSteps.InvoiceCloseButtonClick();
    }

    @When("the user clicks the create button on the invoice list page")
    public void WhenTheUserClicksTheCreateButtonOnTheInvoiceListPage(){
        invoiceSteps.InvoiceListItemCreate();
    }

    @When("the user clicks the edit button on the invoice browsing page")
    public void WhenTheUserClicksTheEditButtonOnProductCardViewPage(){
        invoiceSteps.EditButtonClick();
    }

    @When("the user open the invoice card with '$skuValue' sku")
    public void WhenTheUserOpenTheProductCard(String skuValue){
        invoiceSteps.ListItemClick(skuValue);
    }

    @When("the user generates charData with '$charNumber' number in the '$elementName' invoice field")
    public void WhenTheUserGeneratesCharData(String elementName, int charNumber){
        invoiceSteps.GenerateTestCharData(elementName, charNumber);
    }

    @Then("the user checks the invoice with '$skuValue' sku has '$name' equal to '$expectedValue'")
    public void WhenTheUSerChecksTheInvoiceWithSkuHasNameValueEqualToExpectedValue(String skuValue, String name, String expectedValue){
        invoiceSteps.CheckInvoiceListItemWithSkuHasExpectedValue(skuValue, name, expectedValue);
    }

    @Then("the user checks the invoice with '$skuValue' sku is present")
    public void WhenTheUserChecksTheInvoiceWithSkuIsPresent(String skuValue){
        invoiceSteps.ListItemCheck(skuValue);
    }

    @Then("the user checks the invoice '$elementName' value is '$expectedValue'")
    public void ThenTheUserChecksValue(String elementName, String expectedValue){
        invoiceSteps.CheckCardValue(elementName, expectedValue);
    }

    @Then("the user checks invoice elements values $checkValuesTable")
    public void ThenTheUserChecksTheElementValues(ExamplesTable checkValuesTable){
        invoiceSteps.CheckCardValue(checkValuesTable);
    }

    @Then("the user checks '$elementName' invoice field contains only '$fieldLength' symbols")
    public void ThenTheUserChecksNameFieldContainsOnlyExactSymbols(String elementName, int fieldLength){
        invoiceSteps.CheckFieldLength(elementName, fieldLength);
    }

    @Then("the user checks the '$elementName' is prefilled and equals NowDate")
    public void TheTheUserChecksTheDateIsPrefilledAndEquals(String elementName){
        invoiceSteps.CheckTheDateisNowDate(elementName);
    }
}
