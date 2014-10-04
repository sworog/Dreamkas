package project.lighthouse.autotests.collection.abstractObjects;

import junit.framework.Assert;
import junit.framework.AssertionFailedError;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.*;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ObjectClickable;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ObjectLocatable;
import project.lighthouse.autotests.collection.abstractObjects.objectInterfaces.ResultComparable;
import project.lighthouse.autotests.collection.compare.CompareResultHashMap;
import project.lighthouse.autotests.collection.compare.CompareResults;
import project.lighthouse.autotests.common.Waiter;
import project.lighthouse.autotests.common.item.interfaces.Collectable;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

abstract public class AbstractObjectCollection<E extends AbstractObject> extends ArrayList<E> implements Collectable {

    private WebDriver webDriver;
    private By findBy;

    public AbstractObjectCollection(WebDriver webDriver, By findBy) {
        this.webDriver = webDriver;
        this.findBy = findBy;
    }

    protected List<WebElement> getWebElements(WebDriver webDriver, By findBy) {
        try {
            return new Waiter(webDriver).getVisibleWebElements(findBy);
        } catch (StaleElementReferenceException e) {
            return new Waiter(webDriver).getVisibleWebElements(findBy);
        } catch (TimeoutException e) {
            return new ArrayList<>();
        }
    }

    public void init(WebDriver webDriver, By findBy) {
        clear();
        List<WebElement> webElementList = getWebElements(webDriver, findBy);
        for (WebElement element : webElementList) {
            E abstractObject = createNode(element);
            add(abstractObject);
        }
    }

    abstract public E createNode(WebElement element);

    public void exactCompareExampleTable(ExamplesTable examplesTable) {
        init(webDriver, findBy);
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
        init(webDriver, findBy);
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
        init(webDriver, findBy);
        for (E abstractObject : this) {
            if (locateObject(abstractObject, locator)) {
                return abstractObject;
            }
        }
        String errorMessage = String.format("There is no object with locator '%s'", locator);
        throw new AssertionFailedError(errorMessage);
    }
}
