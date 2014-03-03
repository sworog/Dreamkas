package project.lighthouse.autotests.elements.Buttons;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.interfaces.Disableable;

public class LinkFacade implements Disableable {

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

    @Override
    public Boolean isDisable() {
        // TODO implement
        return null;
    }
}
