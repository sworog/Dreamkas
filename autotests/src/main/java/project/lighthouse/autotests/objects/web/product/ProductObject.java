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
 * Object to store product list object data
 */
public class ProductObject extends AbstractObject implements ObjectLocatable, ObjectClickable, ResultComparable {

    private String name;
    private String vendor;
    private String vendorCountry;
    private String purchasePrice;

    public ProductObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("name")).getText();
        vendor = getElement().findElement(By.name("vendor")).getText();
        vendorCountry = getElement().findElement(By.name("vendorCountry")).getText();
        purchasePrice = getElement().findElement(By.name("purchasePrice")).getText();
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
                .compare("vendor", vendor, row.get("vendor"))
                .compare("vendorCountry", vendorCountry, row.get("vendorCountry"))
                .compare("purchasePrice", purchasePrice, row.get("purchasePrice"));
    }
}
