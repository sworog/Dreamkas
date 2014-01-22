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
import project.lighthouse.autotests.objects.web.compare.CompareResultHashMap;

import java.util.ArrayList;
import java.util.Iterator;
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

    public void exactCompareExampleTable(ExamplesTable examplesTable) {
        CompareResultHashMap compareResultHashMap = new CompareResultHashMap();

        Iterator<Map<String, String>> mapIterator = examplesTable.getRows().iterator();
        Iterator<AbstractObject> abstractObjectIterator = this.iterator();

        while (mapIterator.hasNext()) {
            Map<String, String> row = mapIterator.next();
            if (!abstractObjectIterator.hasNext()) {
                Assert.fail();
            }
            ResultComparable resultComparable = (ResultComparable) abstractObjectIterator.next();
            if (!resultComparable.getCompareResults(row).isEmpty()) {
                compareResultHashMap.put(row, resultComparable.getCompareResults(row));
            }
        }

        compareResultHashMap.failIfHasAnyErrors();
    }

    public void compareWithExampleTable(ExamplesTable examplesTable) {
        CompareResultHashMap compareResultHashMap = new CompareResultHashMap();

        for (Iterator<Map<String, String>> mapIterator = examplesTable.getRows().iterator(); mapIterator.hasNext(); ) {
            Map<String, String> row = mapIterator.next();

            for (Iterator<AbstractObject> abstractObjectIterator = this.iterator(); abstractObjectIterator.hasNext(); ) {
                ResultComparable resultComparable = (ResultComparable) abstractObjectIterator.next();
                if (resultComparable.getCompareResults(row).isEmpty()) {
                    abstractObjectIterator.remove();
                    mapIterator.remove();
                    break;
                } else {
                    compareResultHashMap.put(row, resultComparable.getCompareResults(row));
                }
            }
        }

        compareResultHashMap.failIfHasAnyErrors();
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

    public void notContains(String locator) {
        String errorMessage = String.format("There is the object with locator '%s'", locator);
        try {
            getAbstractObjectByLocator(locator);
            Assert.fail(errorMessage);
        } catch (AssertionFailedError e) {
            if (!e.getMessage().contains("There is no object with locator")) {
                Assert.fail(errorMessage);
            }
        }
    }

    private Boolean locateObject(AbstractObject abstractObject, String objectLocator) {
        return ((ObjectLocatable) abstractObject).getObjectLocator().equals(objectLocator);
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
