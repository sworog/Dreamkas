package project.lighthouse.autotests.objects.web.stockMovement.stockIn;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class StockInProductCollection extends AbstractObjectCollection<StockInProduct> {

    public StockInProductCollection(WebDriver webDriver) {
        super(webDriver, By.name("stockInProduct"));
    }

    @Override
    public StockInProduct createNode(WebElement element) {
        return new StockInProduct(element);
    }
}
