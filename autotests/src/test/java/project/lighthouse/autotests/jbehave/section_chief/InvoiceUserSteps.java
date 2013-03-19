package project.lighthouse.autotests.jbehave.section_chief;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.InvoiceSteps;

public class InvoiceUserSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @Given("the user is on the invoice create page")
    public void GivenTheUserIsOnTheInvoiceCreatePage() {
       invoiceSteps.OpenInvoiceCreatePage();
    }

    @When("the user inputs '$inputText' in the invoice '$elementName' field")
    public void WhenTheUserInputsTextInTheInvoiceField(String elementName, String inputText){
        invoiceSteps.Input(elementName, inputText);
    }

    @When("the user clicks the invoice create button")
    public void WhenTheUserClicksTheInvoiceCreateButton(){
        invoiceSteps.InvoiceCreateButtonClick();
    }
}
