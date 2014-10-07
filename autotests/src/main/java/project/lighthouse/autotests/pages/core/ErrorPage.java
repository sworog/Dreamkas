package project.lighthouse.autotests.pages.core;

import net.thucydides.core.annotations.Step;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.pageObjects.CommonPageObject;
import project.lighthouse.autotests.elements.items.NonType;
import project.lighthouse.autotests.helper.UrlHelper;

public class ErrorPage extends CommonPageObject {

    public ErrorPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("h1", new NonType(this, By.tagName("h1")));
    }

    @Step
    public void openUrl(String url) {
        getDriver().navigate().to(UrlHelper.getWebFrontUrl() + url);
    }
}
