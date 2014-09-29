package dreamkas.jbehave;

import dreamkas.steps.LoginSteps;
import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;

public class LoginUserSteps {

    @Steps
    LoginSteps loginSteps;

    @Given("пользователь авторизируется в системе используя адрес электронной почты '$email' и пароль '$password'")
    public void givenUserAuthorizeInTheSystem(String email, String password) {
        loginSteps.inputLoginCredentialsAndClickOnLoginButton(email, password);
    }
}
