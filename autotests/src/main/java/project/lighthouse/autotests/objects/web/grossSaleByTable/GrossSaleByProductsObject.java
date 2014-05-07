package project.lighthouse.autotests.objects.web.grossSaleByTable;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectValueColorable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

public class GrossSaleByProductsObject extends AbstractObject implements ObjectLocatable, ResultComparable, ObjectValueColorable {

    private String productName;
    private String productSku;
    private String productBarcode;
    private WebElement todayValue;
    private String yesterdayValue;
    private String weekAgoValue;

    public GrossSaleByProductsObject(WebElement element, WebDriver webDriver) {
        super(element, webDriver);
    }

    @Override
    public void setProperties() {
        productName = getElement().findElement(By.name("product.name")).getText();
        productSku = getElement().findElement(By.name("product.sku")).getText();
        productBarcode = setProperty(By.name("product.barcode"));
        todayValue = getElement().findElement(By.name("today.runningSum"));
        yesterdayValue = getElement().findElement(By.name("yesterday.runningSum")).getText();
        weekAgoValue = getElement().findElement(By.name("weekAgo.runningSum")).getText();
    }

    @Override
    public String getObjectLocator() {
        return productName;
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("productName", productName, row.get("productName"))
                .compare("productSku", productSku, row.get("productSku"))
                .compare("productBarcode", productBarcode, row.get("productBarcode"))
                .compare("todayValue", todayValue.getText(), row.get("todayValue"))
                .compare("yesterdayValue", yesterdayValue, row.get("yesterdayValue"))
                .compare("weekAgoValue", weekAgoValue, row.get("weekAgoValue"));
    }

    public Boolean isValueColor() {
        return todayValue.getAttribute("style").equals("color: red;");
    }
}
