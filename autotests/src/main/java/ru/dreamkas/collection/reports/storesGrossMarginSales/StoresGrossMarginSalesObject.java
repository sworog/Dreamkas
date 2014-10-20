package ru.dreamkas.collection.reports.storesGrossMarginSales;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectClickable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectLocatable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ResultComparable;
import ru.dreamkas.collection.compare.CompareResults;

import java.util.Map;

public class StoresGrossMarginSalesObject extends AbstractObject implements ResultComparable, ObjectLocatable, ObjectClickable {

    private String name;
    private String grossSales;
    private String grossMargin;
    private String costOfGoods;

    public StoresGrossMarginSalesObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("name")).getText();
        grossSales = getElement().findElement(By.name("grossSales")).getText();
        grossMargin = getElement().findElement(By.name("grossMargin")).getText();
        costOfGoods = getElement().findElement(By.name("costOfGoods")).getText();
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("Магазин", name, row.get("Магазин"))
                .compare("Продажи", grossSales, row.get("Продажи"))
                .compare("Себестоимость", costOfGoods, row.get("Себестоимость"))
                .compare("Прибыль", grossMargin, row.get("Прибыль"));
    }

    @Override
    public String getObjectLocator() {
        return name;
    }

    @Override
    public void click() {
        getElement().click();
    }
}
