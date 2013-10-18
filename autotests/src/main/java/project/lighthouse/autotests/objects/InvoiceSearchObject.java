package project.lighthouse.autotests.objects;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;

public class InvoiceSearchObject {

    private WebDriver driver;
    private Waiter waiter;
    private String sku;
    private String acceptanceDate;
    private String supplier;
    private String accepter;
    private String legalEntity;
    private String supplierInvoiceSku;
    private String supplierInvoiceDate;

    private InvoiceSearchObject(WebDriver driver) {
        this.driver = driver;
        waiter = new Waiter(driver, 1);
    }

    public InvoiceSearchObject(WebDriver driver, WebElement webElement) {
        this(driver);
        setInvoiceProperties(webElement);
    }

    public String getSku() {
        return sku;
    }

    public String getAcceptanceDate() {
        return acceptanceDate;
    }

    public String getSupplier() {
        return supplier;
    }

    public String getAccepter() {
        return accepter;
    }

    public String getLegalEntity() {
        return legalEntity;
    }

    public String getSupplierInvoiceSku() {
        return supplierInvoiceSku;
    }

    public String getSupplierInvoiceDate() {
        return supplierInvoiceDate;
    }

    private void setInvoiceProperties(WebElement webElement) {
        this.sku = webElement.findElement(By.name("sku")).getText();
        this.acceptanceDate = webElement.findElement(By.name("acceptanceDate")).getText();
        this.supplier = webElement.findElement(By.name("supplier")).getText();
        this.accepter = webElement.findElement(By.name("accepter")).getText();
        this.legalEntity = webElement.findElement(By.name("legalEntity")).getText();
        this.supplierInvoiceSku = null;
        if (!waiter.invisibilityOfElementLocated(By.name("supplierInvoiceSku"))) {
            this.supplierInvoiceSku = webElement.findElement(By.name("supplierInvoiceSku")).getText();
        }
        this.supplierInvoiceDate = null;
        if (!waiter.invisibilityOfElementLocated(By.name("supplierInvoiceDate"))) {
            this.supplierInvoiceDate = webElement.findElement(By.name("supplierInvoiceDate")).getText();
        }
    }
}
