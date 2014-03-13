package project.lighthouse.autotests.objects.web.order;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

/**
 * Collection representing order products
 */
public class OrderProductObjectCollection extends AbstractObjectCollection {

    public OrderProductObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public AbstractObject createNode(WebElement element) {
        return new OrderProductObject(element);
    }
}
