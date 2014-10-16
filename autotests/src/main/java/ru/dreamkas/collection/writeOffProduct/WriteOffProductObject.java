package ru.dreamkas.collection.writeOffProduct;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectClickable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectLocatable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ResultComparable;
import ru.dreamkas.collection.compare.CompareResults;

import java.util.Map;

public class WriteOffProductObject extends AbstractObject implements ObjectClickable, ObjectLocatable, ResultComparable {

    private String name;
    private String priceEntered;
    private String quantity;
    private String totalPrice;
    private String cause;

    public WriteOffProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("name")).getText();
        priceEntered = getElement().findElement(By.name("price")).getText();
        quantity = getElement().findElement(By.name("quantity")).getText();
        totalPrice = getElement().findElement(By.name("totalPrice")).getText();
        cause = getElement().findElement(By.name("cause")).getText();
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
                .compare("price", priceEntered, row.get("price"))
                .compare("quantity", quantity, row.get("quantity"))
                .compare("cause", cause, row.get("cause"))
                .compare("totalPrice", totalPrice, row.get("totalPrice"));
    }

    public void deleteIconClick() {
        getElement().findElement(org.openqa.selenium.By.xpath(".//*[@class='delWriteOffProduct btn fa fa-times']")).click();
    }
}
