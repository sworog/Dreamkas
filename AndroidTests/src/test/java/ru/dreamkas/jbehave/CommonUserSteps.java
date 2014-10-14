package ru.dreamkas.jbehave;

import ru.dreamkas.steps.CommonSteps;
import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.BeforeScenario;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;

public class CommonUserSteps {

    @Steps
    CommonSteps commonSteps;

    @BeforeScenario
    public void resetApp() {
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
