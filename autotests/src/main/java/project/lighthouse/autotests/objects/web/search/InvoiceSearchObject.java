package project.lighthouse.autotests.objects.web.search;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractSearchObjectNode;

import java.util.Map;

public class InvoiceSearchObject extends AbstractSearchObjectNode {

    private String sku;
    private String acceptanceDate;
    private String supplier;
    private String accepter;
    private String legalEntity;
    private String supplierInvoiceSku;
    private String supplierInvoiceDate;

    public InvoiceSearchObject(WebDriver webDriver, WebElement element) {
        super(element, webDriver);
    }

    @Override
    public void setProperties() {
        sku = getElement().findElement(By.name("sku")).getText();
        acceptanceDate = getElement().findElement(By.name("acceptanceDate")).getText();
        supplier = getElement().findElement(By.name("supplier")).getText();
        accepter = getElement().findElement(By.name("accepter")).getText();
        legalEntity = getElement().findElement(By.name("legalEntity")).getText();
        supplierInvoiceSku = setProperty(By.name("supplierInvoiceSku"));
        supplierInvoiceDate = setProperty(By.name("supplierInvoiceDate"));
    }

    @Override
    public String getObjectLocator() {
        return sku;
    }

    @Override
    public Boolean rowIsEqual(Map<String, String> row) {
        return sku.equals(row.get("sku")) &&
                acceptanceDate.equals(row.get("acceptanceDate")) &&
                supplier.equals(row.get("supplier")) &&
                accepter.equals(row.get("accepter")) &&
                legalEntity.equals(row.get("legalEntity")) &&
                supplierInvoiceSku.equals(row.get("supplierInvoiceSku")) &&
                supplierInvoiceDate.equals(row.get("supplierInvoiceDate"));
    }
}
