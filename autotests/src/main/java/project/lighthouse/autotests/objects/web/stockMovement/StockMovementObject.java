package project.lighthouse.autotests.objects.web.stockMovement;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

public class StockMovementObject extends AbstractObject implements ObjectClickable, ObjectLocatable, ResultComparable {

    private String date;
    private String type;
    private String status;
    private String store;
    private String sumTotal;
    private String invoiceNumber;

    public StockMovementObject(WebElement element, WebDriver webDriver) {
        super(element, webDriver);
    }

    @Override
    public void setProperties() {
        date = getElement().getAttribute("data-invoice-date");
        type = getElement().findElement(By.name("type")).getText();
        status = setProperty(By.name("status"));
        store = getElement().findElement(By.name("store")).getText();
        sumTotal = getElement().findElement(By.name("sumTotal")).getText();
        invoiceNumber = getElement().getAttribute("data-invoice-number");
    }

    @Override
    public void click() {
        getElement().click();
    }

    @Override
    public String getObjectLocator() {
        return invoiceNumber;
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        // WORKAROUND for use example table objects which do not have status
        String status = row.get("status") != null
                ? row.get("status")
                : "";
        return new CompareResults()
                .compare("date", date, row.get("date"))
                .compare("type", type, row.get("type"))
                .compare("status", this.status, status)
                .compare("store", store, row.get("store"))
                .compare("sumTotal", sumTotal, row.get("sumTotal"));
    }
}
