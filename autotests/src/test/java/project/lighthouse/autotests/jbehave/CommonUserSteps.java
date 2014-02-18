package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Pending;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.CommonSteps;

public class CommonUserSteps {

    @Steps
    CommonSteps commonSteps;

    String storedUrl;

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

    @Then("the user checks <autoCompleteResult>")
    public void thenTheUserChecksAutoCompleteResult(String autoCompleteResult) {
        commonSteps.checkAutoCompleteResult(autoCompleteResult);
    }

    @Then("the user checks alert text is equal to '$expectedText'")
    public void thenTheUserChecksAlertTextIsEqualTo(String expectedText) {
        commonSteps.checkAlertText(expectedText);
    }

    @Then("the user checks there is no alert on the page")
    public void thenTheUserChecksNoAlertOnThePage() {
        commonSteps.NoAlertIsPresent();
    }

    @Given("skipped test")
    @Pending
    public void pending() {
        //Pending
    }

    @When("the user refreshes the current page")
    public void whenTheUserRefreshesTheCurrentPage() {
        commonSteps.pageRefresh();
    }

    @Then("the user checks page contains text '$text'")
    public void pageContainsText(String text) {
        commonSteps.pageContainsText(text);
    }

    @Given("skipped. Info: '$description', Details: '$details'")
    @Pending
    public void about(String description, String details) {
        //pending
    }

    @Given("the user stores the current url")
    public void givenTheUserStoresTheCurrentUrl() {
        storedUrl = commonSteps.getDriver().getCurrentUrl();
    }

    @Given("the user navigates to the stored url")
    public void givenTheUserNavigatesToTheStoredUrl() {
        commonSteps.getDriver().navigate().to(storedUrl);
    }
}
