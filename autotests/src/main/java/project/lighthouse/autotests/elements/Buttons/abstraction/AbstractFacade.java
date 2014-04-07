package project.lighthouse.autotests.elements.Buttons.abstraction;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.interfaces.Conditional;

import static org.junit.Assert.fail;

/**
 * Abstract facade to handle facade objects
 */
public abstract class AbstractFacade implements Conditional {

    private CommonPageObject pageObject;
    private By findBy;
    private String facadeText;

    public AbstractFacade(CommonPageObject pageObject, By customFindBy) {
        this.pageObject = pageObject;
        findBy = customFindBy;
    }

    public AbstractFacade(CommonPageObject pageObject, String facadeText) {
        this.pageObject = pageObject;
        findBy = By.xpath(getXpath(facadeText));
        this.facadeText = facadeText;
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

    private String getXpath(String facadeText) {
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
}
