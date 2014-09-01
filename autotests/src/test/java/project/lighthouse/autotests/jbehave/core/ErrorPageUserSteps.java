package project.lighthouse.autotests.jbehave.core;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.core.ErrorPageSteps;

public class ErrorPageUserSteps {

    @Steps
    ErrorPageSteps errorPageSteps;

    @Then("user checks h1 text is '$text'")
    public void userChecksH1Text(String text) {
        errorPageSteps.assertH1Text(text);
    }

    @Given("user opens url '$url'")
    @When("user opens url '$url'")
    public void whenUserOpensUrl(String url) {
        errorPageSteps.openUrl(url);
    }
}
