package project.lighthouse.autotests.pages.common;

import net.thucydides.core.pages.PageObject;
import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;

abstract public class CommonItem {

    protected types type;
    protected WebElement element;
    protected By findBy;
    protected PageObject pageObject;

    public static enum types {input, textarea, select, dateTime, autocomplete, date, nonType}

    public CommonItem(PageObject pageObject, By findBy) {
        this.pageObject = pageObject;
        this.findBy = findBy;
        this.element = getWebDriver().findElement(findBy);
    }

    public CommonItem(PageObject pageObject, String name) {
        this(pageObject, By.name(name));
    }

    public types getType() {
        return type;
    }

    public WebElement getWebElement() {
        return element;
    }

    public WebDriver getWebDriver() {
        return pageObject.getDriver();
    }

    abstract public void setValue(String value);

    public int length() {
        return $().getText().length();
    }

    public WebElementFacade $() {
        return pageObject.$(element);
    }
}
