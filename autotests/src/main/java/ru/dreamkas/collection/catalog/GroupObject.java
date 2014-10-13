package ru.dreamkas.collection.catalog;

import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectClickable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectLocatable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ResultComparable;
import ru.dreamkas.collection.compare.CompareResults;

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
