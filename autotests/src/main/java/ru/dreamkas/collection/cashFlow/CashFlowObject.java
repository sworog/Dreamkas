package ru.dreamkas.collection.cashFlow;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import ru.dreamkas.apihelper.DateTimeHelper;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectClickable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectLocatable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ResultComparable;
import ru.dreamkas.collection.compare.CompareResults;

import java.util.Map;

public class CashFlowObject extends AbstractObject implements ObjectClickable, ObjectLocatable, ResultComparable {

    private String date;
    private String amount;
    private String comment;

    public CashFlowObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        date = getElement().getAttribute("data-date");
        amount = getElement().findElement(By.name("amount")).getText();
        comment = getElement().findElement(By.name("comment")).getText();
    }

    @Override
    public void click() {
        getElement().findElement(By.name("amount")).click();
    }

    @Override
    public String getObjectLocator() {
        return date + ":" + amount;
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        String convertedDate = DateTimeHelper.getDate(row.get("Дата"));

        return new CompareResults()
                .compare("Дата", date, convertedDate)
                .compare("Сумма", amount, row.get("Сумма"))
                .compare("Комментарий", comment, row.get("Комментарий"));
    }
}
