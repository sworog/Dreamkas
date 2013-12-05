package project.lighthouse.autotests.helper;

import org.joda.time.DateTime;

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

    public static String getDate() {
        switch (new DateTime().getDayOfWeek() - 1) {
            case 0:
                return "прошлое воскресение";
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
            default:
                return "в аду";
        }
    }
}
