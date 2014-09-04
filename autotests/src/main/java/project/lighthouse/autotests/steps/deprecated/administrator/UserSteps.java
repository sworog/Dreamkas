package project.lighthouse.autotests.steps.deprecated.administrator;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.deprecated.user.localNavigation.UserLocalNavigation;

public class UserSteps extends ScenarioSteps {

    UserLocalNavigation userLocalNavigation;

    @Step
    public void logOutButtonClick() {
        userLocalNavigation.logOutButtonClick();
    }
}
