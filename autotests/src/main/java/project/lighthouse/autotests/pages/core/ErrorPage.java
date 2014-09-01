package project.lighthouse.autotests.pages.core;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.helper.UrlHelper;

public class ErrorPage extends CommonPageObject {

    @FindBy(tagName = "h1")
    WebElement h1WebElement;

    public ErrorPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public String getH1Text() {
        return findVisibleElement(h1WebElement).getText();
    }

    @Step
    public void openUrl(String url) {
        getDriver().navigate().to(UrlHelper.getWebFrontUrl() + url);
    }
}
