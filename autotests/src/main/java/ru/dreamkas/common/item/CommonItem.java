package ru.dreamkas.common.item;

import net.thucydides.core.pages.WebElementFacade;
import org.junit.Assert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import ru.dreamkas.common.item.interfaces.*;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.handler.field.FieldChecker;
import ru.dreamkas.handler.field.FieldErrorChecker;
import ru.dreamkas.pages.modal.ModalWindowPage;

/**
 * Abstract class representing single page element type (input and etc)
 */
abstract public class CommonItem
        implements Clickable, Settable, FieldCheckable, FieldErrorCheckable, Findable, Conditionable {

    private By findBy;
    private CommonPageObject pageObject;
    private String label;

    public CommonItem(CommonPageObject pageObject, By findBy) {
        this.pageObject = pageObject;
        this.findBy = findBy;
    }

    public CommonItem(ModalWindowPage modalWindowPage, String xpath) {
        this.pageObject = modalWindowPage;
        this.findBy = By.xpath(modalWindowPage.modalWindowXpath() + xpath);
    }

    public CommonItem(CommonPageObject pageObject, String name) {
        this(pageObject, By.name(name));
    }

    public CommonItem(CommonPageObject pageObject, String name, String label) {
        this(pageObject, By.name(name));
        this.label = label;
    }

    public FieldChecker getFieldChecker() {
        return new FieldChecker(this);
    }

    public FieldErrorChecker getFieldErrorMessageChecker() {
        return new FieldErrorChecker(this);
    }

    public String getLabel() {
        return label;
    }

    public CommonPageObject getPageObject() {
        return pageObject;
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

    public String getText() {
        return getVisibleWebElementFacade().getText();
    }

    public void shouldBeVisible() {
        if (!getPageObject().visibilityOfElementLocated(getFindBy())) {
            Assert.fail("the element should be visible");
        }
    }

    public void shouldBeNotVisible() {
        if (!getPageObject().invisibilityOfElementLocated(getFindBy())) {
            Assert.fail("the element should be not visible");
        }
    }
}
