package project.lighthouse.autotests.elements.Buttons;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonActions;

public class ButtonFacade {

    private String xpath = "//*[@class='button']";
    private String browserName;

    private static final String BUTTON_XPATH_PATTERN = "//*[contains(@class, 'button') and normalize-space(text())='%s']";

    private CommonActions commonActions;

    public ButtonFacade(PageObject pageObject) {
        commonActions = new CommonActions(pageObject);
        browserName = commonActions.getCapabilities().getBrowserName();
    }

    public ButtonFacade(PageObject pageObject, String buttonTextName) {
        this(pageObject);
        this.xpath = getButtonXpath(buttonTextName);
    }

    private String getButtonXpath(String buttonTextName) {
        return String.format(BUTTON_XPATH_PATTERN, buttonTextName);
    }

    public void click() {
        if (browserName.equals("firefox") && commonActions.visibleWebElementHasTagName(xpath, "span")) {
            commonActions.elementSubmit(By.xpath(xpath));
        } else {
            commonActions.elementClick(By.xpath(xpath));
        }
    }

    public void catalogClick() {
        if (browserName.equals("firefox") && commonActions.webElementHasTagName(xpath, "span")) {
            commonActions.catalogElementSubmit(By.xpath(xpath));
        } else {
            commonActions.catalogElementClick(By.xpath(xpath));
        }
    }
}
