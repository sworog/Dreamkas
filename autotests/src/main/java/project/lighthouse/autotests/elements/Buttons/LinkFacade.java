package project.lighthouse.autotests.elements.Buttons;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.interfaces.Disableable;

public class LinkFacade implements Disableable {

    private CommonPageObject pageObject;
    private String linkText;
    private By findBy;

    public LinkFacade(CommonPageObject pageObject, String linkText) {
        this.pageObject = pageObject;
        this.linkText = linkText;
        setFindBy();
    }

    private void setFindBy() {
        String xpath = String.format("//a[normalize-space(text())='%s']", linkText);
        this.findBy = By.xpath(xpath);
    }

    public void click() {
        pageObject.getCommonActions().elementClick(findBy);
    }

    @Override
    public Boolean isDisabled() {
        return null != pageObject.findElement(findBy).getAttribute("disabled");
    }
}
