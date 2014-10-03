package dreamkas.jbehave;

import dreamkas.steps.PosSteps;
import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;

public class PosUserSteps {

    @Steps
    PosSteps posSteps;

    @Then("пользователь проверяет, что заголовок '$expectedTitle'")
    public void thenTheUserChecksTheTitle(String expectedTitle) {
        posSteps.assertActionBarTitle(expectedTitle);
    }
}
