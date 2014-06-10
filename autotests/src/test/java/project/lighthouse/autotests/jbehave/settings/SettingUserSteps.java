package project.lighthouse.autotests.jbehave.settings;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.AfterStory;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.api.ApiConnect;
import project.lighthouse.autotests.steps.administrator.SettingSteps;

import java.io.IOException;
import java.util.Iterator;
import java.util.Map;

public class SettingUserSteps {

    @Steps
    SettingSteps settingSteps;

    ExamplesTable valuesTable;
    private Boolean isSet10ImportUrlSet = false;

    @AfterStory
    public void afterStory() throws IOException, JSONException {
        if (isSet10ImportUrlSet) {
            new ApiConnect("watchman", "lighthouse").setSet10ImportUrl("");
            isSet10ImportUrlSet = false;
        }
    }

    @Given("the user opens the settings page")
    public void givenTheUserOpensTheSettingsPage() {
        settingSteps.open();
    }

    @When("the user input values on the setting page $fieldInputTable")
    public void whenTheUserInputsValues(ExamplesTable fieldInputTable) {
        settingSteps.input(fieldInputTable);
        valuesTable = fieldInputTable;
        isSet10ImportUrlSet = tableContainsSet10ImportUrl(fieldInputTable);
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

    private Boolean tableContainsSet10ImportUrl(ExamplesTable examplesTable) {
        Iterator<Map<String, String>> iterator = examplesTable.getRows().iterator();
        while (iterator.hasNext()) {
            if (iterator.next().containsValue("set10-import-url")) {
                return true;
            }
        }
        return false;
    }
}
