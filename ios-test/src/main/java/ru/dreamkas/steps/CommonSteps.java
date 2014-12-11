package ru.dreamkas.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import ru.dreamkas.pages.CommonPageObject;

public class CommonSteps extends ScenarioSteps {

    CommonPageObject commonPageObject;

    @Step
    public void resetApp() {
        commonPageObject.getAppiumDriver().resetApp();
    }
}
