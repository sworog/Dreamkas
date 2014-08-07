package project.lighthouse.autotests.objects.web.supplier;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class SupplierCollection extends AbstractObjectCollection {

    public SupplierCollection(WebDriver webDriver) {
        super(webDriver, By.className("supplier__link"));
    }

    @Override
    public SupplierObject createNode(WebElement element) {
        return new SupplierObject(element);
    }
}
