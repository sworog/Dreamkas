package project.lighthouse.autotests.elements.Buttons;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonPageObject;

public class LinkFacade {

    private CommonPageObject pageObject;
    private String linkText;

    public LinkFacade(CommonPageObject pageObject, String linkText) {
        this.pageObject = pageObject;
        this.linkText = linkText;
    }

    public void click() {
        String xpath = String.format("//a[normalize-space(text())='%s']", linkText);
        pageObject.getCommonActions().elementClick(By.xpath(xpath));
    }
}
