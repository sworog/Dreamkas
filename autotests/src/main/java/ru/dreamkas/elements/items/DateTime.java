package ru.dreamkas.elements.items;

import org.openqa.selenium.By;
import ru.dreamkas.common.item.CommonItem;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.helper.DateTimeHelper;

import java.text.DateFormatSymbols;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Date;
import java.util.Locale;

import static junit.framework.Assert.fail;

public class DateTime extends CommonItem {

    public static final String DATE_PATTERN = "dd.MM.yyyy";
    public static final String DATE_TIME_PATTERN = "dd.MM.yyyy HH:mm";
    public final Locale locale = new Locale("ru");

    String name;

    public static final String prevMonthLinkXpath = "//*[@class='datepicker__prevMonthLink']";
    public static final String nextMonthLinkXpath = "//*[@class='datepicker__nextMonthLink']";

    public DateTime(CommonPageObject pageObject, By findBy, String name) {
        super(pageObject, findBy);
        this.name = name;
    }

    public DateTime(CommonPageObject pageObject, String name) {
        super(pageObject, name);
        this.name = name;
    }

    public DateTime(CommonPageObject pageObject, String name, String label) {
        super(pageObject, name, label);
        this.name = name;
    }

    public String getDatePickerXpath() {
        String xpathTemplate = "//*[contains(@class, 'datepicker') and @rel='%s']";
        return String.format(xpathTemplate, name);
    }

    @Override
    public void setValue(String value) {
        getVisibleWebElementFacade().click();
        if (value.startsWith("!")) {
            String parsedValue = DateTimeHelper.getDate(value.substring(1));
            getVisibleWebElementFacade().type(parsedValue);
        } else {
            String parsedValue = DateTimeHelper.getDate(value);
            dateTimePickerInput(parsedValue);
        }
        dateTimePickerClose();
    }

    public void dateTimePickerInput(String datePattern) {
        String[] dateArray = datePattern.split(" ");
        String[] date = dateArray[0].split("\\.");
        String dayString = date[0];
        int monthInt = Integer.parseInt(date[1]);
        String monthString = getMonthName(monthInt);
        int yearString = Integer.parseInt(date[2]);
        if (!(yearString == getActualDatePickerYear())) {
            setYear(yearString);
        }
        if (!monthString.toLowerCase().equals(getActualDatePickerMonth())) {
            setMonth(monthInt);
        }
        if (!dayString.startsWith("0") && dayString.length() == 1) {
            dayString = "0" + dayString;
        }
        setDay(dayString);
        if (dateArray.length == 2) {
            setTime(dateArray[1]);
        }
    }

    //TODO refactor to ButtonFacade() class
    public void dateTimePickerClose() {
        String dateTimePickerCloseButtonXpath = getDatePickerXpath() + "//*[@class='button datepicker__saveLink tooltip__closeLink']";
        getPageObject().findVisibleElement(By.xpath(dateTimePickerCloseButtonXpath)).click();
    }

    public String getActualDatePickerMonth() {
        String actualDatePickerMonthXpath = getDatePickerXpath() + "//*[@class='datepicker__monthName']";
        return getPageObject().findVisibleElement(By.xpath(actualDatePickerMonthXpath)).getText();
    }

    public int getActualDatePickerYear() {
        String actualDatePickerYearXpath = getDatePickerXpath() + "//*[@class='datepicker__yearNum']";
        String actualDatePickerYear = getPageObject().findVisibleElement(By.xpath(actualDatePickerYearXpath)).getText();
        return Integer.parseInt(actualDatePickerYear);
    }

    public void setTime(String timeString) {
        String[] time = timeString.split(":");
        String hoursXpath = getDatePickerXpath() + "//*[@name='hours']";
        String minutesXpath = getDatePickerXpath() + "//*[@name='minutes']";
        getPageObject().$(getPageObject().findVisibleElement(By.xpath(hoursXpath))).type(time[0]);
        getPageObject().$(getPageObject().findVisibleElement(By.xpath(minutesXpath))).type(time[1]);
    }

    public void setDay(String dayString) {
        String timePickerDayXpath =
                String.format(getDatePickerXpath() +
                                "//*[@class='datepicker__dateList']/*[normalize-space(@class='datepicker__dateItem') and normalize-space(text())='%s' " +
                                "and not(contains(@class, 'datepicker__dateItem datepicker__dateItem_otherMonth'))]",
                        dayString
                );
        getPageObject().findVisibleElement(By.xpath(timePickerDayXpath)).click();
    }

    public void setMonth(int monthValue) {
        int getActualMonth = getMonthNumber(getActualDatePickerMonth());
        int actualMonthValue = 0;
        if (monthValue < getActualMonth) {
            actualMonthValue = 0;
            while (!(monthValue == actualMonthValue)) {
                getPageObject().findVisibleElement(By.xpath(getDatePickerXpath() + prevMonthLinkXpath)).click();
                actualMonthValue = getMonthNumber(getActualDatePickerMonth());
            }
        } else if (monthValue > actualMonthValue) {
            actualMonthValue = 0;
            while (!(monthValue == actualMonthValue)) {
                getPageObject().findVisibleElement(By.xpath(getDatePickerXpath() + nextMonthLinkXpath)).click();
                actualMonthValue = getMonthNumber(getActualDatePickerMonth());
            }
        }
    }

    public void setYear(int yearValue) {
        int actualYear = Calendar.getInstance().get(Calendar.YEAR);
        if (yearValue < getActualDatePickerYear()) {
            int actualYearValue = 0;
            while (!(yearValue == actualYearValue)) {
                getPageObject().findVisibleElement(By.xpath(getDatePickerXpath() + prevMonthLinkXpath)).click();
                actualYearValue = getActualDatePickerYear();
            }
        } else if (yearValue > actualYear) {
            fail(
                    String.format("Year '%s' cantbe older than current year '%s'", yearValue, actualYear)
            );
        }
    }

    public String getMonthName(int month) {
        return new DateFormatSymbols(locale).getMonths()[month - 1];
    }

    public int getMonthNumber(String monthName) {
        Date date = null;
        try {
            date = new SimpleDateFormat("MMM", locale).parse(monthName);

        } catch (ParseException e) {
            fail(
                    String.format("SimpleDateFormat parse error! Error message: '%s'", e.getMessage())
            );
        }
        Calendar cal = Calendar.getInstance();
        cal.setTime(date);
        int month = cal.get(Calendar.MONTH);
        return month + 1;
    }

    @Override
    public String getText() {
        return getVisibleWebElementFacade().getValue();
    }
}
