package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.junit.Assert;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.pages.errorPage.ErrorPage;

public class ErrorPageSteps extends ScenarioSteps {

    ErrorPage errorPage;

    @Step
    public void error403IsPresent() {
        WebElement error403WEbWebElement = errorPage.getPageError403WebElement();
        if (!errorPage.visibilityOfElementLocated(error403WEbWebElement)) {
            Assert.fail("The error 403 page is not present on the page!");
        }
    }

    @Step
    public void error404isPresent() {
        WebElement error404WebElement = errorPage.getPageError404WebElement();
        if (!errorPage.visibilityOfElementLocated(error404WebElement)) {
            Assert.fail("The error 404 page is not present on the page!");
        }
    }

    @Step
    public void error403IsNotPresent() {
        WebElement error403WebElement = errorPage.getPageError403WebElement();
        if (!errorPage.invisibilityOfElementLocated(error403WebElement)) {
            Assert.fail("The error 403 page is present on the page!");
        }
    }
}
