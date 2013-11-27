package project.lighthouse.autotests.steps.departmentManager;

import fixtures.Us_53_1_Fixture;
import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.joda.time.DateTime;
import project.lighthouse.autotests.pages.departmentManager.dashBoard.DashBoardPage;

public class DashBoardSteps extends ScenarioSteps {

    DashBoardPage dashBoardPage;

    @Step
    public void assertGrossSaleSubTitle() {
        String expectedMessage = String.format("Объём продаж на %s.00", new DateTime().getHourOfDay());
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleSubTitle());
    }

    @Step
    public void assertGrossSalesTodayValue() {
        String expectedGrossSalesTodayValue = new Us_53_1_Fixture().getGrossSalesPerHourMapForToday().get(new DateTime().getHourOfDay());
        Assert.assertEquals(expectedGrossSalesTodayValue, dashBoardPage.getGrossSaleTodayValue());
    }
}
