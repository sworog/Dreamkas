package project.lighthouse.autotests.helper;

import java.text.SimpleDateFormat;

public class DateTimeHelper {

    private int days;

    private static final String DATE_TIME_PATTERN = "dd.MM.yyyy HH:mm";
    private static final String DATE_PATTERN = "yyyy-MM-dd";

    public DateTimeHelper(String value) {
        days = getDays(value);
    }

    public DateTimeHelper(int value) {
        days = value;
    }

    public String convertDateTime() {
        return getTodayDate(DATE_TIME_PATTERN, days);
    }

    public String convertDate() {
        return getTodayDate(DATE_PATTERN, days);
    }

    private int getDays(String value) {
        String replacedValue = value.replaceFirst(".+-([0-3]?[0-9]).*", "$1");
        return Integer.parseInt(replacedValue);
    }

    private String getTodayDate(String pattern, int day) {
        return new SimpleDateFormat(pattern).format(new org.joda.time.DateTime().minusDays(day).toDate());
    }
}
