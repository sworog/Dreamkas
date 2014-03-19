package project.lighthouse.autotests.objects.web.order.order;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

/**
 * Class representing orders collection from orders list page
 */
public class OrderObjectCollection extends AbstractObjectCollection {

    public OrderObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public AbstractObject createNode(WebElement element) {
        return new OrderObject(element);
    }
}
