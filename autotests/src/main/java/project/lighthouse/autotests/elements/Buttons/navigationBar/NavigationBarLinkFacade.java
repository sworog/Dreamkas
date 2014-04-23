package project.lighthouse.autotests.elements.Buttons.navigationBar;

import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.abstraction.AbstractFacade;

/**
 * Facade to handle global navigation bar link items
 */
public class NavigationBarLinkFacade extends AbstractFacade {

    private static final String XPATH_PATTERN = "//a[@name='globalNavigationLink' and text()='%s']";

    public NavigationBarLinkFacade(CommonPageObject pageObject, String linkText) {
        super(pageObject, linkText);
    }

    @Override
    public String getXpathPattern() {
        return XPATH_PATTERN;
    }
}
