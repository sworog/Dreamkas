package ru.dreamkas.jbehave.deprecated;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import org.openqa.selenium.Keys;
import ru.dreamkas.steps.deprecated.KeyPressSteps;

public class KeyPressUserSteps {

    @Steps
    KeyPressSteps keyPressSteps;

    @When("the user presses '$keys' key button")
    public void whenTheUserPressesKeyButton(Keys keys) {
        keyPressSteps.keyPress(keys);
    }
}
