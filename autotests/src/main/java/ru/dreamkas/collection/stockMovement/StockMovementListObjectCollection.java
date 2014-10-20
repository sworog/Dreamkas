package ru.dreamkas.collection.stockMovement;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObjectCollection;

public class StockMovementListObjectCollection<E extends StockMovementListObject> extends AbstractObjectCollection<E> {

    public StockMovementListObjectCollection(WebDriver webDriver) {
        super(webDriver, By.xpath("//*[@name='writeOff' or @name='invoice' or @name='stockIn' or @name='supplierReturn']"));
    }

    @Override
    @SuppressWarnings("unchecked")
    public E createNode(WebElement element) {
        return (E) new StockMovementListObject(element, getWebDriver());
    }
}
