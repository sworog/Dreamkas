package project.lighthouse.autotests.objects.web.order.orderProduct;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

/**
 * Object representing order product
 */
public class OrderProductObject extends AbstractObject implements ResultComparable, ObjectLocatable, ObjectClickable {

    private String name;
    private String units;
    private String quantity;
    private String price;
    private String sum;

    public OrderProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("productName")).getText();
        units = getElement().findElement(By.name("productUnits")).getText();
        quantity = getElement().findElement(By.name("productAmount")).getText();
        price = getElement().findElement(By.name("productPrice")).getText();
        sum = getElement().findElement(By.name("productSum")).getText();
    }


    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("name", name, row.get("name"))
                .compare("units", units, row.get("units"))
                .compare("quantity", quantity, row.get("quantity"))
                .compare("retailPrice", price, row.get("retailPrice"))
                .compare("totalSum", sum, row.get("totalSum"));
    }

    @Override
    public String getObjectLocator() {
        return name;
    }

    public String getQuantity() {
        return quantity;
    }

    @Override
    public void click() {
        getElement().click();
    }

    public void quantityType(String value) {
        WebElement quantityWebElement = getElement().findElement(By.xpath(".//*[@data-name='quantity']"));
        quantityWebElement.clear();
        quantityWebElement.sendKeys(value);
    }

    public void deleteIconClick() {
        getElement().findElement(By.xpath(".//*[@class='icon-remove-sign form_order__removeProductLink']")).click();
    }

    public String getSum() {
        return sum;
    }
}
