package project.lighthouse.autotests.objects.web.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.compare.CompareResults;
import project.lighthouse.autotests.objects.web.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.objectInterfaces.ResultComparable;

import java.util.Map;

public class InvoiceListObject extends AbstractObject implements ObjectLocatable, ResultComparable {

    private String acceptanceDateFormatted;
    private String quantity;
    private String priceFormatted;
    private String totalPriceFormatted;
    private String invoiceSku;

    public InvoiceListObject(WebElement element) {
        super(element);
    }

    public void setProperties() {
        acceptanceDateFormatted = getElement().findElement(By.xpath(".//*[@model-attribute='acceptanceDateFormatted']")).getText();
        quantity = getElement().findElement(By.xpath(".//*[@model-attribute='quantityElement']")).getText();
        priceFormatted = getElement().findElement(By.xpath(".//*[@model-attribute='priceFormatted']")).getText();
        totalPriceFormatted = getElement().findElement(By.xpath(".//*[@model-attribute='totalPriceFormatted']")).getText();
        invoiceSku = getElement().getAttribute("invoice-sku");
    }

    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("acceptanceDateFormatted", acceptanceDateFormatted, row.get("acceptanceDateFormatted"))
                .compare("quantity", quantity, row.get("quantity"))
                .compare("priceFormatted", priceFormatted, row.get("priceFormatted"))
                .compare("totalPriceFormatted", totalPriceFormatted, row.get("totalPriceFormatted"));
    }

    public String getObjectLocator() {
        return invoiceSku;
    }
}
