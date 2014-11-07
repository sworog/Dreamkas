package ru.dreamkas.helper;

import java.text.DateFormatSymbols;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;

/**
 * The helper is used for formatting and getting date and time values
 */
public class DateTimeHelper {

    public static final String ISO_8601 = "yyyy-MM-dd'T'HH:mm'Z'";

    public static String getExpectedSaleDate(String saleDate, String pattern) {
        try {
            SimpleDateFormat simpleDateFormat = new SimpleDateFormat(DateTimeHelper.ISO_8601, new Locale("ru"));
            Date convertedSaleDate = new org.joda.time.DateTime(simpleDateFormat.parse(saleDate)).plusHours(3).toDate();
            String[] months = {"января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря"};
            DateFormatSymbols customDateFormatSymbols = DateFormatSymbols.getInstance(new Locale("ru"));
            customDateFormatSymbols.setMonths(months);
            return new SimpleDateFormat(pattern, customDateFormatSymbols).format(convertedSaleDate);
        } catch (ParseException e) {
            throw new AssertionError(e);
        }
    }
}
