package project.lighthouse.autotests.elements.Buttons;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonActions;

public class ButtonFacade {

    private String xpath = "//*[@class='button']";
    private String browserName;

    private static final String BUTTON_XPATH_PATTERN = "//*[contains(@class, 'button') and normalize-space(text())='%s']";

    private CommonActions commonActions;

    public ButtonFacade(WebDriver webDriver) {
        commonActions = new CommonActions(webDriver);
        browserName = commonActions.getCapabilities().getBrowserName();
    }

    public ButtonFacade(WebDriver driver, String buttonTextName) {
        this(driver);
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
