package project.lighthouse.autotests.pages.common;

import net.thucydides.core.pages.PageObject;
import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;

abstract public class CommonItem {

    private WebElement element;
    protected By findBy;
    protected PageObject pageObject;

    public CommonItem(PageObject pageObject, By findBy) {
        this.pageObject = pageObject;
        this.findBy = findBy;
    }

    public CommonItem(PageObject pageObject, String name) {
        this(pageObject, By.name(name));
    }

    public WebElement getWebElement() {
        return getWebDriver().findElement(findBy);
    }

    public WebDriver getWebDriver() {
        return pageObject.getDriver();
    }

    abstract public void setValue(String value);

    public int length() {
        return $().getText().length();
    }

    public WebElementFacade $() {
        return pageObject.$(getWebElement());
    }
}
