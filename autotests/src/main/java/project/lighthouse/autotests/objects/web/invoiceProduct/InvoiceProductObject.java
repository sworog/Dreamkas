package project.lighthouse.autotests.objects.web.invoiceProduct;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

public class InvoiceProductObject extends AbstractObject implements ObjectClickable, ObjectLocatable, ResultComparable {

    private String name;
    private String priceEntered;
    private String quantity;
    private String totalPrice;


    public InvoiceProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("name")).getText();
        priceEntered = getElement().findElement(By.name("priceEntered")).getText();
        quantity = getElement().findElement(By.name("quantity")).getText();
        totalPrice = getElement().findElement(By.name("totalPrice")).getText();
    }

    @Override
    public void click() {
        getElement().click();
    }

    @Override
    public String getObjectLocator() {
        return name;
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("name", name, row.get("name"))
                .compare("priceEntered", priceEntered, row.get("priceEntered"))
                .compare("quantity", quantity, row.get("quantity"))
                .compare("totalPrice", totalPrice, row.get("totalPrice"));
    }

    public void deleteIconClick() {
        getElement().findElement(org.openqa.selenium.By.xpath(".//*[@class='delInvoiceProduct btn fa fa-times']")).click();
    }
}
