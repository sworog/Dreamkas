package project.lighthouse.autotests.objects.web.stockMovement.invoiceProduct;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.compare.CompareResults;
import project.lighthouse.autotests.objects.web.stockMovement.StockMovementWebObject;

import java.util.Map;

public class InvoiceProductObject extends StockMovementWebObject {

    private String priceEntered;

    public InvoiceProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        super.setProperties();
        priceEntered = getElement().findElement(By.name("priceEntered")).getText();
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return super.getCompareResults(row)
                .compare("priceEntered", priceEntered, row.get("priceEntered"));
    }

    public void clickDeleteIcon() {
        clickDeleteIcon("delInvoiceProduct");
    }
}
