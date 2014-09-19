package project.lighthouse.autotests.collection.supplier;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObjectCollection;

public class SupplierCollection extends AbstractObjectCollection {

    public SupplierCollection(WebDriver webDriver) {
        super(webDriver, By.className("supplier__link"));
    }

    @Override
    public SupplierObject createNode(WebElement element) {
        return new SupplierObject(element);
    }
}
