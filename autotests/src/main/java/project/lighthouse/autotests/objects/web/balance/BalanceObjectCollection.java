package project.lighthouse.autotests.objects.web.balance;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class BalanceObjectCollection extends AbstractObjectCollection {

    public BalanceObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public BalanceObjectItem createNode(WebElement element) {
        return new BalanceObjectItem(element);
    }
}
