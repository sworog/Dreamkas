package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.authorization.AuthorizationPage;

public class AuthorizationSteps extends ScenarioSteps {

    AuthorizationPage authorizationPage;

    public AuthorizationSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void authorization(String userName) {
        authorizationPage.authorization(userName);
    }

    @Step
    public void authorization(String userName, String password) {
        authorizationPage.authorization(userName, password);
    }

    @Step
    public void logOut() {
        authorizationPage.logOut();
    }

    @Step
    public void afterScenarioFailure() {
        authorizationPage.afterScenarioFailure();
    }

    @Step
    public void checkUser(String userName) {
        authorizationPage.checkUser(userName);
    }

    @Step
    public void openPage() {
        authorizationPage.open();
    }
}
