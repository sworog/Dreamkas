package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.Cookie;
import org.openqa.selenium.TimeoutException;
import project.lighthouse.autotests.elements.preLoader.PreLoader;
import project.lighthouse.autotests.pages.MenuNavigationBar;
import project.lighthouse.autotests.pages.authorization.AuthorizationPage;
import project.lighthouse.autotests.pages.authorization.RestorePasswordPage;
import project.lighthouse.autotests.pages.authorization.SignUpPage;
import project.lighthouse.autotests.storage.Storage;

import java.util.HashMap;
import java.util.Map;

import static org.hamcrest.Matchers.equalTo;
import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;
import static org.junit.Assert.fail;

public class AuthorizationSteps extends ScenarioSteps {

    private Map<String, String> users = new HashMap<String, String>() {{
        put("watchman", "lighthouse");
        put("commercialManager", "lighthouse");
        put("storeManager", "lighthouse");
        put("departmentManager", "lighthouse");
    }};

    AuthorizationPage authorizationPage;
    MenuNavigationBar menuNavigationBar;
    SignUpPage signUpPage;
    RestorePasswordPage restorePasswordPage;

    @Step
    public void authorization(String userName) {
        String password = users.get(userName);
        authorization(userName, password);
    }

    @Step
    public void authorization(String userName, String password) {
        authorization(userName, password, false);
    }

    @Step
    public void authorization(String userName, String password, Boolean isFalse) {
        workAroundTypeForUserName(userName);
        authorizationPage.input("password", password);
        authorizationPage.loginButtonClick();
        if (!isFalse) {
            checkUser(userName);
        }
        Storage.getUserVariableStorage().setIsAuthorized(true);
    }

    @Step
    public void loginButtonClick() {
        authorizationPage.loginButtonClick();
    }

    // TODO fix this in future

    /**
     * This is actually bad type workaround for failing logging in.
     * For some reasons the type method is disturbed and the userName is not typed fully.
     *
     * @param inputText
     */
    @Step
    public void workAroundTypeForUserName(String inputText) {
        authorizationPage.input("userName", inputText);
        if (!authorizationPage.getItems().get("userName").getVisibleWebElementFacade().getValue().equals(inputText)) {
            workAroundTypeForUserName(inputText);
        }
    }

    @Step
    public void beforeScenario() {
        if (Storage.getUserVariableStorage().getIsAuthorized()) {
            Cookie token = getDriver().manage().getCookieNamed("token");
            if (token != null) {
                getDriver().manage().deleteCookie(token);
            }
        }
    }

    @Step
    public void checkUser(String userName) {
        String actualUserName = menuNavigationBar.getUserNameText();
        assertThat(
                String.format("The user name is '%s'. Should be '%s'.", actualUserName, userName),
                actualUserName,
                equalTo(userName));
    }

    @Step
    public void openPage() {
        authorizationPage.open();
    }

    @Step
    public void loginFormIsPresent() {
        try {
            authorizationPage.getLoginFormWebElement();
        } catch (TimeoutException e) {
            fail("The log out is not successful!");
        }
    }

    @Step
    public void authorizationFalse(String userName, String password) {
        authorization(userName, password, true);
        Storage.getUserVariableStorage().setIsAuthorized(false);
    }

    @Step
    public void signUpButtonClick() {
        signUpPage.signUpButtonClick();
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
        assertThat(
                authorizationPage.getSignUpPageTitleText(),
                is("Ваша учетная запись успешно создана.\nДля входа введите пароль присланный вам на email."));
    }

    @Step
    public void assertRestorePasswordText(String text) {
        assertThat(authorizationPage.getSignUpPageTitleText(), is(text));
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
        authorizationPage.forgotPasswordLinkClick();
    }

    @Step
    public void recoverPasswordButtonClick() {
        restorePasswordPage.recoverPasswordButtonClick();
        new PreLoader(getDriver()).await();
    }

    @Step
    public void assertPageTitleText(String text) {
        assertThat(restorePasswordPage.getPageTitleText(), is(text));
    }

    @Step
    public void assertPageText(String text) {
        assertThat(restorePasswordPage.getPageText(), is(text));
    }

    @Step
    public void restorePasswordPageEmailInput(String value) {
        restorePasswordPage.input("email", value);
    }

    @Step
    public void restorePasswordPageOpen() {
        restorePasswordPage.open();
    }
}
