package project.lighthouse.autotests.objects.notApi.search;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.objects.notApi.abstractObjects.AbstractSearchObjectNode;

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
        Waiter waiter = new Waiter(getWebDriver(), 0);
        this.sku = getElement().findElement(By.name("sku")).getText();
        this.acceptanceDate = getElement().findElement(By.name("acceptanceDate")).getText();
        this.supplier = getElement().findElement(By.name("supplier")).getText();
        this.accepter = getElement().findElement(By.name("accepter")).getText();
        this.legalEntity = getElement().findElement(By.name("legalEntity")).getText();
        this.supplierInvoiceSku = null;
        if (!waiter.invisibilityOfElementLocated(By.name("supplierInvoiceSku"))) {
            this.supplierInvoiceSku = getElement().findElement(By.name("supplierInvoiceSku")).getText();
        }
        this.supplierInvoiceDate = null;
        if (!waiter.invisibilityOfElementLocated(By.name("supplierInvoiceDate"))) {
            this.supplierInvoiceDate = getElement().findElement(By.name("supplierInvoiceDate")).getText();
        }
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
