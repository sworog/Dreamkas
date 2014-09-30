package dreamkas.screenObjects;

import net.thucydides.core.annotations.findby.FindBy;
import net.thucydides.core.pages.WebElementFacade;

public class Login extends ScreenObject {

    @FindBy(id = "ru.crystals.vaverjanov.dreamkas.debug:id/txtUsername")
    private WebElementFacade txtUsernameField;

    @FindBy(id = "ru.crystals.vaverjanov.dreamkas.debug:id/txtPassword")
    private WebElementFacade txtPasswordField;

    @FindBy(id = "ru.crystals.vaverjanov.dreamkas.debug:id/btnLogin")
    private WebElementFacade loginButton;

    public void inputLoginCredentials(String email, String password) {
        txtUsernameField.type(email);
        txtPasswordField.type(password);
    }

    public void clickOnLoginButton() {
        loginButton.click();
    }
}
