package project.lighthouse.autotests.jbehave.authorization;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.AfterScenario;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.steps.AuthorizationSteps;

public class AuthorizationUserSteps {

    @Steps
    AuthorizationSteps authorizationSteps;

    @Given("the user logs out")
    @AfterScenario(uponOutcome = AfterScenario.Outcome.FAILURE)
    public void givenTheUserLogsOut() {
        authorizationSteps.logOut();
    }

    @Given("the user logs in as '$userName'")
    public void givenTheUSerLogsInAsUserName(String userName) {
        authorizationSteps.authorization(userName);
    }
}
