package project.lighthouse.autotests.objects.web.writeOff;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class WriteOffProductCollection extends AbstractObjectCollection {

    public WriteOffProductCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public WriteOffProductObject createNode(WebElement element) {
        return new WriteOffProductObject(element);
    }
}
