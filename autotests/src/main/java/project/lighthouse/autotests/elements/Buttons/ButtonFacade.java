package project.lighthouse.autotests.elements.Buttons;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonPageObject;

public class ButtonFacade {

    private String xpath = "//*[@class='button']";
    private String browserName;

    private static final String BUTTON_XPATH_PATTERN = "//*[contains(@class, 'button') and normalize-space(text())='%s']";

    CommonPageObject pageObject;

    public ButtonFacade(CommonPageObject pageObject) {
        this.pageObject = pageObject;
        browserName = pageObject.getCommonActions().getCapabilities().getBrowserName();
    }

    public ButtonFacade(CommonPageObject pageObject, String buttonTextName) {
        this(pageObject);
        this.xpath = getButtonXpath(buttonTextName);
    }

    private String getButtonXpath(String buttonTextName) {
        return String.format(BUTTON_XPATH_PATTERN, buttonTextName);
    }

    public void click() {
        if (browserName.equals("firefox") && pageObject.getCommonActions().visibleWebElementHasTagName(xpath, "span")) {
            pageObject.getCommonActions().elementSubmit(By.xpath(xpath));
        } else {
            pageObject.getCommonActions().elementClick(By.xpath(xpath));
        }
    }

    public void catalogClick() {
        if (browserName.equals("firefox") && pageObject.getCommonActions().webElementHasTagName(xpath, "span")) {
            pageObject.getCommonActions().catalogElementSubmit(By.xpath(xpath));
        } else {
            pageObject.getCommonActions().catalogElementClick(By.xpath(xpath));
        }
    }
}
