package project.lighthouse.autotests.pages.common;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.Waiter;

import java.util.HashMap;
import java.util.Map;

abstract public class CommonPageObject extends PageObject {

    protected CommonPage commonPage = new CommonPage(getDriver());

    protected Waiter waiter = new Waiter(getDriver());

    public Map<String, CommonItem> items = new HashMap();

    public CommonPageObject(WebDriver driver) {
        super(driver);
        createElements();
    }

    abstract public void createElements();

    public void input(String elementName, String inputText) {
        try {
            items.get(elementName).setValue(inputText);
        } catch (Exception e) {
            String errorMessage1 = "Element not found in the cache - perhaps the page has changed since it was looked up";
            String getCauseMessage = e.getCause().getMessage();
            if (getCauseMessage.contains(errorMessage1)) {
                input(elementName, inputText);
            } else {
                throw e;
            }
        }
    }

    public WebElement findElement(By by) {
        return waiter.getWebElement(by);
    }
}
