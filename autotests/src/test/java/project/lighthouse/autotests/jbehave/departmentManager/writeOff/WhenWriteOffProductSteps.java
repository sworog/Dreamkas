package project.lighthouse.autotests.jbehave.departmentManager.writeOff;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.departmentManager.WriteOffSteps;

public class WhenWriteOffProductSteps {

    @Steps
    WriteOffSteps writeOffSteps;

    @When("the user clicks on property named '$propertyName' of writeOff product named '$locator'")
    public void propertyClick(String locator, String propertyName) {
        writeOffSteps.clickPropertyByLocator(locator, propertyName);
    }

    @When("the user inputs the value '$value' in property named '$propertyName' of writeOff product named '$locator'")
    public void propertyInput(String locator, String propertyName, String value) {
        writeOffSteps.inputPropertyByLocator(locator, propertyName, value);
    }
}
