package project.lighthouse.autotests.objects.web.receipt;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

public class ReceiptObject extends AbstractObject implements ObjectClickable, ObjectLocatable, ResultComparable {

    private String name;
    private String quantity;
    private String price;

    public ReceiptObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("name")).getText();
        quantity = getElement().findElement(By.name("quantity")).getText();
        price = getElement().findElement(By.name("price")).getText();
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
                .compare("quantity", quantity, row.get("quantity"))
                .compare("price", price, row.get("price"));
    }
}
