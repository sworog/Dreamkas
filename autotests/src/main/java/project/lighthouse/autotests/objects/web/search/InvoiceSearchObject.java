package project.lighthouse.autotests.objects.web.search;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractSearchObjectNode;
import project.lighthouse.autotests.objects.web.compare.CompareResults;
import project.lighthouse.autotests.objects.web.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.objectInterfaces.ResultComparable;

import java.util.Map;

public class InvoiceSearchObject extends AbstractSearchObjectNode implements ObjectLocatable, ResultComparable, ObjectClickable {

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
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("sku", sku, row.get("sku"))
                .compare("acceptanceDate", acceptanceDate, row.get("acceptanceDate"))
                .compare("supplier", supplier, row.get("supplier"))
                .compare("accepter", accepter, row.get("accepter"))
                .compare("legalEntity", legalEntity, row.get("legalEntity"))
                .compare("supplierInvoiceSku", supplierInvoiceSku, row.get("supplierInvoiceSku"))
                .compare("supplierInvoiceDate", supplierInvoiceDate, row.get("supplierInvoiceDate"));
    }

    @Override
    public void click() {
        getElement().click();
    }
}
