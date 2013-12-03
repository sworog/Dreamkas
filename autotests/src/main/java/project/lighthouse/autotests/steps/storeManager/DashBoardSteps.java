package project.lighthouse.autotests.steps.storeManager;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.joda.time.DateTime;
import project.lighthouse.autotests.fixtures.Us_53_1_Fixture;
import project.lighthouse.autotests.pages.storeManager.dashBoard.DashBoardPage;

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
    public void assertGrossSaleSumYesterdayValue() {
        String expectedMessage = String.format("Вчера %s", new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(24));
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleYesterdayValue());
    }

    @Step
    public void assertGrossSaleYesterdayValueIsZero() {
        String expectedMessage = String.format("Вчера %s", "0,00");
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleYesterdayValue());
    }

    @Step
    public void assertGrossSaleSumLastWeekValue() {
        String expectedMessage = String.format("В %s %s", getDate(), new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(24));
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleLastWeekValue());
    }

    @Step
    public void assertGrossSaleLastWeekValueIsZero() {
        String expectedMessage = String.format("В %s %s", getDate(), "0,00");
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleLastWeekValue());
    }

    @Step
    public void assertGrossSalesTodayIsMoreThenYesterday() {
        Double todayGrossSalesValue = getDoubleFromStringWithoutSpaces(new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet2().get(new DateTime().getHourOfDay()));
        Double yesterdayGrossSalesValue = getDoubleFromStringWithoutSpaces(new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(new DateTime().getHourOfDay()));
        String percentage = getRatio(todayGrossSalesValue, yesterdayGrossSalesValue);
        String expectedMessage = String.format("На %s%% больше, чем вчера (%s)", percentage, new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(new DateTime().getHourOfDay()));
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleYesterdayDiff());
    }

    @Step
    public void assertGrossSalesTodayIsLessThenYesterday() {
        Double todayGrossSalesValue = getDoubleFromStringWithoutSpaces(new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(new DateTime().getHourOfDay()));
        Double yesterdayGrossSalesValue = getDoubleFromStringWithoutSpaces(new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet2().get(new DateTime().getHourOfDay()));
        String percentage = getRatio(todayGrossSalesValue, yesterdayGrossSalesValue);
        String expectedMessage = String.format("На %s%% меньше, чем вчера (%s)", percentage, new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet2().get(new DateTime().getHourOfDay()));
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleYesterdayDiff());
    }

    @Step
    public void assertGrossSalesTodayIsEqualYesterday() {
        Assert.assertEquals("Так же как вчера", dashBoardPage.getGrossSaleYesterdayDiff());
    }

    @Step
    public void assertGrossSaleWeekTodayIsMoreThanWeekAgo() {
        Double todayGrossSalesValue = getDoubleFromStringWithoutSpaces(new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet2().get(new DateTime().getHourOfDay()));
        Double weekAgoGrossSalesValue = getDoubleFromStringWithoutSpaces(new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(new DateTime().getHourOfDay()));
        String percentage = getRatio(todayGrossSalesValue, weekAgoGrossSalesValue);
        String expectedMessage = String.format("На %s%% больше, чем в %s (%s)", percentage, getDate(), new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(new DateTime().getHourOfDay()));
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleWeekDiff());
    }

    @Step
    public void assertGrossSaleWeekTodayIsLessThanWeekAgo() {
        Double todayGrossSalesValue = getDoubleFromStringWithoutSpaces(new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet1().get(new DateTime().getHourOfDay()));
        Double weekAgoGrossSalesValue = getDoubleFromStringWithoutSpaces(new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet2().get(new DateTime().getHourOfDay()));
        String percentage = getRatio(todayGrossSalesValue, weekAgoGrossSalesValue);
        String expectedMessage = String.format("На %s%% меньше, чем в %s (%s)", percentage, getDate(), new Us_53_1_Fixture().getGrossSalesPerHourFromDataSet2().get(new DateTime().getHourOfDay()));
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleWeekDiff());
    }

    @Step
    public void assertGrossSaleWeekTodayIsEqualWeekAgo() {
        String expectedMessage = String.format("Так же как в %s", getDate());
        Assert.assertEquals(expectedMessage, dashBoardPage.getGrossSaleWeekDiff());
    }

    @Step
    public void assertGrossSaleYesterdayDiffTextColor(String color) {
        Assert.assertEquals(color, dashBoardPage.getGrossSaleYesterdayDiffTextColor());
    }

    @Step
    public void assertGrossSaleWeekDiffTextColor(String color) {
        Assert.assertEquals(color, dashBoardPage.getGrossSaleWeekDiffTextColor());
    }

    @Step
    public void assertGrossSaleIsNotAvailable() {
        try {
            dashBoardPage.getGrossSaleDivBlock();
            Assert.fail("The gross sale block is available!");
        } catch (Exception ignored) {
        }
    }

    @Step
    public void assertYesterdayRatioBlockIsNotVisible() {
        Assert.assertEquals(dashBoardPage.getGrossSaleYesterdayDiff(), "");
    }

    @Step
    public void assertWeekRatioBlockIsNotVisible() {
        Assert.assertEquals(dashBoardPage.getGrossSaleWeekDiff(), "");
    }

    private Double getDoubleFromStringWithoutSpaces(String stringValue) {
        return Double.parseDouble(stringValue.replace(" ", "").replace(",", "."));
    }

    private String getRatio(Double todayValue, Double yesterdayValue) {
        return getFormattedDouble((todayValue - yesterdayValue) / yesterdayValue * 100);
    }

    private String getFormattedDouble(double value) {
        return String.format("%1$.2f", value).replace(",", ".").replace("-", "");
    }

    private String getDate() {
        switch (new DateTime().getDayOfWeek()) {
            case 1:
                return "прошлый понедельник";
            case 2:
                return "прошлый вторник";
            case 3:
                return "прошлую среду";
            case 4:
                return "прошлый четверг";
            case 5:
                return "прошлую пятницу";
            case 6:
                return "прошлую субботу";
            case 7:
                return "прошлое воскресение";
            default:
                return "в аду";
        }
    }
}
