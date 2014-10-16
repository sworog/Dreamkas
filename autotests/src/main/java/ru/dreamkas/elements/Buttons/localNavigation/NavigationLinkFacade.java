package ru.dreamkas.elements.Buttons.localNavigation;

import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.Buttons.abstraction.AbstractFacade;

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
