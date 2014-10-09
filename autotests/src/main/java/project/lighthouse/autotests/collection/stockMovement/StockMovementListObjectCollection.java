package project.lighthouse.autotests.collection.stockMovement;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObjectCollection;

public class StockMovementListObjectCollection<E extends StockMovementListObject> extends AbstractObjectCollection<E> {

    public StockMovementListObjectCollection(WebDriver webDriver) {
        super(webDriver, By.xpath("//*[@class='writeOff__link' or @class='invoice__link' or @class='stockIn__link' or @class='supplierReturn__link']"));
    }

    @Override
    @SuppressWarnings("unchecked")
    public E createNode(WebElement element) {
        return (E) new StockMovementListObject(element, getWebDriver());
    }
}
