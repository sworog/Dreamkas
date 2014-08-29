package project.lighthouse.autotests.objects.web.stockMovement.supplierReturn;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectCollection;

public class SupplierReturnProductCollection<E extends SupplierReturnProductObject> extends AbstractObjectCollection<E> {

    public SupplierReturnProductCollection(WebDriver webDriver) {
        super(webDriver, By.name("supplierReturnProduct"));
    }

    @Override
    @SuppressWarnings("unchecked")
    public E createNode(WebElement element) {
        return (E) new SupplierReturnProductObject(element);
    }
}
