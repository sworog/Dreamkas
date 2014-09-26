package project.lighthouse.autotests.collection.stockMovement.supplierReturn;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObjectCollection;

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
