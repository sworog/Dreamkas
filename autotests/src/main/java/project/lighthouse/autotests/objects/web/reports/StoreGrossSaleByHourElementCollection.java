package project.lighthouse.autotests.objects.web.reports;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class StoreGrossSaleByHourElementCollection extends AbstractObjectCollection {

    public StoreGrossSaleByHourElementCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public AbstractObject createNode(WebElement element) {
        return new StoreGrossSaleByHourElement(element);
    }
}
