package project.lighthouse.autotests.objects.notApi.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.notApi.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.notApi.abstractObjects.AbstractObjectCollection;

public class ReturnListObjectCollection extends AbstractObjectCollection {

    public ReturnListObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public AbstractObject createNode(WebElement element) {
        return new ReturnListObject(element);
    }
}
