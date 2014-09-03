package project.lighthouse.autotests.objects.web.receipt;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class ReceiptCollection extends AbstractObjectCollection {

    public ReceiptCollection(WebDriver webDriver) {
        super(webDriver, By.xpath("//*[@name='productList']/tbody/*[@name='product']"));
    }

    @Override
    public ReceiptObject createNode(WebElement element) {
        return new ReceiptObject(element);
    }
}
