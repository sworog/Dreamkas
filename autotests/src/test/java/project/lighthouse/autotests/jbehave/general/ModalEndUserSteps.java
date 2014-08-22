package project.lighthouse.autotests.jbehave.general;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.general.ModalSteps;

public class ModalEndUserSteps {

    @Steps
    ModalSteps modalSteps;

    @Given("пользователь* находится в модальном окне '$modalPageObjectName'")
    @When("пользователь* находится в модальном окне '$modalPageObjectName'")
    public void givenUserSetsModalWindowPageObjectWithName(String modalPageObjectName) {
        modalSteps.setCurrentPageObject(modalPageObjectName);
    }

    @When("пользователь* в модальном окне вводит данные $examplesTable")
    public void whenTheUserInputsOnTheEditWriteOffModalWindow(ExamplesTable examplesTable) {
        modalSteps.input(examplesTable);
    }

    @When("пользователь* в модальном окне вводит значение '$value' в поле с именем '$elementName'")
    public void whenUserInputsValueInFieldWithName(String value, String elementName) {
        modalSteps.input(elementName, value);
    }

    @Then("пользователь* в модальном окне проверяет, что поле с именем '$elementName' имеет значение '$value'")
    public void thenUserChecksValue(String elementName, String value) {
        modalSteps.checkValue(elementName, value);
    }

    @Then("пользователь* в модальном окне проверяет поля $exampleTable")
    public void thenUserChecksFieldValues(ExamplesTable examplesTable) {
        modalSteps.checkValues(examplesTable);
    }

    @Then("пользователь* в модальном окне проверяет, что у поля с именем '$elementName' имеется сообщения об ошибке с сообщением '$errorMessage'")
    public void thenUserChecksFieldWithNameHasErrorMessageWithText(String elementName, String errorMessage) {
        modalSteps.checkItemErrorMessage(elementName, errorMessage);
    }

    @Then("пользователь* в модальном окне проверяет, что заголовок равен '$title'")
    public void thenUserAssertsTheModalWindowTitle(String title) {
        modalSteps.assertTitle(title);
    }

    @When("пользователь* в модальном окне нажимает на кнопку создания")
    public void whenUserClicksOnCreateButton() {
        modalSteps.createButtonClick();
    }

    @When("пользователь* в модальном окне нажимает на кнопку сохранения")
    public void whenUserClicksOnSaveButton() {
        modalSteps.saveButtonClick();
    }

    @When("пользователь* в модальном окне нажимает на кнопку удаления")
    public void whenUserClicksOnDeleteButton() {
        modalSteps.deleteButtonClick();
    }

    @When("пользователь* в модальном окне подтверждает удаление")
    public void whenUserClicksOnConfirmDeleteButton() {
        modalSteps.confirmDeleteButtonClick();
    }
}
