package project.lighthouse.autotests.objects.web.stockMovement;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

import java.util.List;

public class StockMovementObjectCollection extends AbstractObjectCollection {

    public StockMovementObjectCollection(WebDriver webDriver) {
        super(webDriver, By.xpath("//*[@class='writeOff__link' or @class='invoice__link' or @class='stockIn__link']"));
    }

    @Override
    public StockMovementObject createNode(WebElement element) {
        return null;
    }

    @Override
    public void init(WebDriver webDriver, By findBy) {
        List<WebElement> webElementList = new Waiter(webDriver).getVisibleWebElements(findBy);
        for (WebElement element : webElementList) {
            StockMovementObject stockMovementObject = new StockMovementObject(element, webDriver);
            add(stockMovementObject);
        }
    }
}
