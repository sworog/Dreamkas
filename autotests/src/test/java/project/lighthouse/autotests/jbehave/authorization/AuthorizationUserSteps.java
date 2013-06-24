package project.lighthouse.autotests.jbehave.authorization;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.AfterScenario;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.AuthorizationSteps;

public class AuthorizationUserSteps {

    @Steps
    AuthorizationSteps authorizationSteps;

    @Given("After scenario failure logging out")
    @AfterScenario(uponOutcome = AfterScenario.Outcome.FAILURE)
    public void afterScenarioFailure() {
        authorizationSteps.afterScenarioFailure();
    }

    @Given("the user logs in as '$userName'")
    public void givenTheUSerLogsInAsUserName(String userName) {
        authorizationSteps.authorization(userName);
    }

    @When("the user logs out")
    public void whenTheUserLogsOut() {
        authorizationSteps.logOut();
    }
}
