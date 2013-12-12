package project.lighthouse.autotests.objects.web.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class WriteOffListObjectList extends AbstractObjectCollection {

    public WriteOffListObjectList(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public WriteOffListObject createNode(WebElement element) {
        return new WriteOffListObject(element);
    }
}
