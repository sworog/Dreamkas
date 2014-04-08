package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import org.openqa.selenium.Keys;
import project.lighthouse.autotests.steps.KeyPressSteps;

public class KeyPressUserSteps {

    @Steps
    KeyPressSteps keyPressSteps;

    @When("the user presses '$keys' key button")
    public void whenTheUserPressesKeyButton(Keys keys) {
        keyPressSteps.keyPress(keys);
    }
}
