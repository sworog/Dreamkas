package ru.dreamkas.jbehave;

import net.thucydides.core.annotations.Steps;

import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.When;

import ru.dreamkas.steps.GeneralSteps;

public class GeneralUserSteps {

    @Steps
    GeneralSteps generalSteps;

    @Given("пользователь* находится на экране '$pageObjectName'")
    public void givenTheUserIsOnTheScreenWithName(String pageObjectName) {
        generalSteps.setCurrentPageObject(pageObjectName);
    }

    @When("пользователь* нажимает на кнопку '$elementName'")
    public void whenTheUserClicksOnElementWithName(String elementName) {
        generalSteps.clickOnElement(elementName);
    }
}
