package ru.dreamkas.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import ru.dreamkas.steps.GeneralSteps;

public class GeneralUserSteps {

    @Steps
    GeneralSteps generalSteps;

    @Given("пользователь* находится на '$pageObjectName'")
    @Alias("пользователь* находится в '$pageObjectName'")
    public void givenTheUserIsOnThePageObjectWithName(String pageObjectName) {
        generalSteps.setPageObject(pageObjectName);
    }

    @When("пользователь* нажимает на кнопку '$elementName'")
    public void whenTheUserClicksOnElementWithName(String elementName) {
        generalSteps.click(elementName);
    }

    @When("пользователь* вводит значение '$value' в поле с именем '$elementName'")
    public void whenTheUserSetsTheElementWithNameWithValue(String value, String elementName) {
        generalSteps.setValue(elementName, value);
    }

    @Then("пользователь* проверяет, что '$elementName' содержит текст '$text'")
    public void thenTheUserChecksTheElementText(String elementName, String text) {
        generalSteps.assertText(elementName, text);
    }
}
