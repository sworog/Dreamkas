package project.lighthouse.autotests.elements.Buttons.localNavigation;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonActions;

public class NavigationLinkFacade {

    private String xpath;
    private static final String xpathPattern = "//*[contains(@class, 'localNavigation__link') and normalize-space(text())='%s']";

    private CommonActions commonActions;

    public NavigationLinkFacade(PageObject pageObject, String linkText) {
        xpath = String.format(xpathPattern, linkText);
        commonActions = new CommonActions(pageObject);
    }

    public void click() {
        commonActions.elementClick(By.xpath(xpath));
    }
}
