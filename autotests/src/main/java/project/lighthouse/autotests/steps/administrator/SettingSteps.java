package project.lighthouse.autotests.steps.administrator;

import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.administrator.SettingsPage;

public class SettingSteps extends ScenarioSteps {

    SettingsPage settingsPage;

    public SettingSteps(Pages pages) {
        super(pages);
    }

    public void open() {
        settingsPage.open();
    }

    public void input(ExamplesTable fieldInputTable) {
        settingsPage.fieldInput(fieldInputTable);
    }

    public void check(ExamplesTable checkValuesTable) {
        settingsPage.checkCardValue(checkValuesTable);
    }

    public void saveSettingsButtonClick() {
        settingsPage.getSaveSettingButtonWebElement().click();
    }
}
