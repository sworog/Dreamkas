package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.errorPage.ErrorPage;

import static org.junit.Assert.fail;

public class ErrorPageSteps extends ScenarioSteps {

    ErrorPage errorPage;

    @Step
    public void error403IsPresent() {
        try {
            errorPage.getPageError403WebElement();
        } catch (Exception e) {
            fail("The error 403 is not present on the page!");
        }
    }

    @Step
    public void error404isPresent() {
        try {
            errorPage.getPageError404WebElement();
        } catch (Exception e) {
            fail("The error 404 is not present on the page!");
        }
    }

    @Step
    public void error403IsNotPresent() {
        try {
            //TODO check
            errorPage.getWaiter().invisibilityOfElementLocated(errorPage.getPageError403WebElement());
        } catch (Exception e) {
            fail("The error 403 is present on the page!");
        }
    }
}
