package project.lighthouse.autotests.objects.web.order;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

/**
 * Object representing order product
 */
public class OrderProductObject extends AbstractObject implements ResultComparable, ObjectLocatable {

    private String name;
    private String quantity;
    private String price;
    private String sum;
    private String inventory;

    public OrderProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("name")).getText();
        quantity = getElement().findElement(By.name("quantity")).getText();
        price = getElement().findElement(By.name("price")).getText();
        sum = getElement().findElement(By.name("sum")).getText();
        inventory = getElement().findElement(By.name("inventory")).getText();
    }


    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("name", name, row.get("name"))
                .compare("quantity", quantity, row.get("quantity"))
                .compare("price", price, row.get("price"))
                .compare("sum", sum, row.get("sum"))
                .compare("inventory", inventory, row.get("inventory"));
    }

    @Override
    public String getObjectLocator() {
        return name;
    }
}
