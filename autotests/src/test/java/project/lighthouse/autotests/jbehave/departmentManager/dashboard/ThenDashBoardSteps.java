package project.lighthouse.autotests.jbehave.departmentManager.dashboard;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.departmentManager.DashBoardSteps;

public class ThenDashBoardSteps {

    @Steps
    DashBoardSteps dashBoardSteps;

    @Then("the user checks the gross sales subTitle")
    public void thenTheUserChecksTheGrossSalesSubTitle() {
        dashBoardSteps.assertGrossSaleSubTitle();
    }

    @Then("the user checks the gross sales today value")
    public void thenTheUserChecksTheGrossSalesTodayValue() {
        dashBoardSteps.assertGrossSalesTodayValue();
    }

    @Then("the user checks the gross sale yesterday value")
    public void thenTheChecksTheGrossSaleYesterdayValue() {
        dashBoardSteps.assertGrossSaleYesterdayValue();
    }

    @Then("the user checks the gross sale last week value")
    public void thenTheUserChecksTheGrossSakeLastWeekValue() {
        dashBoardSteps.assertGrossSaleLastWeekValue();
    }
}
