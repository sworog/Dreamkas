package project.lighthouse.autotests.jbehave.general;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.general.ModalSteps;

public class ModalEndUserSteps {

    @Steps
    ModalSteps modalSteps;

    @Given("пользователь находится в модальном окне '$modalPageObjectName'")
    public void givenUserSetsModalWindowPageObjectWithName(String modalPageObjectName) {
        modalSteps.setCurrentPageObject(modalPageObjectName);
    }

    @Then("пользователь проверяет, что заголовок модального окна равен '$title'")
    public void thenUserAssertsTheModalWindowTitle(String title) {
        modalSteps.assertTitle(title);
    }
}
