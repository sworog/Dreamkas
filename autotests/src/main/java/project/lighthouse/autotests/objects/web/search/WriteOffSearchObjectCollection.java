package project.lighthouse.autotests.objects.web.search;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractSearchObjectCollection;

public class WriteOffSearchObjectCollection extends AbstractSearchObjectCollection {

    public WriteOffSearchObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public WriteOffSearchObject createNode(WebElement element) {
        return new WriteOffSearchObject(element);
    }
}
