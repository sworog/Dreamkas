package project.lighthouse.autotests.objects.web.product.returnList;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class ReturnListObjectCollection extends AbstractObjectCollection {

    public ReturnListObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public ReturnListObject createNode(WebElement element) {
        return new ReturnListObject(element);
    }
}
