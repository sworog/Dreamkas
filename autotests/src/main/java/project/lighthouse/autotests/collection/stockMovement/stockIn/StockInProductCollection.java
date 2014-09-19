package project.lighthouse.autotests.collection.stockMovement.stockIn;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObjectCollection;

public class StockInProductCollection<E extends StockInProductObject> extends AbstractObjectCollection<E> {

    public StockInProductCollection(WebDriver webDriver) {
        super(webDriver, By.name("stockInProduct"));
    }

    @Override
    @SuppressWarnings("unchecked")
    public E createNode(WebElement element) {
        return (E) new StockInProductObject(element);
    }
}
