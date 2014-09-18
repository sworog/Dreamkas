package project.lighthouse.autotests.objects.web.receiptHistory;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;
import project.lighthouse.autotests.storage.Storage;

import java.text.DateFormatSymbols;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;
import java.util.Map;

public class HistoryReceipt extends AbstractObject implements ObjectLocatable, ResultComparable, ObjectClickable {

    private String date;
    private String time;
    private String price;

    public HistoryReceipt(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        date = getElement().getAttribute("data-receipt-date");
        time = getElement().findElement(By.name("time")).getText();
        price = getElement().findElement(By.name("price")).getText();
    }

    @Override
    public void click() {
        getElement().click();
    }

    @Override
    public String getObjectLocator() {
        return String.format("c суммой %s рублей и датой %s:%s", price, date, time);
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("date", String.format("%s %s", this.date, time), getExpectedDate(row))
                .compare("price", price, row.get("price"));
    }

    private String getExpectedDate(Map<String, String> row) {
        try {
            String saleDate = Storage.getCustomVariableStorage().getSalesMap().get(row.get("date"));
            SimpleDateFormat simpleDateFormat = new SimpleDateFormat(DateTimeHelper.ISO_8601, new Locale("ru"));
            Date convertedSaleDate = new org.joda.time.DateTime(simpleDateFormat.parse(saleDate)).plusHours(4).toDate();
            String[] months = {"января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря"};
            DateFormatSymbols customDateFormatSymbols = DateFormatSymbols.getInstance(new Locale("ru"));
            customDateFormatSymbols.setMonths(months);
            return new SimpleDateFormat("EEEE, dd MMMM HH:mm", customDateFormatSymbols).format(convertedSaleDate);
        } catch (ParseException e) {
            throw new AssertionError(e);
        }
    }
}
