package project.lighthouse.autotests.jbehave.departmentManager.dashboard;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.departmentManager.DashBoardSteps;

public class ThenDashBoardSteps {

    private static final String COLOR_RED = "#FF0033";
    private static final String COLOR_GREEN = "#1FB18A";

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

    @Then("the user checks the gross sales today value is zero")
    public void thenTheUserChecksTheGrossSalesTodayValueIsZero() {
        dashBoardSteps.assertGrossSalesTodayValueIsZero();
    }

    @Then("the user checks the gross sale yesterday value")
    public void thenTheChecksTheGrossSaleYesterdayValue() {
        dashBoardSteps.assertGrossSaleYesterdayValue();
    }

    @Then("the user checks the gross sale yesterday value is zero")
    public void thenTheUserChecksTheGrossSaleYesterdayValueIsZero() {
        dashBoardSteps.assertGrossSaleYesterdayValueIsZero();
    }

    @Then("the user checks the gross sale last week value")
    public void thenTheUserChecksTheGrossSakeLastWeekValue() {
        dashBoardSteps.assertGrossSaleLastWeekValue();
    }

    @Then("the user checks the gross sale last week value is zero")
    public void thenTheUserChecksTheGrossSaleLastWeekValueIsZero() {
        dashBoardSteps.assertGrossSaleLastWeekValueIsZero();
    }

    @Then("the user checks the gross sale value is more than yesterday one")
    public void thenTheUserChecksTheGrossSaleValueIsMoreThanYesterdayOne() {
        dashBoardSteps.assertGrossSalesTodayIsMoreThenYesterday();
    }

    @Then("the user checks the gross sale value is less than yesterday one")
    public void thenTheUserCheckTheGrossSaleValueIsLessThanYesterdayOne() {
        dashBoardSteps.assertGrossSalesTodayIsLessThenYesterday();
    }

    @Then("the user checks the gross sale value is equal yesterday one")
    public void thenTheUserChecksTherossSaleValueIsEqualYesterdayOne() {
        dashBoardSteps.assertGrossSalesTodayIsEqualYesterday();
    }

    @Then("the user checks the gross sale value is more than last week ago")
    public void thenTheUserChecksTheGrossSaleValueIsMoreThanLastWeek() {
        dashBoardSteps.assertGrossSaleWeekTodayIsMoreThanWeekAgo();
    }

    @Then("the user checks the gross sale value is less than last week ago")
    public void thenTheUserChecksTheGrossSaleValueIsLessThanLastWeekAgo() {
        dashBoardSteps.assertGrossSaleWeekTodayIsLessThanWeekAgo();
    }

    @Then("the user checks the gross sale value is equal last week ago")
    public void thenTheUserChecksTheGrossSaleValueIsEqualLastWeekAgo() {
        dashBoardSteps.assertGrossSaleWeekTodayIsEqualWeekAgo();
    }

    @Then("the user checks the gross yesterday ratio text color is red")
    public void thenTheUserChecksTheGrossYesterdayRatioTextColorIsRed() {
        dashBoardSteps.assertGrossSaleYesterdayDiffTextColor(COLOR_RED);
    }

    @Then("the user checks the gross yesterday ratio text color is green")
    public void thenTheUserChecksTheGrossYesterdayRatioTextColorIsGreen() {
        dashBoardSteps.assertGrossSaleYesterdayDiffTextColor(COLOR_GREEN);
    }

    @Then("the user checks the gross week ratio text color is green")
    public void thenTheUserChecksTheGrossWeekyRatioTextColorIsGreen() {
        dashBoardSteps.assertGrossSaleWeekDiffTextColor(COLOR_GREEN);
    }

    @Then("the user checks the gross week ratio text color is red")
    public void thenTheUserChecksTheGrossWeekRatioTextColorIsRed() {
        dashBoardSteps.assertGrossSaleWeekDiffTextColor(COLOR_RED);
    }
}
