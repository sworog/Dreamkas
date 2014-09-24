package project.lighthouse.autotests.collection.stockMovement.invoiceProduct;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObjectCollection;

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
