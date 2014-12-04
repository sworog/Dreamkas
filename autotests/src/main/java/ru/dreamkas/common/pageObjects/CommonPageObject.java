package ru.dreamkas.common.pageObjects;

import net.thucydides.core.pages.PageObject;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.common.CommonActions;
import ru.dreamkas.common.Waiter;
import ru.dreamkas.common.item.CommonItemMap;
import ru.dreamkas.common.item.interfaces.*;
import ru.dreamkas.elements.items.NonType;
import ru.dreamkas.exceptions.NotImplementedException;

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
    public void put(String elementName, CommonItemType commonItem) {
        items.put(elementName, commonItem);
    }

    public void put(String elementName) {
        put(elementName, new NonType(this, elementName));
    }

    public void putDefaultCollection(Collectable collectable) {
        put("defaultCollection", collectable);
    }

    public Collectable getDefaultCollection() {
        return ((Collectable)items.get("defaultCollection"));
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

    public void checkFieldLength(String elementName, int fieldLength) {
        ((FieldCheckable) items.get(elementName)).getFieldChecker().assertFieldLength(elementName, fieldLength);
    }

    public void checkFieldLabel(String elementName) {
        ((FieldCheckable) items.get(elementName)).getFieldChecker().assertLabelTitle();
    }

    @Override
    public void checkValue(String elementName, String value) {
        ((FieldCheckable) items.get(elementName)).getFieldChecker().assertValueEqual(value);
    }

    public void checkValue(String message, String elementName, String value) {
        ((FieldCheckable) items.get(elementName)).getFieldChecker().assertValueEqual(message, value);
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
        ((FieldErrorCheckable) items.get(elementName)).getFieldErrorMessageChecker().assertFieldErrorMessage(errorMessage);
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
        ((Conditionable) items.get(elementName)).shouldBeVisible();
    }

    @Override
    public void elementShouldBeNotVisible(String elementName) {
        ((Conditionable) items.get(elementName)).shouldBeNotVisible();
    }

    @Override
    public void exactCompareExampleTable(ExamplesTable examplesTable) {
        getDefaultCollection().exactCompareExampleTable(examplesTable);
    }

    @Override
    public void compareWithExampleTable(ExamplesTable examplesTable) {
        getDefaultCollection().compareWithExampleTable(examplesTable);
    }

    @Override
    public void clickOnCollectionObjectByLocator(String locator) {
        getDefaultCollection().clickByLocator(locator);
    }

    public void collectionContainObjectWithLocator(String locator) {
        getDefaultCollection().contains(locator);
    }

    public void collectionNotContainObjectWithLocator(String locator) {
        getDefaultCollection().notContains(locator);
    }

    @Override
    public String getCommonItemAttributeValue(String commonItemName, String attribute) {
        return ((Findable) items.get(commonItemName)).getVisibleWebElement().getAttribute(attribute);
    }

    @Override
    public String getCommonItemCssValue(String commonItemName, String cssValue) {
        return ((Findable) items.get(commonItemName)).getVisibleWebElement().getCssValue(cssValue);
    }

    @Override
    public void clickOnCommonItemWihName(String commonItemName) {
        ((Clickable) items.get(commonItemName)).click();
    }

    public void shouldContainsText(String textValue) {
        findVisibleElement(
                By.xpath(
                        String.format("//*[contains(normalize-space(text()), '%s')]", textValue)
                )
        );
    }

    @Override
    public void addObjectButtonClick() {
        throw new NotImplementedException();
    }
}
