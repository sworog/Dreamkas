package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.MenuNavigation;

public class MenuNavigationSteps extends ScenarioSteps {

    MenuNavigation menuNavigation;

    @Step
    public void reportMenuItemClick() {
        menuNavigation.reportMenuItemClick();
    }

    @Step
    public void reportMenuItemIsNotVisible() {
        try {
            menuNavigation.reportMenuItemClick();
        } catch (Exception ignored) {
        }
    }
}
