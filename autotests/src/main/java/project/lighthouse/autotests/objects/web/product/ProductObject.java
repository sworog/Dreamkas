package project.lighthouse.autotests.objects.web.product;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.Map;

/**
 * Product object
 */
public class ProductObject extends AbstractObject implements ObjectLocatable, ObjectClickable, ResultComparable {

    private String name;
    private String sellingPrice;
    private String barcode;

    public ProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("name")).getText();
        sellingPrice = getElement().findElement(By.name("sellingPrice")).getText();
        barcode = getElement().findElement(By.name("barcode")).getText();
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
                .compare("sellingPrice", sellingPrice, row.get("sellingPrice"))
                .compare("barcode", barcode, row.get("barcode"));
    }
}
