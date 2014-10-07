package project.lighthouse.autotests.collection.receiptHistory;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObject;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.collection.compare.CompareResults;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.storage.Storage;

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
        String saleDate = Storage.getCustomVariableStorage().getSalesMap().get(row.get("date"));
        return DateTimeHelper.getExpectedSaleDate(saleDate, "EEEE, dd MMMM HH:mm");
    }
}
