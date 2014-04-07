package project.lighthouse.autotests.steps;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.openqa.selenium.Keys;
import org.openqa.selenium.interactions.Actions;

public class KeyPressSteps extends ScenarioSteps {

    private Actions getActions() {
        return new Actions(getDriver());
    }

    @Step
    public void keyPress(Keys keys) {
        getActions().sendKeys(keys).perform();
    }
}
