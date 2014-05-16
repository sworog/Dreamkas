package project.lighthouse.autotests.elements.Buttons;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.abstraction.AbstractFacade;
import project.lighthouse.autotests.elements.Buttons.interfaces.Disableable;
import project.lighthouse.autotests.elements.Buttons.interfaces.Firefoxable;

/**
 * Facade to handle buttons interactions
 */
public class ButtonFacade extends AbstractFacade implements Disableable, Firefoxable {

    private static final String BUTTON_XPATH_PATTERN = "//*[contains(@class, 'button') and normalize-space(text())='%s']";

    public ButtonFacade(CommonPageObject pageObject) {
        super(pageObject, By.xpath("//*[@class='button']"));
    }

    public ButtonFacade(CommonPageObject pageObject, String buttonTextName) {
        super(pageObject, buttonTextName);
    }

    public void click() {
        if (isFirefox() && getPageObject().getCommonActions().visibleWebElementHasTagName(getFindBy(), "span")) {
            getPageObject().getCommonActions().elementSubmit(getFindBy());
        } else {
            getPageObject().getCommonActions().elementClick(getFindBy());
        }
    }

    public void catalogClick() {
        if (isFirefox() && getPageObject().getCommonActions().webElementHasTagName(getFindBy(), "span")) {
            getPageObject().getCommonActions().catalogElementSubmit(getFindBy());
        } else {
            getPageObject().getCommonActions().catalogElementClick(getFindBy());
        }
    }

    @Override
    public String getXpathPattern() {
        return BUTTON_XPATH_PATTERN;
    }

    @Override
    public Boolean isDisabled() {
        return null != getPageObject().findElement(getFindBy()).getAttribute("disabled");
    }

    @Override
    public Boolean isFirefox() {
        return getPageObject().getCommonActions().getCapabilities().getBrowserName().equals("firefox");
    }
}
