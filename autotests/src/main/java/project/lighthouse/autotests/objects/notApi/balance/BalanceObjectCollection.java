package project.lighthouse.autotests.objects.notApi.balance;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.notApi.abstractObjects.AbstractObjectCollection;
import project.lighthouse.autotests.objects.notApi.abstractObjects.AbstractObjectNode;

public class BalanceObjectCollection extends AbstractObjectCollection {

    public BalanceObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public AbstractObjectNode createNode(WebElement element) {
        return new BalanceObjectItem(element);
    }
}
