package project.lighthouse.autotests.objects.web.stockMovement.supplierReturn;

import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.stockMovement.StockMovementProductObject;

public class SupplierReturnProductObject extends StockMovementProductObject {

    public SupplierReturnProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void clickDeleteIcon() {
        clickDeleteIcon("delSupplierReturnProduct");
    }
}
