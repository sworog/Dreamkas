package project.lighthouse.autotests.objects.web.invoice;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;


public class InvoiceProductsCollection extends AbstractObjectCollection {

    public InvoiceProductsCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public InvoiceProductObject createNode(WebElement element) {
        return new InvoiceProductObject(element);
    }
}
