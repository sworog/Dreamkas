package dreamkas.steps;

import dreamkas.screenObjects.Login;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;

public class LoginSteps extends ScenarioSteps {

    Login login;

    @Step
    public void inputLoginCredentialsAndClickOnLoginButton(String email, String password) {
        login.inputLoginCredentials(email, password);
        login.clickOnLoginButton();
    }
}