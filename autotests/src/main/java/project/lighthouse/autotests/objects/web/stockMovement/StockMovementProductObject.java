package project.lighthouse.autotests.objects.web.stockMovement;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

public abstract class StockMovementProductObject extends AbstractObject implements ObjectClickable, ObjectLocatable, ResultComparable {

    protected String name;
    protected String price;
    protected String quantity;
    protected String totalPrice;

    public StockMovementProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("name")).getText();
        price = getElement().findElement(By.name("price")).getText();
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
                .compare("price", price, row.get("price"))
                .compare("quantity", quantity, row.get("quantity"))
                .compare("totalPrice", totalPrice, row.get("totalPrice"));
    }

    public abstract void clickDeleteIcon();

    protected void clickDeleteIcon(String cssClass) {
        String xpath = String.format(".//*[@class='%s btn fa fa-times']", cssClass);
        getElement().findElement(org.openqa.selenium.By.xpath(xpath)).click();
    }
}
