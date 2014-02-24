package project.lighthouse.autotests.common;

import net.thucydides.core.pages.PageObject;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.CommonActions;
import project.lighthouse.autotests.Waiter;

import java.util.HashMap;
import java.util.Map;

abstract public class CommonPageObject extends PageObject {

    protected CommonPage commonPage = new CommonPage(getDriver());

    protected Waiter waiter = new Waiter(getDriver());

    public Map<String, CommonItem> items = new HashMap<>();

    protected CommonActions commonActions = new CommonActions(getDriver(), items);

    public CommonPageObject(WebDriver driver) {
        super(driver);
        createElements();
    }

    abstract public void createElements();

    public WebElement findElement(By by) {
        return waiter.getPresentWebElement(by);
    }

    public WebElement findVisibleElement(By by) {
        return waiter.getVisibleWebElement(by);
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
        CommonItem item = items.get(elementName);
        commonPage.checkFieldLength(elementName, fieldLength, item.getWebElement());
    }

    public WebElement findOnlyVisibleWebElementFromTheWebElementsList(By findBy) {
        return waiter.getOnlyVisibleElementFromTheList(findBy);
    }

    public WebElement findModelFieldContaining(String modelName, String fieldName, String expectedValue) {
        By by = By.xpath(String.format("//span[@model='%s' and @model-attribute='%s' and contains(text(), '%s')]", modelName, fieldName, expectedValue));
        return findVisibleElement(by);
    }

    public void shouldContainsText(String elementName, String expectedValue) {
        WebElement element = items.get(elementName).getWebElement();
        commonPage.shouldContainsText(elementName, element, expectedValue);
    }
}
