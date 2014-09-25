package project.lighthouse.autotests.collection.abstractObjects;

import junit.framework.Assert;
import junit.framework.AssertionFailedError;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.collection.compare.CompareResultHashMap;
import project.lighthouse.autotests.collection.compare.CompareResults;
import project.lighthouse.autotests.common.Waiter;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

abstract public class AbstractObjectCollection<E extends AbstractObject> extends ArrayList<E> {

    public AbstractObjectCollection(WebDriver webDriver, By findBy) {
        init(webDriver, findBy);
    }

    public void init(WebDriver webDriver, By findBy) {
        List<WebElement> webElementList = new Waiter(webDriver).getVisibleWebElements(findBy);
        for (WebElement element : webElementList) {
            E abstractObject = createNode(element);
            add(abstractObject);
        }
    }

    abstract public E createNode(WebElement element);

    public void exactCompareExampleTable(ExamplesTable examplesTable) {
        CompareResultHashMap compareResultHashMap = new CompareResultHashMap();

        Iterator<Map<String, String>> mapIterator = examplesTable.getRows().iterator();
        Iterator<E> abstractObjectIterator = this.iterator();

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

        if (abstractObjectIterator.hasNext()) {
            Assert.fail("The collection still contains not verified objects, but should not contain any");
        }

        compareResultHashMap.failIfHasAnyErrors();
    }

    public void compareWithExampleTable(ExamplesTable examplesTable) {
        CompareResultHashMap compareResultHashMap = new CompareResultHashMap();

        Iterator<Map<String, String>> mapIterator = examplesTable.getRows().iterator();

        while (mapIterator.hasNext()) {
            Map<String, String> row = mapIterator.next();
            Iterator<E> abstractObjectIterator = this.iterator();
            List<CompareResults> compareResultList = new ArrayList<>();

            while (abstractObjectIterator.hasNext()) {
                ResultComparable resultComparable = (ResultComparable) abstractObjectIterator.next();
                if (resultComparable.getCompareResults(row).isEmpty()) {
                    abstractObjectIterator.remove();
                    mapIterator.remove();
                    compareResultList.clear();
                    break;
                } else {
                    compareResultList.add(resultComparable.getCompareResults(row));
                }
            }
            if (!compareResultList.isEmpty()) {
                compareResultHashMap.put(
                        row,
                        getCompareResultsCollectionWithMinimumSize(compareResultList)
                );
            }
        }

        compareResultHashMap.failIfHasAnyErrors();
    }

    private CompareResults getCompareResultsCollectionWithMinimumSize(List<CompareResults> compareResultList) {
        CompareResults compareResultsWithMinSize = null;
        for (CompareResults compareResults : compareResultList) {
            if (compareResultsWithMinSize == null || compareResultsWithMinSize.size() > compareResults.size()) {
                compareResultsWithMinSize = compareResults;
            }
        }
        return compareResultsWithMinSize;
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

    private Boolean locateObject(E abstractObject, String objectLocator) {
        return ((ObjectLocatable) abstractObject).getObjectLocator().equals(objectLocator);
    }

    public E getAbstractObjectByLocator(String locator) {
        for (E abstractObject : this) {
            if (locateObject(abstractObject, locator)) {
                return abstractObject;
            }
        }
        String errorMessage = String.format("There is no object with locator '%s'", locator);
        throw new AssertionFailedError(errorMessage);
    }
}
