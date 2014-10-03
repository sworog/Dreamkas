package dreamkas.jbehave;

import dreamkas.steps.CommonSteps;
import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.BeforeScenario;
import org.jbehave.core.annotations.Then;

public class CommonUserSteps {

    @Steps
    CommonSteps commonSteps;

    @Then("пользователь проверяет, что текущая активити это '$currentActivity'")
    public void thenTheUserAssertsCurrentActivity(String currentActivity) {
        commonSteps.assertCurrentActivity(currentActivity);
    }

    @BeforeScenario
    public void resetApp() {
        commonSteps.resetApp();
    }
}
