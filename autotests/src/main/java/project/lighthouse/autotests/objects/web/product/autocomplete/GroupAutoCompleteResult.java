package project.lighthouse.autotests.objects.web.product.autocomplete;

import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.objects.web.abstractObjects.AbstractObject;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

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
