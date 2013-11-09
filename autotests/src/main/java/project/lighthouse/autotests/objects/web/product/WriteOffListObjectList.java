package project.lighthouse.autotests.objects.web.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectNode;

public class WriteOffListObjectList extends AbstractObjectCollection {

    public WriteOffListObjectList(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public AbstractObjectNode createNode(WebElement element) {
        return new WriteOffListObject(element);
    }
}
