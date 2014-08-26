package project.lighthouse.autotests.objects.web.stockMovement.stockIn;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

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
