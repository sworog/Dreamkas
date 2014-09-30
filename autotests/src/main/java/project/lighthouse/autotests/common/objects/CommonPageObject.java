package project.lighthouse.autotests.common.objects;

import net.thucydides.core.pages.PageObject;
import org.apache.commons.lang.NotImplementedException;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObjectCollection;
import project.lighthouse.autotests.common.CommonActions;
import project.lighthouse.autotests.common.Waiter;
import project.lighthouse.autotests.common.item.CommonItem;
import project.lighthouse.autotests.common.item.CommonItemMap;
import project.lighthouse.autotests.elements.items.NonType;

import java.util.Map;

/**
 * Page object facade for page object pages
 */
abstract public class CommonPageObject extends PageObject implements GeneralPageObject {

    /**
     * Map to store common items user can interact with
     */
    private CommonItemMap items = new CommonItemMap();

    private CommonActions commonActions = new CommonActions(this);

    public CommonPageObject(WebDriver driver) {
        super(driver);
        setDriver(driver, 100);
        createElements();
    }

    public CommonActions getCommonActions() {
        return commonActions;
    }

    public Waiter getWaiter() {
        return commonActions.getWaiter();
    }

    public CommonItemMap getItems() {
        return items;
    }

    /**
     * Put items to CommonItemMap {@link #items}
     */
    public void put(String elementName, CommonItem commonItem) {
        items.put(elementName, commonItem);
    }

    public void put(String elementName) {
        put(elementName, new NonType(this, elementName));
    }

    abstract public void createElements();

    public WebElement findElement(By by) {
        return getWaiter().getPresentWebElement(by);
    }

    public WebElement findVisibleElement(By by) {
        return getWaiter().getVisibleWebElement(by);
    }

    public WebElement findVisibleElement(WebElement element) {
        return getWaiter().getVisibleWebElement(element);
    }

    public WebElement findOnlyVisibleWebElementFromTheWebElementsList(By findBy) {
        return getWaiter().getOnlyVisibleElementFromTheList(findBy);
    }

    @Override
    public void input(String elementName, String value) {
        commonActions.input(elementName, value);
    }

    @Override
    public void input(ExamplesTable examplesTable) {
        for (Map<String, String> row : examplesTable.getRows()) {
            String elementName = row.get("elementName");
            String inputText = row.get("value");
            input(elementName, inputText);
        }
    }

    public void click(By findBy) {
        commonActions.elementClick(findBy);
    }

    public void itemClick(String itemName) {
        items.get(itemName).click();
    }

    public String getItemAttribute(String itemName, String attribute) {
        return items.get(itemName).getVisibleWebElement().getAttribute(attribute);
    }

    public void checkFieldLength(String elementName, int fieldLength) {
        items.get(elementName).getFieldChecker().assertFieldLength(elementName, fieldLength);
    }

    public void checkFieldLabel(String elementName) {
        items.get(elementName).getFieldChecker().assertLabelTitle();
    }

    @Override
    public void checkValue(String elementName, String value) {
        items.get(elementName).getFieldChecker().assertValueEqual(value);
    }

    public void checkValue(String message, String elementName, String value) {
        items.get(elementName).getFieldChecker().assertValueEqual(message, value);
    }

    @Override
    public void checkValues(ExamplesTable examplesTable) {
        for (Map<String, String> row : examplesTable.getRows()) {
            String elementName = row.get("elementName");
            String expectedValue = row.get("value");
            checkValue(elementName, expectedValue);
        }
    }

    @Override
    public void checkItemErrorMessage(String elementName, String errorMessage) {
        items.get(elementName).getFieldErrorMessageChecker().assertFieldErrorMessage(errorMessage);
    }

    public Boolean invisibilityOfElementLocated(WebElement element) {
        return getWaiter().invisibilityOfElementLocated(element);
    }

    public Boolean invisibilityOfElementLocated(By findBy) {
        return getWaiter().invisibilityOfElementLocated(findBy);
    }

    public Boolean visibilityOfElementLocated(WebElement element) {
        return getWaiter().visibilityOfElementLocated(element);
    }

    public Boolean visibilityOfElementLocated(By findBy) {
        return getWaiter().visibilityOfElementLocated(findBy);
    }

    @Override
    public void elementShouldBeVisible(String elementName) {
        getItems().get(elementName).shouldBeVisible();
    }

    @Override
    public void elementShouldBeNotVisible(String elementName) {
        getItems().get(elementName).shouldBeNotVisible();
    }

    public AbstractObjectCollection getObjectCollection() {
        throw new NotImplementedException();
    }

    @Override
    public void exactCompareExampleTable(ExamplesTable examplesTable) {
        getObjectCollection().exactCompareExampleTable(examplesTable);
    }

    @Override
    public void compareWithExampleTable(ExamplesTable examplesTable) {
        getObjectCollection().compareWithExampleTable(examplesTable);
    }

    @Override
    public void clickOnCollectionObjectByLocator(String locator) {
        getObjectCollection().clickByLocator(locator);
    }

    @Override
    public String getCommonItemAttributeValue(String commonItemName, String attribute) {
        return getItems().get(commonItemName).getVisibleWebElement().getAttribute(attribute);
    }

    @Override
    public void clickOnCommonItemWihName(String commonItemName) {
        getItems().get(commonItemName).click();
    }
}
