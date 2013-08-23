package project.lighthouse.autotests.jbehave.authorization;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.*;
import project.lighthouse.autotests.steps.AuthorizationSteps;

public class AuthorizationUserSteps {

    @Steps
    AuthorizationSteps authorizationSteps;

    @BeforeScenario()
    public void beforeScenario() {
        authorizationSteps.beforeScenario();
    }

    @BeforeScenario(uponType = ScenarioType.EXAMPLE)
    public void beforeExample() {
        beforeScenario();
    }

    @Given("the user logs in as '$userName'")
    @Alias("the user logs in as <userName>")
    public void givenTheUSerLogsInAsUserName(String userName) {
        authorizationSteps.authorization(userName);
    }

    @Given("the user opens the authorization page")
    public void givenTheUserOpensAuthorizationPage() {
        authorizationSteps.openPage();
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
        authorizationSteps.logOut();
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

    @Then("the user sees the 403 error")
    public void thenTheUserSeesThe403Error() {
        authorizationSteps.error403IsPresent();
    }

    @Then("the user dont see the 403 error")
    public void thenTheUserDonseeSeeThe403Error() {
        authorizationSteps.error403IsNotPresent();
    }

    @Then("the user sees no edit product button")
    public void thenTheUserSeesNoEditProductButton() {
        authorizationSteps.editProductButtonIsNotPresent();
    }

    @Then("the user sees no create new product button")
    public void thenTheUserSeesNoCreateNewProductButton() {
        authorizationSteps.newProductCreateButtonIsNotPresent();
    }

    @Then("the user sees user card edit button")
    public void thenTheUserSeesUserCardEditButton() {
        authorizationSteps.userCardEditButtonIsPresent();
    }

    @Then("the user sees no user card edit button")
    public void thenTheUserSeesNoUserCardEditButton() {
        authorizationSteps.userCardEditButtonIsNotPresent();
    }

    @Then("the user sees user card link to users list")
    public void thenTheUserSeesUserCardLinkToUsersList() {
        authorizationSteps.userCardListLinkIsPresent();
    }

    @Then("the user sees no user card link to users list")
    public void thenTheUserSeesNoUserCardLinkToUsersList() {
        authorizationSteps.userCardListLinkIsNotPresent();
    }
}
