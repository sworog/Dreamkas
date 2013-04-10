package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.CommonSteps;

public class CommonUserSteps {

    @Steps
    CommonSteps commonSteps;


    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode")
    public void givenTheUserCreatesProductWithParams(String name, String sku, String barcode){
        commonSteps.createProductPostRequestSend(name, sku, barcode, "kg");
    }

    @Given("there is created product with sku '$sku'")
    public void givenThereIsCreatedProductWithSkuValue(String sku){
        givenTheUserCreatesProductWithParams(sku, sku, sku, "kg");
    }

    @Given("there is the product with '$name' name, '$sku' sku, '$barcode' barcode, '$units' units")
    public void givenTheUserCreatesProductWithParams(String name, String sku, String barcode, String units){
        commonSteps.createProductPostRequestSend(name, sku, barcode, units);
    }

    @Given("there is the invoice with '$sku' sku")
    public void givenThereIsTheInvoiceWithSku(String sku){
        commonSteps.createInvoiceThroughPost(sku);
    }

    @Then("the user checks that he is on the '$pageObjectName'")
    public void TheTheUserChecksThatHeIsOnTheProductListPage(String pageObjectName){
        commonSteps.checkTheRequiredPageIsOpen(pageObjectName);
    }

    @Then("the user sees error messages $errorMessageTable")
    public void ThenTheUserSeesErrorMessages(ExamplesTable errorMessageTable){
        commonSteps.checkErrorMessages(errorMessageTable);
    }

    @Then("the user sees no error messages")
    public void ThenTheUserSeesNoErrorMessages(){
        commonSteps.checkNoErrorMessages();
    }

    @Then("the user sees no error messages $errorMessageTable")
    public void ThenTheUserSeesNoErrorMessages(ExamplesTable errorMessageTable){
        commonSteps.checkNoErrorMessages(errorMessageTable);
    }

    @Then("the users checks no autocomplete results")
    public void thenTheUserChecksNoAutocompleteResults(){
        commonSteps.checkAutoCompleteNoResults();
    }

    @Then("the users checks autocomplete results contains $checkValuesTable")
    public void thenTheUSerChecksAutocompleteResultsContainsValuesTable(ExamplesTable checkValuesTable){
        commonSteps.checkAutoCompleteResults(checkValuesTable);
    }
}
