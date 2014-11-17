package ru.dreamkas.elements.Buttons.abstraction;

import net.thucydides.core.pages.WebElementFacade;
import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import ru.dreamkas.common.item.interfaces.Clickable;
import ru.dreamkas.common.item.interfaces.Findable;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.Buttons.interfaces.Conditional;
import ru.dreamkas.pages.modal.ModalWindowPage;

import static org.junit.Assert.fail;

/**
 * Abstract facade to handle facade objects
 */
public abstract class AbstractFacade implements Conditional, Clickable, Findable {

    private CommonPageObject pageObject;
    private By findBy;
    private String facadeText;

    public AbstractFacade(CommonPageObject pageObject, By customFindBy) {
        this.pageObject = pageObject;
        findBy = customFindBy;
    }

    public AbstractFacade(ModalWindowPage modalWindowPage, String facadeText) {
        this.pageObject = modalWindowPage;
        this.facadeText = facadeText;
        findBy = By.xpath(modalWindowPage.modalWindowXpath() + getXpathPatternWithFacadeText());

    }

    public AbstractFacade(CommonPageObject pageObject, String facadeText) {
        this.pageObject = pageObject;
        this.facadeText = facadeText;
        findBy = By.xpath(getXpathPatternWithFacadeText());
    }

    public CommonPageObject getPageObject() {
        return pageObject;
    }

    public By getFindBy() {
        return findBy;
    }

    public void click() {
        pageObject.getCommonActions().elementClick(findBy);
    }

    public abstract String getXpathPattern();

    protected String getXpathPatternWithFacadeText() {
        return String.format(getXpathPattern(), facadeText);
    }

    @Override
    public Boolean isVisible() {
        return pageObject.visibilityOfElementLocated(findBy);
    }

    @Override
    public Boolean isInvisible() {
        return pageObject.invisibilityOfElementLocated(findBy);
    }

    @Override
    public void shouldBeVisible() {
        if (!isVisible()) {
            String message = String.format("The items '%s' is not visible!", facadeText);
            fail(message);
        }
    }

    @Override
    public void shouldBeNotVisible() {
        if (!isInvisible()) {
            String message = String.format("The items '%s' is visible!", facadeText);
            fail(message);
        }
    }

    @Override
    public WebElement getVisibleWebElement() {
        return pageObject.findVisibleElement(findBy);
    }

    @Override
    public WebElementFacade getVisibleWebElementFacade() {
        return pageObject.$(getVisibleWebElement());
    }
}
