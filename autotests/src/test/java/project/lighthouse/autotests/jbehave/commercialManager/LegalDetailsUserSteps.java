package project.lighthouse.autotests.jbehave.commercialManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.commercialManager.LegalDetailsSteps;

public class LegalDetailsUserSteps {
    @Steps
    LegalDetailsSteps legalDetailsSteps;

    ExamplesTable legalDetailsData;

    @When("user selects legal details type '$type'")
    public void userSelectsLegalDetailsType(String type) {
        legalDetailsSteps.selectLegalDetailsType(type);
    }

    @When("user fill legal details inputs $fieldInputTable")
    public void userFillLegalDetailsInputs(ExamplesTable fieldInputTable) {
        legalDetailsSteps.fillInputs(fieldInputTable);
        legalDetailsData = fieldInputTable;
    }

    @When("user clicks save legal details button")
    public void userClicksSaveLegalDetailsButton() {
        legalDetailsSteps.clickSaveButton();
    }

    @Then("user checks legal details fields data")
    public void userChecksLegalDetailsFieldsData() {
        legalDetailsSteps.checkLegalDetailsData(legalDetailsData);
    }

    @Then("user checks legal details form the element field '$name' has error message '$errorMessage'")
    public void userCheckLegalDetailsFormTheElementFieldHasErrorMessage(String name, String errorMessage) {
        legalDetailsSteps.assertFieldErrorMessage(name, errorMessage);
    }
}
