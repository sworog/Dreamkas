package project.lighthouse.autotests.jbehave.commercialManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.json.JSONException;
import org.junit.Assert;
import project.lighthouse.autotests.objects.Store;
import project.lighthouse.autotests.steps.AuthorizationSteps;
import project.lighthouse.autotests.steps.CommonSteps;
import project.lighthouse.autotests.steps.commercialManager.CatalogSteps;
import project.lighthouse.autotests.steps.commercialManager.StoreSteps;

import java.io.IOException;

public class CatalogUserSteps {

    @Steps
    CatalogSteps catalogSteps;

    @Steps
    StoreSteps storeSteps;

    @Steps
    AuthorizationSteps authorizationSteps;

    @Steps
    CommonSteps commonSteps;

    String retailMarkupMinValue, retailMarkupMaxValue;
    String newRetailMarkupMinValue, newRetailMarkupMaxValue;

    @Given("the user opens catalog page")
    public void givenTheUSerOpensCatalogPage() {
        catalogSteps.openPage();
    }

    @Given("there is the group with name '$groupName'")
    public void givenThereIsTheGroupWithName(String groupName) throws IOException, JSONException {
        catalogSteps.createGroupThroughPost(groupName);
    }

    @Given("there is the category with name '$categoryName' related to group named '$groupName'")
    public void givenThereIsTheCategoryWithNameRelatedToGroup(String categoryName, String groupName) throws IOException, JSONException {
        catalogSteps.createCategoryThroughPost(categoryName, groupName);
    }

    @Given("the user navigates to the group with name '$groupName'")
    public void givenTheUserNavigatesToTheGroup(String groupName) throws JSONException {
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

    @Given("there is the subCategory with rounding set to '$rounding' with name '$subCategoryName' related to group named '$groupName' and category named '$categoryName'")
    public void createSubCategoryThroughPost(String rounding, String groupName, String categoryName, String subCategoryName) throws IOException, JSONException {
        catalogSteps.createSubCategoryThroughPost(groupName, categoryName, subCategoryName, rounding);
    }

    @Given("the user navigates to the subCategory '$subCategoryName', category '$categoryName', group '$groupName' product list page")
    public void navigateToSubCategoryProductListPageUrl(String subCategoryName, String categoryName, String groupName) throws JSONException {
        catalogSteps.navigateToSubCategoryProductListPageUrl(subCategoryName, categoryName, groupName);
    }

    @Given("the user sets subCategory '$subCategoryName' mark up with max '$maxValue' and min '$minValue' values")
    public void givenTheUSerSetsSubCategoryMarkUp(String subCategoryName, String maxValue, String minValue) throws IOException, JSONException {
        catalogSteps.setSubCategoryMarkUp(maxValue, minValue, subCategoryName);
    }

    @Given("there is the store with number '$storeNumber' managed by '$userName'")
    @Alias("there is the store with <storeNumber> managed by <userName>")
    public void givenThereIsTheStoreManagedBy(String storeNumber, String userName) throws IOException, JSONException {
        Store store = storeSteps.createStore(storeNumber, userName, userName);
        catalogSteps.promoteStoreManager(store, userName);
    }

    @Given("the user navigates to the store '$storeName' catalog page")
    public void givenTheUserNaviagtesToTheStoreCatalogPage(String storeName) throws JSONException {
        storeSteps.navigatesToTheStoreCatalogPage(storeName);
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
        catalogSteps.popUpSubCategoryMenuInteraction(subCategoryName);
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

    @When("the user creates new product through pop up menu")
    public void whenTheUserCreatesNewProductThroughPopUpMenu() {
        catalogSteps.popUpMenuProductCreate();
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
        catalogSteps.subCategoryCheck(subCategoryName);
    }

    @Then("the user checks the subCategory with '$subCategoryName' name is not present")
    public void thenTheUserChecksTheSubCategoryIsNotPresent(String subCategoryName) {
        catalogSteps.subCategoryIsNotPresent(subCategoryName);
    }

    @Then("the user checks the category with '$name' name is related to group '$parentName'")
    public void thenTheUserChecksTheItemsIsRelatedToParent(String name, String parentName) {
        catalogSteps.checkItemParent(name, parentName);
    }

    @Then("the user checks the edit button is not present")
    public void whenTheUserChecksTheEditIsNotPresent() {
        catalogSteps.startEditButtonLinkClickIsNotPresent();
    }

    @When("the user switches to '$propertiesTypeTab' properties tab")
    public void whenTheUserSwitchesToPropertiesTab(String propertiesTypeTab) {
        catalogSteps.propertiesTabClick(propertiesTypeTab);
    }

    @When("the user switches to '$mainTypeTab' main tab")
    public void whenTheUserSwitchesToMainTab(String mainTypeTab) {
        catalogSteps.mainTabClick(mainTypeTab);
    }

    @When("the user clicks save mark up button")
    public void whenTheUserClicksSaveMarkUpButton() {
        catalogSteps.saveMarkUpButtonClick();
    }

    @When("the user sets min mark up value to '$value'")
    public void whenTheUserSetsMinMarkUpValue(String value) {
        catalogSteps.retailMarkupMinSet(value);
        retailMarkupMinValue = value;
    }

    @When("the user sets max mark up value to '$value'")
    public void whenTheUserSetsMaxMarkUpValue(String value) {
        catalogSteps.retailMarkupMaxSet(value);
        retailMarkupMaxValue = value;
    }

    @When("the user resets min mark up value to '$value'")
    @Alias("the user sets new min mark up value to '$value'")
    public void whenTheUserReSetsMinMarkUpValue(String value) {
        catalogSteps.retailMarkupMinSet(value);
        newRetailMarkupMinValue = value;
    }

    @When("the user resets max mark up value to '$value'")
    @Alias("the user sets new max mark up value to '$value'")
    public void whenTheUserReSetsMaxMarkUpValue(String value) {
        catalogSteps.retailMarkupMaxSet(value);
        newRetailMarkupMaxValue = value;
    }

    @Then("the user checks the stored min mark up value")
    public void thenTheUSerChecksTheStoredMinMarkUpValue() {
        catalogSteps.retailMarkupMinCheck(retailMarkupMinValue);
    }

    @Then("the user checks the stored max mark up value")
    public void thenTheUSerChecksTheStoredMaxMarkUpValue() {
        catalogSteps.retailMarkupMaxCheck(retailMarkupMaxValue);
    }

    @Then("the user checks the stored mark up values")
    @Alias("the user checks the stored mark up values are not changed")
    public void thenTheUserChecksTheStoredMarkUpValues() {
        catalogSteps.retailMarkupMinCheck(retailMarkupMinValue);
        catalogSteps.retailMarkupMaxCheck(retailMarkupMaxValue);
    }

    @Then("the user checks mark up values are empty")
    public void thenTheUserChecksMarkUpValuesAreEmpty() {
        catalogSteps.retailMarkupMinCheck("");
        catalogSteps.retailMarkupMaxCheck("");
    }

    @Then("the user checks the stored mark up values are new ones")
    public void thenTheUserChecksTheStoredMarkUpValuesAreNewOnes() {
        catalogSteps.retailMarkupMinCheck(newRetailMarkupMinValue);
        catalogSteps.retailMarkupMaxCheck(newRetailMarkupMaxValue);
    }

    @Then("the user sees success message '$expectedMessage'")
    public void thenTheUserSeesSuccessMessage(String expectedMessage) {
        catalogSteps.checkSuccessMessage(expectedMessage);
    }

    @When("the user sets <markUpType> with <value>")
    public void setMarkUpValue(String markUpType, String value) {
        switch (markUpType) {
            case "max":
                validateMaxMarkUpValue(value);
                break;
            case "min":
                validateMinMarkUpValue(value);
                break;
            default:
                Assert.fail(
                        String.format("No such value '%s'", markUpType)
                );
        }
    }

    public void validateMaxMarkUpValue(String value) {
        whenTheUserSetsMinMarkUpValue("0");
        whenTheUserSetsMaxMarkUpValue(value);
    }

    public void validateMinMarkUpValue(String value) {
        whenTheUserSetsMinMarkUpValue(value);
        whenTheUserSetsMaxMarkUpValue("100");
    }

    @Then("the user user sees <errorMessage>")
    public void thenTheUserSeesErrorMessage(String errorMessage) {
        commonSteps.checkErrorMessage(errorMessage);
    }

    @When("the user set price roundings to <value>")
    @Alias("the user set price roundings to '$value'")
    public void setRoundings(String value) {
        catalogSteps.setRoundings(value);
    }

    @Then("the user checks the price roundings dropdawn default selected value is '$value'")
    @Alias("the user checks the price rounding selected value is <value>")
    public void thenTheUserChecksThePriceRoundingsDropDawnDefaultValue(String value) {
        catalogSteps.checkDropDownDefaultValue(value);
    }

    @When("the user clicks on products export link")
    public void WhenTheUserClicksOnProductExportLink() {
        catalogSteps.productsExportLinkClick();
    }

    @Then("the user checks the products export link is not present")
    public void thenTheUserChecksTheProductsExportLinkIsNotPresent() {
        catalogSteps.productsExportLinkIsNotPresent();
    }
}
