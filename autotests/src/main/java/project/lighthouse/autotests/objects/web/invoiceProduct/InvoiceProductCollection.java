package project.lighthouse.autotests.objects.web.invoiceProduct;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class InvoiceProductCollection extends AbstractObjectCollection {

    public InvoiceProductCollection(WebDriver webDriver) {
        super(webDriver, By.name("invoiceProduct"));
    }

    public InvoiceProductCollection(WebDriver webDriver, By findBy) {
        super(webDriver, findBy);
    }

    @Override
    public InvoiceProductObject createNode(WebElement element) {
        return new InvoiceProductObject(element);
    }
}
