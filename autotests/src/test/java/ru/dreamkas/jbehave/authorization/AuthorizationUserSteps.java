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
    @Alias("полльзователь открывает страницу регистации")
    public void givenTheUserOpensLighthouseSignUpPage() {
        authorizationSteps.openSignUpPage();
    }

    @Given("пользователь авторизуется в системе используя адрес электронной почты '$userName' и пароль '$password'")
    @When("пользователь авторизуется в системе используя адрес электронной почты '$userName' и пароль '$password'")
    public void givenTheUserLogsInUsingCredentials(String userName, String password) {
        authorizationSteps.authorization(userName, password);
    }

    @Given("the user logs in using generated email and common password")
    public void givenTheUserLogsInUsingGeneratedEmailAndPassword() {
        String email = DefaultStorage.getCustomVariableStorage().getEmail();
        authorizationSteps.authorization(email, "lighthouse");
    }

    @Given("the user opens lighthouse restore password page")
    @Alias("пользователь открывает страницу восстановления пароля")
    public void givenTheUserOpensRestorePasswordPage() {
        authorizationSteps.restorePasswordPageOpen();
    }

    @When("the user logs in using '$userName' userName and '$password' password")
    @Alias("the user logs in using <userName> and '$password' password")
    public void givenTheUserLogsInUsingUserNameAndPassword(String userName, String password) {
        authorizationSteps.authorization(userName, password);
    }

    @When("the user logs in using '$userName' userName and '$password' password to check validation")
    @Alias("пользователь авторизуется в системе используя адрес электронной почты '$userName' и пароль '$password' для проверки валидации")
    public void givenTheUserLogsInUsingUserNameAndPasswordToCheckValidation(String userName, String password) {
        authorizationSteps.authorizationFalse(userName, password);
    }

    @When("the user logs out")
    @Alias("пользователь разлогинивается")
    public void whenTheUserLogsOut() {
        menuNavigationSteps.logOutButtonClick();
        DefaultStorage.getUserVariableStorage().setIsAuthorized(false);
    }

    @Then("the user checks that authorized is '$userName' user")
    @Alias("пользователь видит что он авторизирован как '$userName'")
    public void thenTheUserChecksThatAuthorizedIsUser(String userName) {
        authorizationSteps.checkUser(userName);
    }

    @Then("the user checks the login form is present")
    public void thenTheUserChecksTheLoginFormIsPresent() {
        authorizationSteps.loginFormIsPresent();
    }

    @When("the user clicks on sign up button")
    @Alias("пользователь нажимает кнопку зарегистрироваться")
    public void whenTheUserClicksOnTheSignUpButton() {
        authorizationSteps.signUpButtonClick();
    }

    @When("the user inputs '$value' value in email field")
    @Alias("пользователь вводит '$value' в поле email")
    public void whenTheUserInputsValueInEmailField(String value) {
        authorizationSteps.emailFieldInput(value);
    }

    @When("the user inputs stored password from email in password field")
    @Alias("пользователь вводит пароль полученный из письма о регистации")
    public void whenTheUserInputsStoredPasswordFromEmailInPasswordField() {
        String password = emailSteps.getSignUpEmailCredentials();
        authorizationSteps.passwordFieldInput(password);
    }

    @When("the user inputs stored password from restore password email in password field")
    @Alias("пользователь вводит пароль полученный из письма о восстановлении пароля")
    public void whenTheUserInputsStoredPasswordFromRestorePasswordEmailInPasswordField() {
        String password = emailSteps.getRestorePasswordEmailCredentials();
        authorizationSteps.passwordFieldInput(password);
    }

    @When("the user clicks on sign in button and logs in")
    @Alias("пользовател жмёт кнопку авторизироваться и авторизируется")
    public void whenTheUserClicksOnSignInButtonAndLogsIn() {
        authorizationSteps.loginButtonClick();
    }

    @When("the user clicks on forgot password link")
    @Alias("пользователь нажимает на ссылку востановления пароля")
    public void whenTheUserClicksOnForgotPasswordLink() {
        authorizationSteps.forgotPasswordLinkClick();
    }

    @When("the user clicks on restore password button")
    @Alias("пользователь нажимает кнопку востановления пароля")
    public void whenTheUserClicksOnRestorePasswordButton() {
        authorizationSteps.recoverPasswordButtonClick();
    }

    @When("the user inputs '$value' value in restore password email field")
    @Alias("пользователь вводит '$value' в поле email на старанице восстановления пароля")
    public void whenTheUserInputsValueInRestorePasswordEmailField(String value) {
        authorizationSteps.restorePasswordPageEmailInput(value);
    }

    @When("the user inputs values on login page $examplesTable")
    public void whenTheUserInputsValuesOnLoginPage(ExamplesTable examplesTable) {
        authorizationSteps.authFieldInput(examplesTable);
    }

    @Then("the user checks the sign up text is expected")
    @Alias("пользователь видит текст что регистрация прошла успешно")
    public void thenTheUserChecksTheSignUpText() {
        authorizationSteps.assertSignUpText();
    }

    @Then("the user checks the restore password text is '$expected'")
    @Alias("пользователь видит текст над формой логина '$expected'")
    public void thenTheUserChecksTheRestorePasswordText(String expected) {
        authorizationSteps.assertRestorePasswordText(expected);
    }

    @Then("the user asserts the elements have values on auth page $examplesTable")
    @Alias("пользователь видит что в форме уже заполнены поля на странице авторизации $examplesTable")
    public void thenTheUserAssertsTheElementsHaveValuesOnAuthPage(ExamplesTable examplesTable) {
        authorizationSteps.assertValues(examplesTable);
    }

    @Then("the user asserts the element '$elementName' value is equal to value")
    public void thenTheUserAssertsTheElementsHaveValuesOnAuthPage(String elementName, String value) {
        authorizationSteps.assertValue(elementName, value);
    }

    @Then("the user asserts the restore password page title text is '$text'")
    @Alias("пользователь проверяет, что заголовок страницы восстановления пароля равен '$text'")
    public void thenTheUserAssertsTheRestorePasswordPageTitle(String text) {
        authorizationSteps.assertPageTitleText(text);
    }
}
