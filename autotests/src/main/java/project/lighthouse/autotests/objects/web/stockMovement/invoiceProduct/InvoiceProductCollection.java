package project.lighthouse.autotests.objects.web.stockMovement.invoiceProduct;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class InvoiceProductCollection<E extends InvoiceProductObject> extends AbstractObjectCollection<E> {

    public InvoiceProductCollection(WebDriver webDriver) {
        super(webDriver, By.name("invoiceProduct"));
    }

    @Override
    @SuppressWarnings("unchecked")
    public E createNode(WebElement element) {
        return (E) new InvoiceProductObject(element);
    }
}
