package project.lighthouse.autotests.elements.Buttons.localNavigation;

import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.abstraction.AbstractFacade;

/**
 * Facade to handle navigation link elements
 */
public class NavigationLinkFacade extends AbstractFacade {

    private static final String XPATH_PATTERN = "//*[contains(@class, 'localNavigation__link') and normalize-space(text())='%s']";

    public NavigationLinkFacade(CommonPageObject pageObject, String linkText) {
        super(pageObject, linkText);
    }

    @Override
    public String getXpathPattern() {
        return XPATH_PATTERN;
    }
}
