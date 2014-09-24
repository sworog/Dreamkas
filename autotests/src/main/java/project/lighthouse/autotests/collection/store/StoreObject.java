package project.lighthouse.autotests.collection.store;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObject;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.collection.compare.CompareResults;

import java.util.Map;

public class StoreObject extends AbstractObject implements ObjectClickable, ObjectLocatable, ResultComparable {

    private String name;
    private String address;


    public StoreObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().findElement(By.name("name")).getText();
        address = getElement().findElement(By.name("address")).getText();
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
                .compare("address", address, row.get("address"));
    }
}
