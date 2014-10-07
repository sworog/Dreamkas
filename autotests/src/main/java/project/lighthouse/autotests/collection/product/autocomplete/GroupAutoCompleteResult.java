package project.lighthouse.autotests.collection.product.autocomplete;

import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObject;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.collection.compare.CompareResults;

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
