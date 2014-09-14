package project.lighthouse.autotests.steps.core;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.core.ErrorPage;

public class ErrorPageSteps extends ScenarioSteps {

    ErrorPage errorPage;

    @Step
    public void assertH1Text(String text) {
        errorPage.checkValue("h1", text);
    }

    @Step
    public void openUrl(String url) {
        errorPage.openUrl(url);
    }
}
