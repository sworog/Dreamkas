package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.authorization.DashBoardPage;

public class DashBoardSteps extends ScenarioSteps {

    DashBoardPage dashBoardPage;

    @Step
    public void shouldNotBeVisible(String name) {
        dashBoardPage.shouldNotBeVisible(name);
    }

    @Step
    public void shouldBeVisible(String name) {
        dashBoardPage.shouldBeVisible(name);
    }
}
