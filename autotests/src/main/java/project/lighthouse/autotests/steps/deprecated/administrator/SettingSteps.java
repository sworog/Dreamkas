package project.lighthouse.autotests.steps.deprecated.administrator;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.elements.preLoader.PreLoader;
import project.lighthouse.autotests.pages.deprecated.settings.SettingsPage;

public class SettingSteps extends ScenarioSteps {

    SettingsPage settingsPage;

    @Step
    public void open() {
        settingsPage.open();
    }

    @Step
    public void input(ExamplesTable fieldInputTable) {
        settingsPage.fieldInput(fieldInputTable);
    }

    @Step
    public void saveSettingsButtonClick() {
        settingsPage.saveSettingButtonClick();
        new PreLoader(getDriver()).await();
    }

    @Step
    public void saveImportSettingsClick() {
        settingsPage.saveImportSettingsClick();
        new PreLoader(getDriver()).await();
    }
}
