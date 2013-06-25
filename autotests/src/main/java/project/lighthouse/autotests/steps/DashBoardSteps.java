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
    public void productSectionButtonClick() {
        dashBoardPage.productSectionButtonClick();
    }

    @Step
    public void catalogSectionButtonClick() {
        dashBoardPage.catalogSectionButtonClick();
    }

    @Step
    public void invoicesSectionButtonClick() {
        dashBoardPage.invoicesSectionButtonClick();
    }

    @Step
    public void balanceSectionButtonClick() {
        dashBoardPage.balanceSectionButtonClick();
    }

    @Step
    public void writeOffsSectionButtonClick() {
        dashBoardPage.writeOffsSectionButtonClick();
    }

    @Step
    public void userSectionButtonClick() {
        dashBoardPage.userSectionButtonClick();
    }
}
