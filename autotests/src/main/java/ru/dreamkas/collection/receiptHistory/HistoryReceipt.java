package ru.dreamkas.collection.receiptHistory;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectClickable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectLocatable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ResultComparable;
import ru.dreamkas.collection.compare.CompareResults;
import ru.dreamkas.helper.DateTimeHelper;

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
        return String.format("c суммой %s рублей и датой %s %s", price, date, time);
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("date", String.format("%s %s", this.date, time), getExpectedDate(row))
                .compare("price", price, row.get("price"));
    }

    private String getExpectedDate(Map<String, String> row) {
        String saleDate = ApiStorage.getCustomVariableStorage().getSalesMap().get(row.get("date"));
        return DateTimeHelper.getExpectedSaleDate(saleDate, "EEEE, dd MMMM HH:mm");
    }
}
