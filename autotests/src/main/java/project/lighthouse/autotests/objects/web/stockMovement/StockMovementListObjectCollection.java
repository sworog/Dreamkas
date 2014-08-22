package project.lighthouse.autotests.objects.web.stockMovement;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

import java.util.List;

public class StockMovementListObjectCollection extends AbstractObjectCollection<StockMovementListObject> {

    public StockMovementListObjectCollection(WebDriver webDriver) {
        super(webDriver, By.xpath("//*[@class='writeOff__link' or @class='invoice__link' or @class='stockIn__link']"));
    }

    @Override
    public StockMovementListObject createNode(WebElement element) {
        return null;
    }

    @Override
    public void init(WebDriver webDriver, By findBy) {
        List<WebElement> webElementList = new Waiter(webDriver).getVisibleWebElements(findBy);
        for (WebElement element : webElementList) {
            StockMovementListObject stockMovementListObject = new StockMovementListObject(element, webDriver);
            add(stockMovementListObject);
        }
    }
}
