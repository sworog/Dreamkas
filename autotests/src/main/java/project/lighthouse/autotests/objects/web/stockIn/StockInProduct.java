package project.lighthouse.autotests.objects.web.stockIn;

import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.invoiceProduct.InvoiceProductObject;

public class StockInProduct extends InvoiceProductObject {

    public StockInProduct(WebElement element) {
        super(element);
    }

    @Override
    public void deleteIconClick() {
        getElement().findElement(org.openqa.selenium.By.xpath(".//*[@class='delStockInProduct btn fa fa-times']")).click();
    }
}
