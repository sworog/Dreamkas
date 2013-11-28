package project.lighthouse.autotests.steps.departmentManager;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.joda.time.DateTime;
import project.lighthouse.autotests.fixtures.Us_53_1_Fixture;
import project.lighthouse.autotests.pages.departmentManager.dashBoard.DashBoardPage;

import java.text.DecimalFormat;

public class DashBoardSteps extends ScenarioSteps {

    DashBoardPage dashBoardPage;

    @Step
    public void assertGrossSaleSubTitle() {
        String expectedMessage = String.format("Объём продаж на %s.00", new DateTime().getHourOfDay());
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleSubTitle());
    }

    @Step
    public void assertGrossSalesTodayValue() {
        String expectedGrossSalesTodayValue = new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(new DateTime().getHourOfDay());
        Assert.assertEquals(expectedGrossSalesTodayValue, dashBoardPage.getGrossSaleTodayValue());
    }

    @Step
    public void assertGrossSalesTodayValueIsZero() {
        Assert.assertEquals("0,00", dashBoardPage.getGrossSaleTodayValue());
    }

    @Step
    public void assertGrossSaleYesterdayValue() {
        String expectedMessage = String.format("Вчера %s", new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(23));
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleYesterdayValue());
    }

    @Step
    public void assertGrossSaleYesterdayValueIsZero() {
        String expectedMessage = String.format("Вчера %s", "0,00");
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleYesterdayValue());
    }

    @Step
    public void assertGrossSaleLastWeekValue() {
        String expectedMessage = String.format("В %s %s", getDate(), new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(23));
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleLastWeekValue());
    }

    @Step
    public void assertGrossSaleLastWeekValueIsZero() {
        String expectedMessage = String.format("В %s %s", getDate(), "0,00");
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleLastWeekValue());
    }

    @Step
    public void assertGrossSalesTodayIsMoreThenYesterday() {
        Double todayGrossSalesValue = Double.parseDouble(new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet2().get(new DateTime().getHourOfDay()));
        Double yesterdayGrossSalesValue = Double.parseDouble(new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(new DateTime().getHourOfDay()));
        String percentage = getFormattedDouble(todayGrossSalesValue / yesterdayGrossSalesValue);
        String expectedMessage = String.format("На %s%% больше, чем вчера (%s)", percentage, new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(new DateTime().getHourOfDay()));
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleYesterdayDiff());
    }

    @Step
    public void assertGrossSalesTodayIsLessThenYesterday() {
        Double todayGrossSalesValue = Double.parseDouble(new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(new DateTime().getHourOfDay()));
        Double yesterdayGrossSalesValue = Double.parseDouble(new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet2().get(new DateTime().getHourOfDay()));
        String percentage = getFormattedDouble(todayGrossSalesValue / yesterdayGrossSalesValue);
        String expectedMessage = String.format("На %s%% меньше, чем вчера (%s)", percentage, new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet2().get(new DateTime().getHourOfDay()));
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleYesterdayDiff());
    }

    @Step
    public void assertGrossSalesTodayIsEqualYesterday() {
        String expectedMessage = String.format("Также как и вчера (%s)", new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet2().get(new DateTime().getHourOfDay()));
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleYesterdayDiff());
    }

    @Step
    public void assertGrossSaleWeekTodayIsMoreThanWeekAgo() {
        Double todayGrossSalesValue = Double.parseDouble(new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet2().get(new DateTime().getHourOfDay()));
        Double weekAgoGrossSalesValue = Double.parseDouble(new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(new DateTime().getHourOfDay()));
        String percentage = getFormattedDouble(todayGrossSalesValue / weekAgoGrossSalesValue);
        String expectedMessage = String.format("На %s%% больше, чем в %s (%s)", percentage, getDate(), new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(new DateTime().getHourOfDay()));
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleWeekDiff());
    }

    @Step
    public void assertGrossSaleWeekTodayIsLessThanWeekAgo() {
        Double todayGrossSalesValue = Double.parseDouble(new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(new DateTime().getHourOfDay()));
        Double weekAgoGrossSalesValue = Double.parseDouble(new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet2().get(new DateTime().getHourOfDay()));
        String percentage = getFormattedDouble(todayGrossSalesValue / weekAgoGrossSalesValue);
        String expectedMessage = String.format("На %s%% меньше, чем в %s (%s)", percentage, getDate(), new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(new DateTime().getHourOfDay()));
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleWeekDiff());
    }

    @Step
    public void assertGrossSaleWeekTodayIsEqualWeekAgo() {
        String expectedMessage = String.format("Также как и в %s (%s)", getDate(), new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(new DateTime().getHourOfDay()));
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleWeekDiff());
    }

    private String getFormattedDouble(double value) {
        return new DecimalFormat("#.##").format(value);
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
