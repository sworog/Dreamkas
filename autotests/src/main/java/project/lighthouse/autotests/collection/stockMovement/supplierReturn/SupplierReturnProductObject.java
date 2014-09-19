package project.lighthouse.autotests.collection.stockMovement.supplierReturn;

import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.stockMovement.StockMovementProductObject;

public class SupplierReturnProductObject extends StockMovementProductObject {

    public SupplierReturnProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void clickDeleteIcon() {
        clickDeleteIcon("delSupplierReturnProduct");
    }
}
