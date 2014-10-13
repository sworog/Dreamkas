package ru.dreamkas.jbehave.general;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.*;
import org.jbehave.core.model.ExamplesTable;
import ru.dreamkas.steps.general.ModalSteps;

public class ModalEndUserSteps {

    @Steps
    ModalSteps modalSteps;

    @Given("пользователь* находится в модальном окне '$modalPageObjectName'")
    @When("пользователь* находится в модальном окне '$modalPageObjectName'")
    public void givenUserSetsModalWindowPageObjectWithName(String modalPageObjectName) {
        modalSteps.setCurrentPageObject(modalPageObjectName);
    }

    @When("пользователь* в модальном окне вводит данные $examplesTable")
    public void whenUserInputsValues(ExamplesTable examplesTable) {
        modalSteps.input(examplesTable);
    }

    @When("пользователь* в модальном окне вводит значение '$value' в поле с именем '$elementName'")
    @Alias("пользователь* в модальном окне вводит значение value в поле с именем '$elementName'")
    public void whenUserInputsValueInFieldWithName(String value, String elementName) {
        modalSteps.input(elementName, value);
    }

    @Then("пользователь* в модальном окне проверяет, что поле с именем '$elementName' имеет значение '$expectedValue'")
    @Alias("пользователь* в модальном окне проверяет, что поле с именем '$elementName' имеет значение expectedValue")
    public void thenUserChecksValue(String elementName, String expectedValue) {
        modalSteps.checkValue(elementName, expectedValue);
    }

    @Then("пользователь* в модальном окне проверяет поля $exampleTable")
    public void thenUserChecksFieldValues(ExamplesTable examplesTable) {
        modalSteps.checkValues(examplesTable);
    }

    @Then("пользователь* в модальном окне проверяет, что у поля с именем '$elementName' имеется сообщения об ошибке с сообщением '$errorMessage'")
    @Alias("пользователь* в модальном окне проверяет, что у поля с именем '$elementName' имеется сообщения об ошибке с сообщением errorMessage")
    public void thenUserChecksFieldWithNameHasErrorMessageWithText(String elementName, String errorMessage) {
        modalSteps.checkItemErrorMessage(elementName, errorMessage);
    }

    @Then("пользователь* в модальном окне проверяет, что заголовок равен '$title'")
    public void thenUserAssertsTheModalWindowTitle(String title) {
        modalSteps.assertTitle(title);
    }

    @When("пользователь* в модальном окне нажимает на кнопку создания")
    @Aliases(values = {
            "пользователь* в модальном окне нажимает на кнопку сохранения",
            "пользователь* в модальном окне нажимает на кнопку расчета чтобы совершить продажу",
            "пользователь* в модальном окне нажимает на кнопку вернуть, чтобы совершить возврат"
    })
    public void whenUserClicksOnConfirmationButton() {
        modalSteps.confirmationClick();
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
