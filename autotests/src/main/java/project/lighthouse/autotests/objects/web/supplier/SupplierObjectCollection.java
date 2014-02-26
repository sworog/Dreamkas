package project.lighthouse.autotests.objects.web.supplier;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

/**
 * Collection to store supplier table elements
 */
public class SupplierObjectCollection extends AbstractObjectCollection {

    public SupplierObjectCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public AbstractObject createNode(WebElement element) {
        return new SupplierObject(element);
    }
}
