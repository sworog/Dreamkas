package project.lighthouse.autotests.objects.web.stockMovement.stockIn;

import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.stockMovement.StockMovementProductObject;

public class StockInProductObject extends StockMovementProductObject {

    public StockInProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void clickDeleteIcon() {
        clickDeleteIcon("delStockInProduct");
    }
}
