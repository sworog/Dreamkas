package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.authorization.DashBoardPage;

public class DashBoardSteps extends ScenarioSteps {

    DashBoardPage dashBoardPage;

    public DashBoardSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void buttonClick(String name) {
        dashBoardPage.buttonClick(name);
    }

    @Step
    public void shouldNotBeVisible(String name) {
        dashBoardPage.shouldNotBeVisible(name);
    }

    @Step
    public void shouldBeVisible(String name) {
        dashBoardPage.shouldBeVisible(name);
    }
}
