package project.lighthouse.autotests.jbehave.section_chief;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
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

    @Then("the user checks the invoice with '$skuValue' sku has '$name' equal to '$expectedValue'")
    public void WhenTheUSerChecksTheInvoiceWithSkuHasNameValueEqualToExpectedValue(String skuValue, String name, String expectedValue){
        invoiceSteps.CheckInvoiceListItemWithSkuHasExpectedValue(skuValue, name, expectedValue);
    }

    @Then("the user checks the invoice with '$skuValue' sku is present")
    public void WhenTheUserChecksTheInvoiceWithSkuIsPresent(String skuValue){
        invoiceSteps.ListItemCheck(skuValue);
    }
}
