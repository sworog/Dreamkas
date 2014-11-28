package ru.dreamkas.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import ru.dreamkas.steps.CommonSteps;

public class CommonUserSteps {

    @Steps
    CommonSteps commonSteps;

    @Given("пользователь выполняет полный сброс данных устройства")
    public void givenTheUserExecuteAppReset() {
        commonSteps.resetApp();
    }
}
