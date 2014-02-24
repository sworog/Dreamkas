package project.lighthouse.autotests.common;

import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.helper.FieldLengthChecker;

/**
 * Abstract class representing single page element type (input and etc)
 */
abstract public class CommonItem {

    protected By findBy;
    protected CommonPageObject pageObject;

    public CommonItem(CommonPageObject pageObject, By findBy) {
        this.pageObject = pageObject;
        this.findBy = findBy;
    }

    public CommonItem(CommonPageObject pageObject, String name) {
        this(pageObject, By.name(name));
    }

    public FieldLengthChecker getFieldLengthChecker() {
        return new FieldLengthChecker($());
    }

    public WebElement getWebElement() {
        return pageObject.findElement(findBy);
    }

    public WebElement getWebElement(WebElement parent) {
        return parent.findElement(findBy);
    }

    public WebElement getVisibleWebElement() {
        return pageObject.findVisibleElement(findBy);
    }

    public WebElement getOnlyVisibleWebElement() {
        return pageObject.findOnlyVisibleWebElementFromTheWebElementsList(findBy);
    }

    public By getFindBy() {
        return findBy;
    }

    abstract public void setValue(String value);

    public int length() {
        return $().getText().length();
    }

    public WebElementFacade $() {
        return pageObject.$(getWebElement());
    }

    public WebElementFacade getOnlyVisibleWebElementFacade() {
        return pageObject.$(getOnlyVisibleWebElement());
    }

    public WebElementFacade getVisibleWebElementFacade() {
        return pageObject.$(getVisibleWebElement());
    }

    public void click() {
        pageObject.getCommonActions().elementClick(findBy);
    }

    public void selectByValue(String value) {
        pageObject.getCommonActions().selectByValue(value, findBy);
    }

    public void selectByVisibleText(String label) {
        pageObject.getCommonActions().selectByVisibleText(label, findBy);
    }
}
