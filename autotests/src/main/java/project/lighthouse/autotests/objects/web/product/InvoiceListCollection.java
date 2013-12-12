package project.lighthouse.autotests.objects.web.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class InvoiceListCollection extends AbstractObjectCollection {

    public InvoiceListCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public InvoiceListObject createNode(WebElement element) {
        return new InvoiceListObject(element);
    }
}
