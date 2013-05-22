package project.lighthouse.autotests.jbehave.section_chief;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.WriteOffSteps;

public class WriteOffUserSteps {

    @Steps
    WriteOffSteps writeOffSteps;

    @Given("the user opens the write off create page")
    public void givenTheUserOpensTheWriteOffCreatePage() {
        writeOffSteps.openPage();
    }

    @When("the user inputs '$inputValue' in the '$elementName' field on the write off page")
    @Alias("the user inputs '$inputValue' in the write off '$elementName' field")
    public void whenTheUserInputsTextInTheFieldOnTheWriteOffPage(String inputValue, String elementName) {
        writeOffSteps.input(elementName, inputValue);
    }

    @When("the user inputs '$inputValue' in the write off product '$elementName' field")
    public void whenTheUserInputsTextInTheFieldOnTheWriteOffPageDuplicate(String inputValue, String elementName) {
        writeOffSteps.input(elementName, inputValue);
    }

    @When("the user continues the write off creation")
    public void whenTheUserContinuesTheWriteOffCreation() {
        writeOffSteps.continueWriteOffCreation();
    }

    @When("the user presses the add product button and add the product to write off")
    public void whenTheAddTheProductToWriteOff() {
        writeOffSteps.addProductToWriteOff();
    }

    @When("the user makes the write off")
    public void whenTheUserMakesTheWriteOff() {
        writeOffSteps.executeWriteOff();
    }

    @When("the user generates charData with '$charNumber' number in the '$elementName' write off field")
    public void generateTestCharData(int charNumber, String elementName) {
        writeOffSteps.generateTestCharData(elementName, charNumber);
    }


    @When("the user clicks finish edit button and ends the write off edition")
    public void writeOffStopEditButtonClick() {
        writeOffSteps.writeOffStopEditButtonClick();
    }

    @When("the user clicks edit button and starts write off edition")
    public void whenTheUserClicksTheEditButtonOnProductCardViewPage() {
        writeOffSteps.editButtonClick();
    }

    @When("the user deletes the write off product with '$value' sku")
    public void whenTheUserDeletesTheProductWithSkUOnWriteOffPage(String value) {
        writeOffSteps.itemDelete(value);
    }

    @When("the user clicks on '$elementName' write off element to edit it")
    public void whenTheUserClicksOnElementtoEditIt(String elementName) {
        writeOffSteps.elementClick(elementName);
    }

    public void writeOffStopEditlinkClick() {
        writeOffSteps.writeOffStopEditlinkClick();
    }

    @Then("the user checks write off elements values $checkValuesTable")
    public void thenTheUserChecksTheElementValues(ExamplesTable checkValuesTable) {
        writeOffSteps.checkCardValue("", checkValuesTable);
    }

    @Then("the user checks the write off product with '$value' sku is present")
    public void thenTheUserChecksTheProductWithValueIsPresent(String value) {
        writeOffSteps.itemCheck(value);
    }

    @Then("the user checks the product with '$value' sku has '$elementName' element equal to '$expectedValue' on write off page")
    public void thenTheUserChecksTheProductWithValueHasElementEqualToExpectedValue(String value, String elementName, String expectedValue) {
        writeOffSteps.checkListItemHasExpectedValueByFindByLocator(value, elementName, expectedValue);
    }

    @Then("the user checks the product with '$value' sku has elements on the write off page $checkValuesTable")
    public void thenTheUserChecksTheProductWithValueHasElementEqualToExpectedValue(String value, ExamplesTable checkValuesTable) {
        writeOffSteps.checkListItemHasExpectedValueByFindByLocator(value, checkValuesTable);
    }

    @Then("the user checks '$elementName' write off field contains only '$fieldLength' symbols")
    public void checkFieldLength(String elementName, int fieldLength) {
        writeOffSteps.checkFieldLength(elementName, fieldLength);
    }

    @Then("the user checks the write off product with '$value' sku is not present")
    public void itemCheckIsNotPresent(String value) {
        writeOffSteps.itemCheckIsNotPresent(value);
    }
}
