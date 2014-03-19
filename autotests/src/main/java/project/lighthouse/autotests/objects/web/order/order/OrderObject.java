package project.lighthouse.autotests.objects.web.order.order;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

/**
 * Class representing single order row on orders list page
 */
public class OrderObject extends AbstractObject implements ObjectLocatable, ObjectClickable, ResultComparable {

    private static String number;
    private static String supplier;
    private static String date;

    public OrderObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        number = getElement().findElement(By.name("orderNumber")).getText();
        supplier = getElement().findElement(By.name("orderSupplier")).getText();
        date = getElement().findElement(By.name("orderDate")).getText();
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
        return new CompareResults()
                .compare("number", number, row.get("number"))
                .compare("supplier", supplier, row.get("supplier"))
                .compare("date", date, row.get("date"));
    }
}
