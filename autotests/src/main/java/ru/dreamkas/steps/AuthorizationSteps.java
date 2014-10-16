package ru.dreamkas.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.Cookie;
import org.openqa.selenium.TimeoutException;
import ru.dreamkas.common.item.interfaces.Findable;
import ru.dreamkas.elements.preLoader.PreLoader;
import ru.dreamkas.pages.MenuNavigationBar;
import ru.dreamkas.pages.authorization.AuthorizationPage;
import ru.dreamkas.pages.authorization.RestorePasswordPage;
import ru.dreamkas.pages.authorization.SignUpPage;
import ru.dreamkas.storage.DefaultStorage;

import java.util.HashMap;
import java.util.Map;

import static org.hamcrest.Matchers.notNullValue;
import static org.junit.Assert.assertThat;
import static org.junit.Assert.fail;

public class AuthorizationSteps extends ScenarioSteps {

    private Map<String, HashMap<String, String>> users = new HashMap<String, HashMap<String, String>>() {

        @Override
        public HashMap<String, String> get(Object key) {
            HashMap<String, String> get = super.get(key);
            String message = String.format("Users map don't contain value with the key '%s'", key);
            assertThat(message, get, notNullValue());
            return get;
        }

        {
            put("owner", new HashMap<String, String>() {{
                put("email", "owner@lighthouse.pro");
                put("password", "lighthouse");

            }});

        }
    };

    AuthorizationPage authorizationPage;
    MenuNavigationBar menuNavigationBar;
    SignUpPage signUpPage;
    RestorePasswordPage restorePasswordPage;

    @Step
    public void authorization(String user) {
        String email = users.get(user).get("email");
        String password = users.get(user).get("password");
        authorization(email, password);
    }

    @Step
    public void authorization(String email, String password) {
        authorization(email, password, false);
    }

    @Step
    public void authorization(String email, String password, Boolean isFalse) {
        authorizationPage.input("userName", email);
        authorizationPage.input("password", password);
        authorizationPage.clickOnCommonItemWihName("loginButton");
        if (!isFalse) {
            checkUser(email);
        }
        DefaultStorage.getUserVariableStorage().setIsAuthorized(true);
    }

    @Step
    public void loginButtonClick() {
        authorizationPage.clickOnCommonItemWihName("loginButton");
    }

    @Step
    public void beforeScenarioClearCookiesIfUserIsAuthorized() {
        if (DefaultStorage.getUserVariableStorage().getIsAuthorized()) {
            Cookie token = getDriver().manage().getCookieNamed("token");
            if (token != null) {
                getDriver().manage().deleteCookie(token);
            }
            Cookie posStoreId = getDriver().manage().getCookieNamed("posStoreId");
            if (posStoreId != null) {
                getDriver().manage().deleteCookie(posStoreId);
            }
        }
    }

    @Step
    public void checkUser(String email) {
        menuNavigationBar.checkValue(
                "The user name is '%s'. Should be '%s'.",
                "userName", email);
    }

    @Step
    public void openPage() {
        authorizationPage.open();
    }

    @Step
    public void loginFormIsPresent() {
        try {
            ((Findable) authorizationPage.getItems().get("form_login")).getVisibleWebElement();
        } catch (TimeoutException e) {
            fail("The log out is not successful!");
        }
    }

    @Step
    public void authorizationFalse(String userName, String password) {
        authorization(userName, password, true);
        DefaultStorage.getUserVariableStorage().setIsAuthorized(false);
    }

    @Step
    public void signUpButtonClick() {
        signUpPage.clickOnCommonItemWihName("signUpButton");
        new PreLoader(getDriver()).await();
    }

    @Step
    public void emailFieldInput(String emailValue) {
        signUpPage.input("email", emailValue);
    }

    @Step
    public void passwordFieldInput(String passwordValue) {
        authorizationPage.input("password", passwordValue);
    }

    @Step
    public void assertSignUpText() {
        authorizationPage.checkValue(
                "signUpPageTitle",
                "Ваша учетная запись успешно создана.\nДля входа введите пароль присланный вам на email.");
    }

    @Step
    public void assertRestorePasswordText(String text) {
        authorizationPage.checkValue("signUpPageTitle", text);
    }

    @Step
    public void assertValues(ExamplesTable examplesTable) {
        authorizationPage.checkValues(examplesTable);
    }

    @Step
    public void assertValue(String elementName, String value) {
        authorizationPage.checkValue(elementName, value);
    }

    @Step
    public void openSignUpPage() {
        signUpPage.open();
    }

    @Step
    public void forgotPasswordLinkClick() {
        authorizationPage.clickOnCommonItemWihName("forgotPasswordLink");
    }

    @Step
    public void recoverPasswordButtonClick() {
        restorePasswordPage.clickOnCommonItemWihName("recoverPasswordButton");
        new PreLoader(getDriver()).await();
    }

    @Step
    public void assertPageTitleText(String text) {
        restorePasswordPage.checkValue("pageTitleText", text);
    }

    @Step
    public void assertPageText(String text) {
        restorePasswordPage.checkValue("pageText", text);
    }

    @Step
    public void restorePasswordPageEmailInput(String value) {
        restorePasswordPage.input("email", value);
    }

    @Step
    public void restorePasswordPageOpen() {
        restorePasswordPage.open();
    }

    @Step
    public void authFieldInput(ExamplesTable examplesTable) {
        authorizationPage.input(examplesTable);
    }
}
