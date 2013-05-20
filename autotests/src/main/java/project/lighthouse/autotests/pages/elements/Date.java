package project.lighthouse.autotests.pages.elements;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonPageObject;

public class Date extends DateTime {

    public Date(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    public Date(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    private static final String DATE_PICKER_NO_TIME_XPATH = "//*[@class='datepicker datepicker_noTime']";

    @Override
    public void dateTimePickerClose() {
        String xpath = DATE_PICKER_NO_TIME_XPATH + "//*[@class='button button_color_blue datepicker__saveLink tooltip__closeLink']";
        pageObject.findVisibleElement(By.xpath(xpath)).click();
    }

    @Override
    public void setDay(String dayString) {
        String timePickerDayXpath =
                String.format("%s//*[@class='datepicker__dateList']/*[normalize-space(@class='datepicker__dateItem') and normalize-space(text())='%s']", DATE_PICKER_NO_TIME_XPATH, dayString);
        pageObject.findVisibleElement(By.xpath(timePickerDayXpath)).click();
    }

    @Override
    public String getActualDatePickerMonth() {
        String actualDatePickerMonthXpath = DATE_PICKER_NO_TIME_XPATH + "//*[@class='datepicker__monthName']";
        return pageObject.findVisibleElement(By.xpath(actualDatePickerMonthXpath)).getText();
    }

    @Override
    public int getActualDatePickerYear() {
        String actualDatePickerYearXpath = DATE_PICKER_NO_TIME_XPATH + "//*[@class='datepicker__yearNum']";
        return Integer.parseInt(pageObject.findVisibleElement(By.xpath(actualDatePickerYearXpath)).getText());
    }

    @Override
    public void setMonth(int monthValue) {
        setMonth(monthValue, DATE_PICKER_NO_TIME_XPATH + prevMonthLinkXpath, DATE_PICKER_NO_TIME_XPATH + nextMonthLinkXpath);
    }

    @Override
    public void setYear(int yearValue) {
        setYear(yearValue, DATE_PICKER_NO_TIME_XPATH + prevMonthLinkXpath);
    }
}
