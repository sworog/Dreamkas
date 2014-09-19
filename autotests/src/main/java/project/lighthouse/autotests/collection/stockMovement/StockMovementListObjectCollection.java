package project.lighthouse.autotests.collection.stockMovement;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObjectCollection;

import java.util.List;

public class StockMovementListObjectCollection<E extends StockMovementListObject> extends AbstractObjectCollection<E> {

    public StockMovementListObjectCollection(WebDriver webDriver) {
        super(webDriver, By.xpath("//*[@class='writeOff__link' or @class='invoice__link' or @class='stockIn__link' or @class='supplierReturn__link']"));
    }

    @Override
    public E createNode(WebElement element) {
        return null;
    }

    @Override
    @SuppressWarnings("unchecked")
    public void init(WebDriver webDriver, By findBy) {
        List<WebElement> webElementList = new Waiter(webDriver).getVisibleWebElements(findBy);
        for (WebElement element : webElementList) {
            E stockMovementListObject = (E) new StockMovementListObject(element, webDriver);
            add(stockMovementListObject);
        }
    }
}
