package project.lighthouse.autotests.steps.administrator;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.administrator.SettingsPage;

public class SettingSteps extends ScenarioSteps {

    SettingsPage settingsPage;

    public SettingSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void open() {
        settingsPage.open();
    }

    @Step
    public void input(ExamplesTable fieldInputTable) {
        settingsPage.fieldInput(fieldInputTable);
    }

    @Step
    public void check(ExamplesTable checkValuesTable) {
        settingsPage.checkCardValue(checkValuesTable);
    }

    @Step
    public void saveSettingsButtonClick() {
        settingsPage.saveSettingButtonClick();
    }
}
