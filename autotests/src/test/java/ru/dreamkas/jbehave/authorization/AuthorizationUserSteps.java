package ru.dreamkas.jbehave.authorization;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.*;
import org.jbehave.core.model.ExamplesTable;
import ru.dreamkas.steps.AuthorizationSteps;
import ru.dreamkas.steps.deprecated.email.EmailSteps;
import ru.dreamkas.steps.menu.MenuNavigationSteps;
import ru.dreamkas.storage.DefaultStorage;

public class AuthorizationUserSteps {

    @Steps
    AuthorizationSteps authorizationSteps;

    @Steps
    MenuNavigationSteps menuNavigationSteps;

    @Steps
    EmailSteps emailSteps;

    @BeforeScenario()
    public void beforeScenario() {
        authorizationSteps.beforeScenarioClearCookiesIfUserIsAuthorized();
    }

    @BeforeScenario(uponType = ScenarioType.EXAMPLE)
    public void beforeExample() {
        beforeScenario();
    }

    @Given("the user logs in as '$user'")
    public void givenTheUSerLogsInAsUser(String user) {
        authorizationSteps.authorization(user);
    }

    @Given("the user opens the authorization page")
    @Alias("пользователь открывает стартовую страницу авторизации")
    public void givenTheUserOpensAuthorizationPage() {
        authorizationSteps.openPage();
    }

    @Given("the user opens lighthouse sign up page")
    public void givenTheUserOpensLighthouseSignUpPage() {
        authorizationSteps.openSignUpPage();
    }

    @Given("the user logs in using '$userName' userName and '$password' password")
    @Alias("пользователь авторизуется в системе используя адрес электронной почты '$userName' и пароль '$password'")
    public void givenTheUserLogsInUsingCredentials(String userName, String password) {
        authorizationSteps.authorization(userName, password);
    }

    @Given("the user logs in using generated email and common password")
    public void givenTheUserLogsInUsingGeneratedEmailAndPassword() {
        String email = DefaultStorage.getCustomVariableStorage().getEmail();
        authorizationSteps.authorization(email, "lighthouse");
    }

    @Given("the user opens lighthouse restore password page")
    public void givenTheUserOpensRestorePasswordPage() {
        authorizationSteps.restorePasswordPageOpen();
    }

    @When("the user logs in using '$userName' userName and '$password' password")
    @Alias("the user logs in using <userName> and '$password' password")
    public void givenTheUserLogsInUsingUserNameAndPassword(String userName, String password) {
        authorizationSteps.authorization(userName, password);
    }

    @When("the user logs in using '$userName' userName and '$password' password to check validation")
    public void givenTheUserLogsInUsingUserNameAndPasswordToCheckValidation(String userName, String password) {
        authorizationSteps.authorizationFalse(userName, password);
    }

    @When("the user logs out")
    public void whenTheUserLogsOut() {
        menuNavigationSteps.userNameLinkClick();
        menuNavigationSteps.logOutButtonClick();
        DefaultStorage.getUserVariableStorage().setIsAuthorized(false);
    }

    @Then("the user checks that authorized is '$userName' user")
    @Alias("the user checks that authorized is <userName> user")
    public void thenTheUserChecksThatAuthorizedIsUser(String userName) {
        authorizationSteps.checkUser(userName);
    }

    @Then("the user checks the login form is present")
    public void thenTheUserChecksTheLoginFormIsPresent() {
        authorizationSteps.loginFormIsPresent();
    }

    @When("the user clicks on sign up button")
    public void whenTheUserClicksOnTheSignUpButton() {
        authorizationSteps.signUpButtonClick();
    }

    @When("the user inputs '$value' value in email field")
    @Alias("the user inputs value in email field")
    public void whenTheUserInputsValueInEmailField(String value) {
        authorizationSteps.emailFieldInput(value);
    }

    @When("the user inputs stored password from email in password field")
    public void whenTheUserInputsStoredPasswordFromEmailInPasswordField() {
        String password = emailSteps.getSignUpEmailCredentials();
        authorizationSteps.passwordFieldInput(password);
    }

    @When("the user inputs stored password from restore password email in password field")
    public void whenTheUserInputsStoredPasswordFromRestorePasswordEmailInPasswordField() {
        String password = emailSteps.getRestorePasswordEmailCredentials();
        authorizationSteps.passwordFieldInput(password);
    }

    @When("the user clicks on sign in button and logs in")
    public void whenTheUserClicksOnSignInButtonAndLogsIn() {
        authorizationSteps.loginButtonClick();
    }

    @When("the user clicks on forgot password link")
    public void whenTheUserClicksOnForgotPasswordLink() {
        authorizationSteps.forgotPasswordLinkClick();
    }

    @When("the user clicks on restore password button")
    public void whenTheUserClicksOnRestorePasswordButton() {
        authorizationSteps.recoverPasswordButtonClick();
    }

    @When("the user inputs '$value' value in restore password email field")
    public void whenTheUserInputsValueInRestorePasswordEmailField(String value) {
        authorizationSteps.restorePasswordPageEmailInput(value);
    }

    @When("the user inputs values on login page $examplesTable")
    public void whenTheUserInputsValuesOnLoginPage(ExamplesTable examplesTable) {
        authorizationSteps.authFieldInput(examplesTable);
    }

    @Then("the user checks the sign up text is expected")
    public void thenTheUserChecksTheSignUpText() {
        authorizationSteps.assertSignUpText();
    }

    @Then("the user checks the restore password text is '$expected'")
    public void thenTheUserChecksTheRestorePasswordText(String expected) {
        authorizationSteps.assertRestorePasswordText(expected);
    }

    @Then("the user asserts the elements have values on auth page $examplesTable")
    public void thenTheUserAssertsTheElementsHaveValuesOnAuthPage(ExamplesTable examplesTable) {
        authorizationSteps.assertValues(examplesTable);
    }

    @Then("the user asserts the element '$elementName' value is equal to value")
    public void thenTheUserAssertsTheElementsHaveValuesOnAuthPage(String elementName, String value) {
        authorizationSteps.assertValue(elementName, value);
    }

    @Then("the user asserts the restore password page title text is '$text'")
    public void thenTheUserAssertsTheRestorePasswordPageTitle(String text) {
        authorizationSteps.assertPageTitleText(text);
    }

    @Then("the user asserts the restore password page text is '$text'")
    public void thenTheUserAssertsTheRestorePasswordPageText(String text) {
        authorizationSteps.assertPageText(text);
    }
}
