package dreamkas.screenObjects;

public class Login extends ScreenObject {

    public void inputLoginCredentials(String email, String password) {
        getAppiumDriver().findElementById("ru.crystals.vaverjanov.dreamkas.debug:id/txtUsername").sendKeys(email);
        getAppiumDriver().findElementById("ru.crystals.vaverjanov.dreamkas.debug:id/txtPassword").sendKeys(password);
    }

    public void clickOnLoginButton() {
        getAppiumDriver().findElementById("ru.crystals.vaverjanov.dreamkas.debug:id/btnLogin").click();
    }
}
