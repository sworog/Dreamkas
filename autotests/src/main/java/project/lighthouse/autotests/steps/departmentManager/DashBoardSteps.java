package project.lighthouse.autotests.steps.departmentManager;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.joda.time.DateTime;
import project.lighthouse.autotests.fixtures.Us_53_1_Fixture;
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

    @Step
    public void assertGrossSaleYesterdayValue() {
        String expectedMessage = String.format("Вчера %s", new Us_53_1_Fixture().getAllGrossSalesYesterdayValue());
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleYesterdayValue());
    }

    @Step
    public void assertGrossSaleLastWeekValue() {
        String expectedMessage = String.format("В %s %s", getDate(), new Us_53_1_Fixture().getAllGrossSalesLastWeekDayValue());
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleLastWeekValue());
    }

    private String getDate() {
        switch (new DateTime().getDayOfWeek()) {
            case 0:
                return "прошлый понедельник";
            case 1:
                return "прошлый вторник";
            case 2:
                return "прошлую среду";
            case 3:
                return "прошлый четверг";
            case 4:
                return "прошлую пятницу";
            case 5:
                return "прошлую субботу";
            case 6:
                return "прошлое воскресение";
            default:
                return "в аду";
        }
    }
}
