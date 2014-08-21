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
    private String number;

    public StockMovementObject(WebElement element, WebDriver webDriver) {
        super(element, webDriver);
    }

    @Override
    public void setProperties() {
        setDate();
        type = getElement().findElement(By.name("type")).getText();
        status = setProperty(By.name("status"));
        store = getElement().findElement(By.name("store")).getText();
        sumTotal = getElement().findElement(By.name("sumTotal")).getText();
        setNumber();
    }

    private void setNumber() {
        String invoiceNumber = getElement().getAttribute("data-invoice-number");
        String writeOffNumber = getElement().getAttribute("data-writeoff-number");
        String stockInNumber = getElement().getAttribute("data-stockin-number");
        if (invoiceNumber != null) {
            number = invoiceNumber;
        } else if (writeOffNumber != null) {
            number = writeOffNumber;
        } else if (stockInNumber != null) {
            number = stockInNumber;
        }
    }

    public void setDate() {
        String invoiceDate = getElement().getAttribute("data-invoice-date");
        String writeOffDate = getElement().getAttribute("data-writeoff-date");
        String stockInDate = getElement().getAttribute("data-stockin-date");
        if (invoiceDate != null) {
            date = invoiceDate;
        } else if (writeOffDate != null) {
            date = writeOffDate;
        } else if (stockInDate != null) {
            date = stockInDate;
        }
    }

    @Override
    public void click() {
        getElement().click();
    }

    @Override
    public String getObjectLocator() {
        return number;
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
