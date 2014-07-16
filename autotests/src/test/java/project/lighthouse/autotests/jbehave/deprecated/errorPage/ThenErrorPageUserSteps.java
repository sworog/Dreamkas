package project.lighthouse.autotests.jbehave.deprecated.errorPage;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.deprecated.ErrorPageSteps;

public class ThenErrorPageUserSteps {

    @Steps
    ErrorPageSteps errorPageSteps;

    @Then("the user sees the 403 error")
    public void thenTheUserSeesThe403Error() {
        errorPageSteps.error403IsPresent();
    }

    @Then("the user sees the 404 error")
    public void thenTheUserSeesThe404Error() {
        errorPageSteps.error404isPresent();
    }

    @Then("the user dont see the 403 error")
    public void thenTheUserDonseeSeeThe403Error() {
        errorPageSteps.error403IsNotPresent();
    }
}
