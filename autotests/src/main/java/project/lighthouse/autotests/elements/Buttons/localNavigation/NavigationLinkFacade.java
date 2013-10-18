package project.lighthouse.autotests.elements.Buttons.localNavigation;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.CommonActions;

public class NavigationLinkFacade {

    private WebDriver driver;
    private String xpath;
    private static final String xpathPattern = "//*[@class='localNavigation__link' and normalize-space(text())='%s']";

    CommonActions commonActions;

    public NavigationLinkFacade(WebDriver driver, String linkText) {
        this.driver = driver;
        xpath = String.format(xpathPattern, linkText);
        commonActions = new CommonActions(driver);
    }

    public void click() {
        commonActions.elementClick(By.xpath(xpath));
    }
}
