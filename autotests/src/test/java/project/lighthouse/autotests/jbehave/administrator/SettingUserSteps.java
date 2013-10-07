package project.lighthouse.autotests.jbehave.administrator;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.administrator.SettingSteps;

public class SettingUserSteps {

    @Steps
    SettingSteps settingSteps;

    ExamplesTable valuesTable;

    @Given("the user opens the settings page")
    public void givenTheUserOpensTheSettingsPage() {
        settingSteps.open();
    }

    @When("the user input values on the setting page $fieldInputTable")
    public void whenTheUserInputsValues(ExamplesTable fieldInputTable) {
        settingSteps.input(fieldInputTable);
        valuesTable = fieldInputTable;
    }

    @When("the user clicks save button on the setting page")
    public void whenTheUserClicksSaveButton() {
        settingSteps.saveSettingsButtonClick();
    }

    @When("the user clicks import save button on the setting page")
    public void whenTheUSerClicksImportSaveButton() {
        settingSteps.saveImportSettingsClick();
    }

    @Then("the user checks the stored values on the setting page")
    public void thenTheUserChecksTheStoreValues() {
        settingSteps.check(valuesTable);
    }
}
