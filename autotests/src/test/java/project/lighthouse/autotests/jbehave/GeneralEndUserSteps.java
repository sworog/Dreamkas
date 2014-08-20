package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.GeneralSteps;

public class GeneralEndUserSteps {

    @Steps
    GeneralSteps generalSteps;

    @Given("пользователь использует пейдж обжект с именем '$pageObjectName'")
    public void givenTheUserSetsPageObjectWihName(String pageObjectName) {
        generalSteps.setCurrentPageObject(pageObjectName);
    }

    @Then("общий пользователь проверяет, что поле с именем '$elementName' имеет значение '$value'")
    public void thenTheCommonableUserCheckValue(String elementName, String value) {
        generalSteps.checkValue(elementName, value);
    }

    @When("общий пользователь вводит данные в поля $exampleTable")
    public void fieldInput(ExamplesTable exampleTable) {
        generalSteps.fieldInput(exampleTable);
    }
}
