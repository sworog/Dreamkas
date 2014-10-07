package project.lighthouse.autotests.jbehave.general;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.general.GeneralSteps;

public class GeneralEndUserSteps {

    @Steps
    GeneralSteps generalSteps;

    @Given("пользователь* находится на странице '$pageObjectName'")
    @When("пользователь* находится на странице '$pageObjectName'")
    public void givenUserSetsPageObjectWihName(String pageObjectName) {
        generalSteps.setCurrentPageObject(pageObjectName);
    }

    @When("пользователь* вводит данные в поля $exampleTable")
    public void userInputsField(ExamplesTable exampleTable) {
        generalSteps.input(exampleTable);
    }


    @When("пользователь* вводит значение '$value' в поле с именем '$elementName'")
    @Alias("пользователь* вводит значение value в поле с именем '$elementName'")
    public void whenUserInputsValueInFieldWithName(String value, String elementName) {
        generalSteps.input(elementName, value);
    }

    @When("пользователь* нажимает на элемент списка '$collectionName' с именем '$locator'")
    public void whenUserClicksOnCollectionObjectByLocator(String locator) {
        generalSteps.clickOnCollectionObjectByLocator(locator);
    }

    @When("пользователь* нажимает на елемент с именем '$name'")
    public void whenTheUserClicksOnElementWithName(String name) {
        generalSteps.clickOnCommonItemWihName(name);
    }

    @Then("пользователь* проверяет, что поле с именем '$elementName' имеет значение '$value'")
    public void thenUserChecksValue(String elementName, String value) {
        generalSteps.checkValue(elementName, value);
    }

    @Then("пользователь* проверяет поля $exampleTable")
    public void thenUserChecksFieldValues(ExamplesTable examplesTable) {
        generalSteps.checkValues(examplesTable);
    }

    @Then("пользователь* проверяет, что у поля с именем '$elementName' имеется сообщения об ошибке с сообщением '$errorMessage'")
    public void thenUserChecksFieldWithNameHasErrorMessageWithText(String elementName, String errorMessage) {
        generalSteps.checkItemErrorMessage(elementName, errorMessage);
    }

    @Then("пользователь* проверяет, что заголовок равен '$title'")
    public void thenUserAssertsTheModalWindowTitle(String title) {
        generalSteps.assertTitle(title);
    }

    @Then("пользователь* проверяет, что элемент с именем '$elementName' должен быть видимым")
    public void thenUserChecksTheElementWithNameShouldBeVisible(String elementName) {
        generalSteps.elementShouldBeVisible(elementName);
    }

    @Then("пользователь* проверяет, что элемент с именем '$elementName' должен быть невидимым")
    public void thenUserChecksTheElementWithNameShouldBeNotVisible(String elementName) {
        generalSteps.elementShouldBeNotVisible(elementName);
    }

    @Then("пользователь* проверяет, что список '$collectionName' содержит точные данные $examplesTable")
    public void thenUserExactCompareCollectionWithExamplesTable(ExamplesTable examplesTable) {
        generalSteps.exactCompareExampleTable(examplesTable);
    }

    @Then("пользователь* проверяет, что список '$collectionName' содержит данные $examplesTable")
    public void thenUserCompareCollectionWithExamplesTable(ExamplesTable examplesTable) {
        generalSteps.compareWithExampleTable(examplesTable);
    }

    @Then("пользователь проверяет, что у элемента с именем '$commonItemName' аттрибут '$attribute' имеет значение '$value'")
    public void thenTheUserChecksCommonItemAttributeValue(String commonItemName, String attribute, String value) {
        generalSteps.assertCommonItemAttributeValue(commonItemName, attribute, value);
    }
}
