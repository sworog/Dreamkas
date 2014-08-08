package project.lighthouse.autotests.objects.web.invoice;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

public class InvoiceObject extends AbstractObject implements ObjectClickable, ObjectLocatable, ResultComparable {

    private String invoiceId;
    private String date;
    private String operation;
    private String store;
    private String sum;

    public InvoiceObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        invoiceId = getElement().findElement(By.name("invoiceId")).getText();
        date = getElement().findElement(By.name("date")).getText();
        operation = getElement().findElement(By.name("operation")).getText();
        store = getElement().findElement(By.name("store")).getText();
        sum = getElement().findElement(By.name("sum")).getText();
    }

    @Override
    public void click() {
        getElement().click();
    }

    @Override
    public String getObjectLocator() {
        return invoiceId;
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("date", date, row.get("date"))
                .compare("operation", operation, row.get("operation"))
                .compare("store", store, row.get("store"))
                .compare("sum", sum, row.get("sum"));
    }
}
