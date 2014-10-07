package dreamkas.steps;

import dreamkas.pageObjects.LoginPage;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class LoginSteps extends ScenarioSteps {

    LoginPage loginPage;

    @Step
    public void inputLoginCredentials(String email, String password) {
        loginPage.inputLoginCredentials(email, password);
    }

    @Step
    public void clickOnLoginButton() {
        loginPage.clickOnLoginButton();
    }

    @Step
    public void assertDescription(String expectedDescription) {
        assertThat(loginPage.getDescription(), is(expectedDescription));
    }
}