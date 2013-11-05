package project.lighthouse.autotests.objects.web.balance;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObjectNode;

import java.util.Map;

public class BalanceObjectItem extends AbstractObjectNode {

    private String sku;
    private String name;
    private String barcode;
    private String balance;
    private String units;
    private String averagePurchasePrice;
    private String lastPurchasePrice;

    public BalanceObjectItem(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        sku = getElement().findElement(By.xpath(".//*[@model_attr='product.sku']")).getText();
        name = getElement().findElement(By.xpath(".//*[@model_attr='product.name']")).getText();
        barcode = getElement().findElement(By.xpath(".//*[@model_attr='product.barcode']")).getText();
        balance = getElement().findElement(By.xpath(".//*[@model_attr='amount']")).getText();
        units = getElement().findElement(By.xpath(".//*[@model_attr='unitsFormatted']")).getText();
        averagePurchasePrice = getElement().findElement(By.xpath(".//*[@model_attr='averagePurchasePriceFormatted']")).getText();
        lastPurchasePrice = getElement().findElement(By.xpath(".//*[@model_attr='lastPurchasePriceFormatted']")).getText();
    }

    @Override
    public Boolean rowIsEqual(Map<String, String> row) {
        return sku.equals(row.get("sku")) &&
                name.equals(row.get("name")) &&
                barcode.equals(row.get("barcode")) &&
                balance.equals(row.get("balance")) &&
                units.equals(row.get("units")) &&
                averagePurchasePrice.equals(row.get("averagePurchasePrice")) &&
                lastPurchasePrice.equals(row.get("lastPurchasePrice"));
    }
}
