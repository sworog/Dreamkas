package project.lighthouse.autotests.jbehave.commercialManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
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

    @Given("there is the group with name '$groupName'")
    public void givenThereIsTheClassWithName(String groupName) throws IOException, JSONException {
        catalogSteps.createGroupThroughPost(groupName);
    }

    @Given("there is the category with name '$categoryName' related to group named '$groupName'")
    public void givenThereIsTheGroupWithNameRelatedToKlass(String categoryName, String groupName) throws IOException, JSONException {
        catalogSteps.createCategoryThroughPost(categoryName, groupName);
    }

    @Given("the user navigates to the group with name '$groupName'")
    public void givenTheUserNavigatesToTheKlassName(String groupName) throws JSONException {
        catalogSteps.navigateToGroupPage(groupName);
    }

    @Given("the user navigates to the category with name '$categoryName' related to group named '$groupName'")
    public void givenTheUserNavigatesToTheCategoryPage(String categoryName, String groupName) throws JSONException {
        catalogSteps.navigateToCategoryPage(categoryName, groupName);
    }

    @Given("there is the subCategory with name '$subCategoryName' related to group named '$groupName' and category named '$categoryName'")
    public void givenThereIsTheSubCategory(String groupName, String categoryName, String subCategoryName) throws IOException, JSONException {
        catalogSteps.createSubCategoryThroughPost(groupName, categoryName, subCategoryName);
    }

    @When("the user clicks on start edition link and starts the edition")
    public void whenTheUserStartsTheEdition() {
        catalogSteps.startEditionButtonLinkClick();
    }

    @When("the user clicks on end edition link and ends the edition")
    public void whenTheUserEndsTheEdition() {
        catalogSteps.stopEditionButtonLinkClick();
    }

    @When("the user creates new group with name '$groupName'")
    public void whenTheUserCreatesNewGroupWithName(String groupName) {
        catalogSteps.groupCreate(groupName);
    }

    @When("the user clicks on the group name '$groupName'")
    public void whenTheUSerClicksOnTheGroupName(String groupName) {
        catalogSteps.groupClick(groupName);
    }

    @When("the user creates new category with name '$categoryName'")
    public void whenTheUserCreatesNewCategory(String categoryName) {
        catalogSteps.categoryCreate(categoryName);
    }

    @When("the user clicks on the category name '$categoryName'")
    public void whenTheUserClicksOnTheCategoryName(String categoryName) {
        catalogSteps.categoryClick(categoryName);
    }

    @When("the user creates new subCategory with name '$subCategoryName'")
    public void whenTheUserCreatesNewSubCategory(String subCategoryName) {
        catalogSteps.subCategoryCreate(subCategoryName);
    }

    @When("the user clicks on the subCategory name '$categoryName'")
    public void whenTheUserClicksOnTheSubCategoryName(String subCategoryName) {
        catalogSteps.subCategoryClick(subCategoryName);
    }

    @When("the user opens pop up menu of '$name' element")
    public void whenTheUserOpensPopUpMenuOfElement(String name) {
        catalogSteps.popUpMenuInteraction(name);
    }

    @When("the user opens pop up menu of category '$name' element")
    public void whenTheUserOpensPopUpMenuOfCategoryElement(String name) {
        catalogSteps.popUpCategoryMenuInteraction(name);
    }

    @When("the user opens pop up menu of subCategory '$subCategoryName' element")
    public void whenTheUserOpensPopUpMenuOfSubCategoryElement(String subCategoryName) {
        catalogSteps.popUpCategoryMenuInteraction(subCategoryName);
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

    @When("the user clicks create new group button")
    public void whenTheUserClicksCreateNewGroupButton() {
        catalogSteps.addNewGroupButtonClick();
    }

    @When("the user clicks create new category button")
    public void whenTheUserClicksCreateNewCategoryButton() {
        catalogSteps.addNewCategoryClick();
    }

    @When("the user clicks create new subCategory button")
    public void whenTheUserClicksCreateNewSubCategoryButton() {
        catalogSteps.addNewSubCategoryClick();
    }

    @When("the user inputs '$value' in '$element' field of pop up")
    public void whenTheUserInputsValueInTheFieldOfPopUp(String value, String element) {
        catalogSteps.input(element, value);
    }

    @When("the user clicks the create new group button in pop up")
    @Alias("the user clicks the create new category button in pop up")
    public void whenTheUserClicksTheCreateNewGroupButtonInPopUp() {
        catalogSteps.addNewButtonConfirmClick();
    }

    @When("the user clicks the create new subCategory button in pop up")
    public void whenTheUserClicksTheCreateNewSubCategoryButtonInPopUp() {
        catalogSteps.addNewSubCategoryConfirmClick();
    }

    @When("the user generates charData with '$number' number in the '$element' pop up field")
    public void whenTheUserGeneratesCharDate(int number, String element) {
        catalogSteps.generateTestCharData(element, number);
    }

    @When("the user generates charData with '$number' number with string char '$str' in the '$element' pop up field")
    public void whenTheUserGeneratesCharDate(int number, String str, String element) {
        catalogSteps.generateTestCharData(element, number, str);
    }

    @Then("the user checks '$element' pop up field contains only '$numbers' symbols")
    public void whenTheUserChecksNumbers(String element, int numbers) {
        catalogSteps.checkFieldLength(element, numbers);
    }

    @Then("the user checks the group with '$groupName' name is present")
    public void thenTheUserChecksTheClassIsPresent(String groupName) {
        catalogSteps.groupCheck(groupName);
    }

    @Then("the user checks the group with '$groupName' name is not present")
    public void thenTheUserChecksTheClassIsNotPresent(String groupName) {
        catalogSteps.groupCheckIsNotPresent(groupName);
    }

    @Then("the user checks the category with '$categoryName' name is present")
    public void thenTheUserChecksTheGroupIsPresent(String categoryName) {
        catalogSteps.categoryCheck(categoryName);
    }

    @Then("the user checks the category with '$categoryName' name is not present")
    public void thenTheUserChecksTheGroupIsNotPresent(String categoryName) {
        catalogSteps.categoryCheckIsNotPresent(categoryName);
    }

    @Then("the user checks the subCategory with '$subCategoryName' name is present")
    public void thenTheUserChecksTheSubCategoryIsPresent(String subCategoryName) {
        catalogSteps.categoryCheck(subCategoryName);
    }

    @Then("the user checks the subCategory with '$subCategoryName' name is not present")
    public void thenTheUserChecksTheSubCategoryIsNotPresent(String subCategoryName) {
        catalogSteps.categoryCheckIsNotPresent(subCategoryName);
    }

    @Then("the user checks the category with '$name' name is related to group '$parentName'")
    public void thenTheUserChecksTheItemsIsRelatedToParent(String name, String parentName) {
        catalogSteps.checkItemParent(name, parentName);
    }
}
