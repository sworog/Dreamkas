package project.lighthouse.autotests.elements.Buttons.localNavigation;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonPageObject;

/**
 * Facade to handle navigation link elements
 */
public class NavigationLinkFacade {

    private String xpath;
    private static final String xpathPattern = "//*[contains(@class, 'localNavigation__link') and normalize-space(text())='%s']";

    private CommonPageObject pageObject;

    public NavigationLinkFacade(CommonPageObject pageObject, String linkText) {
        this.pageObject = pageObject;
        xpath = String.format(xpathPattern, linkText);
    }

    public void click() {
        pageObject.getCommonActions().elementClick(By.xpath(xpath));
    }
}
