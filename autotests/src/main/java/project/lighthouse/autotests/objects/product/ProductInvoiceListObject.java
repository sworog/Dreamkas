package project.lighthouse.autotests.objects.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.product.abstractObjects.AbstractProductObjectList;

import java.util.Map;

public class ProductInvoiceListObject extends AbstractProductObjectList {

    private String acceptanceDateFormatted;
    private String quantity;
    private String priceFormatted;
    private String totalPriceFormatted;
    private String invoiceSku;

    public String getInvoiceSku() {
        return invoiceSku;
    }

    public ProductInvoiceListObject(WebElement element) {
        super(element);
        setProperties();
    }

    public void setProperties() {
        acceptanceDateFormatted = element.findElement(By.xpath(".//*[@model_attr='acceptanceDateFormatted']")).getText();
        quantity = element.findElement(By.xpath(".//*[@model_attr='quantity']")).getText();
        priceFormatted = element.findElement(By.xpath(".//*[@model_attr='priceFormatted']")).getText();
        totalPriceFormatted = element.findElement(By.xpath(".//*[@model_attr='totalPriceFormatted']")).getText();
        invoiceSku = element.getAttribute("invoice-sku");
    }

    public Boolean rowIsEqual(Map<String, String> row) {
        return acceptanceDateFormatted.equals(row.get("acceptanceDateFormatted")) &&
                quantity.equals(row.get("quantity")) &&
                priceFormatted.equals(row.get("priceFormatted")) &&
                totalPriceFormatted.equals(row.get("totalPriceFormatted"));
    }
}
