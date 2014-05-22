package project.lighthouse.autotests.common;

import junit.framework.Assert;
import net.thucydides.core.pages.PageObject;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;
import project.lighthouse.autotests.elements.items.NonType;

import java.util.Map;

/**
 * Page object facade for page object pages
 */
abstract public class CommonPageObject extends PageObject {

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
     * put items to CommonItemMap {@link #items}
     *
     * @param elementName
     * @param commonItem
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

    public void input(String elementName, String inputText) {
        commonActions.input(elementName, inputText);
    }

    public void check(String elementName, String expectedValue) {
        commonActions.checkElementText(elementName, expectedValue);
    }

    public void checkCardValue(String checkType, String elementName, String expectedValue) {
        commonActions.checkElementValue(checkType, elementName, expectedValue);
    }

    public void checkCardValue(String elementName, String expectedValue) {
        commonActions.checkElementValue("", elementName, expectedValue);
    }

    public void checkCardValue(String checkType, ExamplesTable checkValuesTable) {
        commonActions.checkElementValue(checkType, checkValuesTable);
    }

    public void checkCardValue(ExamplesTable checkValuesTable) {
        commonActions.checkElementValue("", checkValuesTable);
    }

    public void fieldInput(ExamplesTable fieldInputTable) {
        for (Map<String, String> row : fieldInputTable.getRows()) {
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

    public void inputTable(ExamplesTable inputTable) {
        commonActions.inputTable(inputTable);
    }

    public void selectByValue(String elementName, String value) {
        items.get(elementName).setValue(value);
    }

    public void checkFieldLength(String elementName, int fieldLength) {
        items.get(elementName).getFieldChecker().assertFieldLength(elementName, fieldLength);
    }

    public void checkFieldLabel(String elementName) {
        items.get(elementName).getFieldChecker().assertLabelTitle();
    }

    public WebElement findOnlyVisibleWebElementFromTheWebElementsList(By findBy) {
        return getWaiter().getOnlyVisibleElementFromTheList(findBy);
    }

    public WebElement findModelFieldContaining(String modelName, String fieldName, String expectedValue) {
        By by = By.xpath(String.format("//span[@model='%s' and @model-attribute='%s' and contains(text(), '%s')]", modelName, fieldName, expectedValue));
        return findVisibleElement(by);
    }

    public void shouldContainsText(String elementName, String expectedValue) {
        WebElement element = items.get(elementName).getWebElement();
        commonActions.shouldContainsText(elementName, element, expectedValue);
    }

    public void elementShouldBeVisible(String value, CommonView commonView) {
        commonActions.elementShouldBeVisible(value, commonView);
    }

    public void checkValue(String elementName, String value) {
        items.get(elementName).getFieldChecker().assertValueEqual(value);
    }

    public void checkValues(ExamplesTable examplesTable) {
        for (Map<String, String> row : examplesTable.getRows()) {
            String elementName = row.get("elementName");
            String expectedValue = row.get("value");
            checkValue(elementName, expectedValue);
        }
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

    public void elementShouldBeNotVisible(String elementName) {
        CommonItem commonItem = getItems().get(elementName);
        if (!invisibilityOfElementLocated(commonItem.getFindBy())) {
            String message = String.format("Element with name '%s' is visible, but shouldnt not be visible", elementName);
            Assert.fail(message);
        }
    }
}
