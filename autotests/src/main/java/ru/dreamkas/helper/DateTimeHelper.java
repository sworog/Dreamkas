package ru.dreamkas.helper;

import org.joda.time.DateTime;

import java.text.DateFormatSymbols;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;
import java.util.TimeZone;

/**
 * The helper is used for formatting and getting date and time values
 */
public class DateTimeHelper {

    private int days;

    private static final String DATE_TIME_PATTERN = "dd.MM.yyyy HH:mm";
    private static final String DATE_PATTERN = "yyyy-MM-dd";
    private static final String DATE_PATTERN_REVERT = "dd.MM.yyyy";
    public static final String ISO_8601 = "yyyy-MM-dd'T'HH:mm'Z'";

    public DateTimeHelper(String value) {
        days = getDays(value);
    }

    public DateTimeHelper(int value) {
        days = value;
    }

    public String convertDateTime() {
        return getTodayDate(DATE_TIME_PATTERN, days);
    }

    public String convertDateTime(int hour, int minute, int second) {
        return getTodayDate(DATE_TIME_PATTERN, days, hour, minute, second);
    }

    public String convertDate() {
        return getTodayDate(DATE_PATTERN, days);
    }

    public String convertDateByPattern(String pattern) {
        return getTodayDate(pattern, days);
    }

    private int getDays(String value) {
        String replacedValue = value.replaceFirst(".+-([0-3]?[0-9]).*", "$1");
        return Integer.parseInt(replacedValue);
    }

    private static String getTodayDate(String pattern, int day) {
        return new SimpleDateFormat(pattern).format(new org.joda.time.DateTime().minusDays(day).toDate());
    }

    private String getTodayDate(String pattern, int days, int hour, int minute, int second) {
        return new SimpleDateFormat(pattern).format(new org.joda.time.DateTime()
                .withHourOfDay(hour)
                .withMinuteOfHour(minute)
                .withSecondOfMinute(second)
                .minusDays(days)
                .toDate()
        );
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

    public static String getDate(String value) {
        switch (value) {
            case "todayDateAndTime":
                return getTodayDate(DATE_TIME_PATTERN);
            case "todayDate":
                return getTodayDate(DATE_PATTERN_REVERT);
            case "saleTodayDate":
                return getTodayDateForSaleRegistering();
            default:
                if (value.contains("saleTodayDate-")) {
                    String replacedValue = value.replaceFirst(".+-([0-3]?[0-9]).*", "$1");
                    int numberOfDay = Integer.parseInt(replacedValue);
                    return getTodayDateForSaleRegistering(numberOfDay);
                } else if (value.contains("todayDate-")) {
                    String replacedValue = value.replaceFirst(".+-([0-3]?[0-9]).*", "$1");
                    int numberOfDay = Integer.parseInt(replacedValue);
                    return getTodayDate(DATE_PATTERN_REVERT, numberOfDay);
                }
                return value;
        }
    }

    public static String getTodayDate(String pattern) {
        return new SimpleDateFormat(pattern).format(new Date());
    }

    public static String getTodayDateForSaleRegistering() {
        return getDateForSaleRegistering(new Date());
    }

    public static String getTodayDateForSaleRegistering(int days) {
        return getDateForSaleRegistering(new org.joda.time.DateTime().minusDays(days).toDate());
    }

    private static String getDateForSaleRegistering(Date date) {
        SimpleDateFormat simpleDateFormat = new SimpleDateFormat(ISO_8601);
        TimeZone timeZone = TimeZone.getTimeZone("UTC");
        simpleDateFormat.setTimeZone(timeZone);
        return simpleDateFormat.format(date);
    }

    public static String getExpectedSaleDate(String saleDate, String pattern) {
        try {
            SimpleDateFormat simpleDateFormat = new SimpleDateFormat(DateTimeHelper.ISO_8601, new Locale("ru"));
            Date convertedSaleDate = new org.joda.time.DateTime(simpleDateFormat.parse(saleDate)).plusHours(4).toDate();
            String[] months = {"января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря"};
            DateFormatSymbols customDateFormatSymbols = DateFormatSymbols.getInstance(new Locale("ru"));
            customDateFormatSymbols.setMonths(months);
            return new SimpleDateFormat(pattern, customDateFormatSymbols).format(convertedSaleDate);
        } catch (ParseException e) {
            throw new AssertionError(e);
        }
    }
}
