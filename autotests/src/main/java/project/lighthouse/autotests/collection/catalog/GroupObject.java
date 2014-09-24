package project.lighthouse.autotests.collection.catalog;

import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObject;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.collection.compare.CompareResults;

import java.util.Map;

/**
 * Group object item
 */
public class GroupObject extends AbstractObject implements ObjectClickable, ResultComparable, ObjectLocatable {

    private String name;

    public GroupObject(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        name = getElement().getText();
    }

    @Override
    public void click() {
        getElement().click();
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults()
                .compare("name", name, row.get("name"));
    }

    @Override
    public String getObjectLocator() {
        return name;
    }
}
