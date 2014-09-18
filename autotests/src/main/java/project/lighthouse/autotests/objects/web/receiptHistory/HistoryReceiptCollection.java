package project.lighthouse.autotests.objects.web.receiptHistory;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class HistoryReceiptCollection extends AbstractObjectCollection {

    public HistoryReceiptCollection(WebDriver webDriver) {
        super(webDriver, By.name("receipt"));
    }

    @Override
    public AbstractObject createNode(WebElement element) {
        return new HistoryReceipt(element);
    }
}
