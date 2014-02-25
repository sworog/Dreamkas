package project.lighthouse.autotests.elements.Buttons.navigationBar;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonPageObject;

public class NavigationBarLink {

    private CommonPageObject pageObject;
    private String linkText;

    private static final String XPATH = "//*[@class='navigationBar__links']/a[text()='%s']";

    public NavigationBarLink(CommonPageObject pageObject, String linkText) {
        this.pageObject = pageObject;
        this.linkText = linkText;
    }

    public void click() {
        pageObject.getCommonActions().elementClick(By.xpath(getXpath()));
    }

    private String getXpath() {
        return String.format(XPATH, linkText);
    }
}
