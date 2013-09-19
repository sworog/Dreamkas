package project.lighthouse.autotests.elements.Buttons;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.CommonActions;

public class ButtonFacade {

    WebDriver webDriver;
    String xpath = "//*[@class='button']";

    private static final String BUTTON_XPATH_PATTERN = "//*[contains(@class, 'button') and normalize-space(text())='%s']";

    CommonActions commonActions;

    public ButtonFacade(WebDriver webDriver) {
        this.webDriver = webDriver;
        commonActions = new CommonActions(webDriver);
    }

    public ButtonFacade(WebDriver driver, String buttonTextName) {
        this(driver);
        setXpath(
                getButtonXpath(buttonTextName));
    }

    private void setXpath(String xpath) {
        this.xpath = xpath;
    }

    private String getButtonXpath(String buttonTextName) {
        return String.format(BUTTON_XPATH_PATTERN, buttonTextName);
    }

    public void click() {
        String browserName = commonActions.getCapabilities().getBrowserName();
        if (browserName.equals("firefox") && commonActions.webElementHasTagName(xpath, "span")) {
            commonActions.elementClickByFirefox(By.xpath(xpath + "/input"));
        } else {
            commonActions.elementClick(By.xpath(xpath));
        }
    }
}
