package project.lighthouse.autotests.objects.web.abstractObjects;

import junit.framework.Assert;
import junit.framework.AssertionFailedError;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.objects.web.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.objects.web.compare.CompareResults;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

abstract public class AbstractObjectCollection extends ArrayList<AbstractObject> {

    public AbstractObjectCollection(WebDriver webDriver, By findBy) {
        init(webDriver, findBy);
    }

    public void init(WebDriver webDriver, By findBy) {
        List<WebElement> webElementList = new Waiter(webDriver).getVisibleWebElements(findBy);
        for (WebElement element : webElementList) {
            AbstractObject abstractObject = createNode(element);
            add(abstractObject);
        }
    }

    abstract public AbstractObject createNode(WebElement element);

    public void compareWithExampleTable(ExamplesTable examplesTable) {
        Map<Map<String, String>, CompareResults> mapCompareResultsMap = new HashMap<>();
        for (Map<String, String> row : examplesTable.getRows()) {
            Boolean found = false;
            for (AbstractObject abstractObject : this) {
                ResultComparable resultComparable = (ResultComparable) abstractObject;
                if (resultComparable.getCompareResults(row).isEmpty()) {
                    this.remove(abstractObject);
                    found = true;
                    break;
                } else {
                    mapCompareResultsMap.put(row, resultComparable.getCompareResults(row));
                }
            }
            if (found) {
                mapCompareResultsMap.clear();
            }
        }
        if (!mapCompareResultsMap.isEmpty()) {
            StringBuilder builder = new StringBuilder("Not found rows: \n");
            for (Map.Entry<Map<String, String>, CompareResults> entry : mapCompareResultsMap.entrySet()) {
                String message = String.format("- row: '%s'\n%s", entry.getKey(), entry.getValue().getCompareRowStringResult());
                builder.append(message);
            }
            Assert.fail(builder.toString());
        }
    }

    public void clickByLocator(String locator) {
        ((ObjectClickable) getAbstractObjectByLocator(locator)).click();
    }

    public void clickPropertyByLocator(String locator, String propertyName) {
        getAbstractObjectByLocator(locator).getObjectProperty(propertyName).click();
    }

    public void inputPropertyByLocator(String locator, String propertyName, String value) {
        getAbstractObjectByLocator(locator).getObjectProperty(propertyName).input(value);
    }

    public void contains(String locator) {
        getAbstractObjectByLocator(locator);
    }

    private Boolean locateObject(AbstractObject abstractObject, String objectLocator) {
        if (abstractObject instanceof ObjectLocatable) {
            return ((ObjectLocatable) abstractObject).getObjectLocator().equals(objectLocator);
        } else {
            return false;
        }
    }

    public AbstractObject getAbstractObjectByLocator(String locator) {
        for (AbstractObject abstractObject : this) {
            if (locateObject(abstractObject, locator)) {
                return abstractObject;
            }
        }
        String errorMessage = String.format("There is no object with locator '%s'", locator);
        throw new AssertionFailedError(errorMessage);
    }
}
