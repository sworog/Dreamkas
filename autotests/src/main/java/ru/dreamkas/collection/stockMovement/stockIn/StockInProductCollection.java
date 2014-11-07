package ru.dreamkas.collection.stockMovement.stockIn;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObjectCollection;

public class StockInProductCollection<E extends StockInProductObject> extends AbstractObjectCollection<E> {

    public StockInProductCollection(WebDriver webDriver) {
        super(webDriver, By.name("stockMovementProduct"));
    }

    @Override
    @SuppressWarnings("unchecked")
    public E createNode(WebElement element) {
        return (E) new StockInProductObject(element);
    }
}
