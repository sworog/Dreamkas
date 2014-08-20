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

    @When("пользователь вводит данные в поля $exampleTable")
    public void fieldInput(ExamplesTable exampleTable) {
        generalSteps.input(exampleTable);
    }

    @When("пользователь вводит значение '$value' в поле с именем '$elementName'")
    public void whenTheUserInputsValueInTheFieldWithName(String value, String elementName) {
        generalSteps.input(elementName, value);
    }

    @Then("пользователь проверяет, что поле с именем '$elementName' имеет значение '$value'")
    public void thenTheCommonableUserCheckValue(String elementName, String value) {
        generalSteps.checkValue(elementName, value);
    }

    @Then("пользователь проверяет поля $exampleTable")
    public void thenTheUserCheckFieldValues(ExamplesTable examplesTable) {
        generalSteps.checkValues(examplesTable);
    }

    @Then("пользователь проверяет, что у поля с именем '$elementName' имеется сообщения об ошибке с сообщением '$errorMessage'")
    public void thenTheUserChecksTheFieldWithNameHasErrorMessageWithText(String elementName, String errorMessage) {
        generalSteps.checkItemErrorMessage(elementName, errorMessage);
    }
}
