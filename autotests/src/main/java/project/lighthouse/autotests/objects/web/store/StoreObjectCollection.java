package project.lighthouse.autotests.objects.web.store;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class StoreObjectCollection extends AbstractObjectCollection {

    public StoreObjectCollection(WebDriver webDriver) {
        super(webDriver, By.className("store__link"));
    }

    @Override
    public StoreObject createNode(WebElement element) {
        return new StoreObject(element);
    }
}
