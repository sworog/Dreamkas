package ru.dreamkas.collection.product.autocomplete;

import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectClickable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ObjectLocatable;
import ru.dreamkas.collection.abstractObjects.objectInterfaces.ResultComparable;
import ru.dreamkas.collection.compare.CompareResults;

import java.util.Map;

/**
 * The object to store group autocomplete result
 */
public class GroupAutoCompleteResult extends AbstractObject implements ObjectClickable, ObjectLocatable, ResultComparable {

    private String result;

    public GroupAutoCompleteResult(WebElement element) {
        super(element);
    }

    @Override
    public void setProperties() {
        result = getElement().getText();
    }


    @Override
    public void click() {
        getElement().click();
    }

    @Override
    public String getObjectLocator() {
        return result;
    }

    @Override
    public CompareResults getCompareResults(Map<String, String> row) {
        return new CompareResults().compare("result", result, row.get("result"));
    }
}
