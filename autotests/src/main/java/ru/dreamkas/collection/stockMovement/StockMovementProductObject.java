package ru.dreamkas.collection.stockMovement;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectClickable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectLocatable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ResultComparable;
import ru.dreamkas.collection.compare.CompareResults;

import java.util.Map;

public abstract class StockMovementProductObject extends AbstractObject implements ObjectClickable, ObjectLocatable, ResultComparable {

    protected String name;
    protected String price;
    protected String quantity;
    protected String totalPrice;

    public StockMovementProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("name")).getText();
        price = getElement().findElement(By.name("price")).getText();
        quantity = getElement().findElement(By.name("quantity")).getText();
        totalPrice = getElement().findElement(By.name("totalPrice")).getText();
    }

    @Override
    public void click() {
        getElement().click();
    }

    @Override
    public String getObjectLocator() {
        return name;
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("name", name, row.get("name"))
                .compare("price", price, row.get("price"))
                .compare("quantity", quantity, row.get("quantity"))
                .compare("totalPrice", totalPrice, row.get("totalPrice"));
    }

    public void clickDeleteIcon() {
        String xpath = (".//*[contains(@class, 'removeProductLink')]");
        getElement().findElement(org.openqa.selenium.By.xpath(xpath)).click();

    }
}
