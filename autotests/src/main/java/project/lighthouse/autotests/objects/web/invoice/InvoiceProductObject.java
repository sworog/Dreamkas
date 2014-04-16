package project.lighthouse.autotests.objects.web.invoice;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

public class InvoiceProductObject extends AbstractObject implements ObjectLocatable, ResultComparable, ObjectClickable {

    private String name;
    private String quantity;
    private String units;
    private String price;
    private String sum;
    private String vatSum;

    public InvoiceProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("productName")).getText();
        quantity = getElement().findElement(By.name("quantity")).getText();
        units = getElement().findElement(By.name("productUnits")).getText();
        price = getElement().findElement(By.name("priceEntered")).getText();
        sum = getElement().findElement(By.name("totalPrice")).getText();
        vatSum = getElement().findElement(By.name("totalAmountVAT")).getText();
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("productName", name, row.get("name"))
                .compare("quantity", quantity, row.get("quantity"))
                .compare("productUnits", units, row.get("units"))
                .compare("productPrice", price, row.get("price"))
                .compare("productSum", sum, row.get("totalSum"))
                .compare("vatSum", vatSum, row.get("vatSum"));
    }

    @Override
    public String getObjectLocator() {
        return name;
    }

    public void quantityType(String value) {
        WebElement quantityWebElement = getElement().findElement(By.xpath(".//*[@data-name='quantity']"));
        quantityWebElement.clear();
        quantityWebElement.sendKeys(value);
    }

    public void priceType(String value) {
        WebElement quantityWebElement = getElement().findElement(By.xpath(".//*[@data-name='priceEntered']"));
        quantityWebElement.clear();
        quantityWebElement.sendKeys(value);
    }

    public String getQuantity() {
        return quantity;
    }

    public String getPrice() {
        return price;
    }

    public void deleteIconClick() {
        getElement().findElement(By.xpath(".//*[@class='icon-remove-sign form_invoice__removeProductLink']")).click();
    }

    @Override
    public void click() {
        getElement().click();
    }
}
