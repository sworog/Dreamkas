package ru.dreamkas.collection.reports.grossMarginSales;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectClickable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectLocatable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ResultComparable;
import ru.dreamkas.collection.compare.CompareResults;

import java.util.Map;

public class GrossMarginSalesByGroupsObject extends AbstractObject implements ResultComparable, ObjectLocatable, ObjectClickable {

    private String groupName;
    private String grossSales;
    private String grossMargin;
    private String costOfGoods;

    public GrossMarginSalesByGroupsObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        groupName = getElement().findElement(By.name("groupName")).getText();
        grossSales = getElement().findElement(By.name("grossSales")).getText();
        grossMargin = getElement().findElement(By.name("grossMargin")).getText();
        costOfGoods = getElement().findElement(By.name("costOfGoods")).getText();
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("товар", groupName, row.get("товар"));
    }

    @Override
    public String getObjectLocator() {
        return groupName;
    }

    @Override
    public void click() {
        getElement().click();
    }
}
