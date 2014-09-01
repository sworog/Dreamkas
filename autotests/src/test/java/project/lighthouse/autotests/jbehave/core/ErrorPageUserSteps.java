package project.lighthouse.autotests.jbehave.core;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.core.ErrorPageSteps;

public class ErrorPageUserSteps {

    @Steps
    ErrorPageSteps errorPageSteps;

    @Then("user checks h1 text is '$text'")
    public void userChecksH1Text(String text) {
        errorPageSteps.assertH1Text(text);
    }
}
