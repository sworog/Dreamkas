package ru.dreamkas.collection.reports.goodsGrossMarginSales;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ResultComparable;
import ru.dreamkas.collection.compare.CompareResults;

import java.util.Map;

public class GoodsGrossMarginSalesByProductsObject extends AbstractObject implements ResultComparable {

    private String name;
    private String grossSales;
    private String grossMargin;
    private String costOfGoods;
    private String quantity;

    public GoodsGrossMarginSalesByProductsObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("name")).getText();
        grossSales = getElement().findElement(By.name("grossSales")).getText();
        grossMargin = getElement().findElement(By.name("grossMargin")).getText();
        costOfGoods = getElement().findElement(By.name("costOfGoods")).getText();
        quantity = getElement().findElement(By.name("quantity")).getText();
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("товар", name, row.get("товар"))
                .compare("продажи", grossSales, row.get("продажи"))
                .compare("себестоимость", costOfGoods, row.get("себестоимость"))
                .compare("прибыль", grossMargin, row.get("прибыль"))
                .compare("количество", quantity, row.get("количество"));
    }
}
