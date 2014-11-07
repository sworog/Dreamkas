package ru.dreamkas.jbehave;

import org.jbehave.core.annotations.*;
import ru.dreamkas.steps.CommonSteps;
import net.thucydides.core.annotations.Steps;

public class CommonUserSteps {

    @Steps
    CommonSteps commonSteps;

    @Given("пользователь ресетит все данные и перезапускает приложение")
    public void givenTheUserResetsDataAndRelaunchTheApp() {
        commonSteps.resetApp();
    }

    @When("пользователь закрывает приложение")
    public void whenTheUserClosesApp() {
        commonSteps.closeApp();
    }

    @When("пользователь открывает приложение")
    public void whenTheUserLaunchesApp() {
        commonSteps.launchApp();
    }

    @When("пользователь подтверждает выход и нажимает 'Да'")
    public void whenTheUserConfirmsDialog() {
        commonSteps.clickOnConfirmButton();
    }

    @Then("пользователь проверяет, что текущая активити это '$currentActivity'")
    public void thenTheUserAssertsCurrentActivity(String currentActivity) {
        commonSteps.assertCurrentActivity(currentActivity);
    }
}
