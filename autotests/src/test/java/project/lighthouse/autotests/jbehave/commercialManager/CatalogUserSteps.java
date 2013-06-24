package project.lighthouse.autotests.jbehave.commercialManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.json.JSONException;
import project.lighthouse.autotests.steps.commercialManager.CatalogSteps;

import java.io.IOException;

public class CatalogUserSteps {

    @Steps
    CatalogSteps catalogSteps;

    @Given("the user opens catalog page")
    public void givenTheUSerOpensCatalogPage() {
        catalogSteps.openPage();
    }

    @Given("there is the class with name '$klassName'")
    public void givenThereIsTheClassWithName(String klassName) throws IOException, JSONException {
        catalogSteps.createKlassThroughPost(klassName);
    }

    @Given("there is the group with name '$groupName' related to class named '$klassName'")
    public void givenThereIsTheGroupWithNameRelatedToKlass(String groupName, String klassName) throws IOException, JSONException {
        catalogSteps.createGroupThroughPost(groupName, klassName);
    }

    @Given("the user navigates to the klass with name '$klassName'")
    public void givenTheUserNavigatesToTheKlassName(String klassName) throws JSONException {
        catalogSteps.navigateToKlassPage(klassName);
    }

    @When("the user clicks on start edition link and starts the edition")
    public void whenTheUserStartsTheEdition() {
        catalogSteps.startEditionButtonLinkClick();
    }

    @When("the user clicks on end edition link and ends the edition")
    public void whenTheUserEndsTheEdition() {
        catalogSteps.stopEditionButtonLinkClick();
    }

    @When("the user creates new class with name '$className'")
    public void whenTheUserCreatesNewClassWithName(String className) {
        catalogSteps.classCreate(className);
    }

    @When("the user clicks on the class name '$className'")
    public void whenTheUSerClicksOnTheClassName(String className) {
        catalogSteps.classClick(className);
    }

    @When("the user creates new group with name '$groupName'")
    public void whenTheUserCreatesNewGroup(String groupName) {
        catalogSteps.groupCreate(groupName);
    }

    @When("the user clicks on the group name '$groupName'")
    public void whenTheUserClicksOnTheGroupName(String groupName) {
        catalogSteps.groupClick(groupName);
    }

    @When("the user opens pop up menu of '$name' element")
    public void whenTheUserOpensPopUpMenuOfElement(String name) {
        catalogSteps.popUpMenuInteraction(name);
    }

    @When("the user opens pop up menu of group '$name' element")
    public void whenTheUserOpensPopUpMenuOfGroupElement(String name) {
        catalogSteps.popUpGroupMenuInteraction(name);
    }

    @When("the user deletes element through pop up menu")
    public void whenTheUserDeletesClassWithNameThroughPopUp() {
        catalogSteps.itemDeleteThroughPopUpMenu();
    }

    @When("the user accept pop up menu changes")
    public void whenTheUserAcceptPopUpMenuChanges() {
        catalogSteps.popUpMenuAccept();
    }

    @When("the user discards pop up menu changes")
    public void whenTheUserDiscardsDeletion() {
        catalogSteps.popUpMenuCancel();
    }

    @When("the user edits the element name through pop up menu")
    public void whenTheUserEditsTheClassName() {
        catalogSteps.itemEditThroughPopUpMenu();
    }

    @When("the user clicks create new class button")
    public void whenTheUserClicksCreateNewClassButton() {
        catalogSteps.addNewButtonClick();
    }

    @When("the user clicks create new group button")
    public void whenTheUserClicksCreateNewGroupButton() {
        catalogSteps.addNewGroupClick();
    }

    @When("the user inputs '$value' in '$element' field of pop up")
    public void whenTheUserInputsValueInTheFieldOfPopUp(String value, String element) {
        catalogSteps.input(element, value);
    }

    @When("the user clicks the create new class button in pop up")
    public void whenTheUserClicksTheCreateNewClassButtonInPopUp() {
        catalogSteps.addNewButtonConfirmClick();
    }

    @When("the user generates charData with '$number' number in the '$element' pop up field")
    public void whenTheUserGeneratesCharDate(int number, String element) {
        catalogSteps.generateTestCharData(element, number);
    }

    @When("the user generates charData with '$number' number with string char '$str' in the '$element' pop up field")
    public void whenTheUserGeneratesCharDate(int number, String str, String element) {
        catalogSteps.generateTestCharData(element, number, str);
    }

    @When("the user clicks the class '$name' link on right panel")
    public void whenTheUserClicksTheClassLink(String name) {
        catalogSteps.itemLinkClick(name);
    }

    @Then("the user checks '$element' pop up field contains only '$numbers' symbols")
    public void whenTheUserChecksNumbers(String element, int numbers) {
        catalogSteps.checkFieldLength(element, numbers);
    }

    @Then("the user checks the class with '$className' name is present")
    public void thenTheUserChecksTheClassIsPresent(String className) {
        catalogSteps.classCheck(className);
    }

    @Then("the user checks the class with '$className' name is not present")
    public void thenTheUserChecksTheClassIsNotPresent(String className) {
        catalogSteps.classCheckIsNotPresent(className);
    }

    @Then("the user checks the group with '$className' name is present")
    public void thenTheUserChecksTheGroupIsPresent(String groupName) {
        catalogSteps.groupCheck(groupName);
    }

    @Then("the user checks the group with '$className' name is not present")
    public void thenTheUserChecksTheGroupIsNotPresent(String groupName) {
        catalogSteps.groupCheckIsNotPresent(groupName);
    }

    @Then("the user checks the group with '$name' name is related to class '$parentName'")
    public void thenTheUserChecksTheItemsIsRelatedToParent(String name, String parentName) {
        catalogSteps.checkItemParent(name, parentName);
    }

    @Then("the user checks the class '$name' link is present on right panel")
    public void whenTheUserChecksTheClassLinkIsPresent(String name) {
        catalogSteps.itemLinkCheck(name);
    }
}
