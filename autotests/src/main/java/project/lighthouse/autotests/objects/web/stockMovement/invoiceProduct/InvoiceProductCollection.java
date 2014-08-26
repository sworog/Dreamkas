package project.lighthouse.autotests.objects.web.stockMovement.invoiceProduct;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class InvoiceProductCollection extends AbstractObjectCollection<InvoiceProductObject> {

    public InvoiceProductCollection(WebDriver webDriver) {
        super(webDriver, By.name("invoiceProduct"));
    }

    @Override
    public InvoiceProductObject createNode(WebElement element) {
        return new InvoiceProductObject(element);
    }
}
