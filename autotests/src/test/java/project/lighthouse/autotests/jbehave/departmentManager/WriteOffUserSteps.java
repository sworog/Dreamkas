package project.lighthouse.autotests.jbehave.departmentManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.steps.api.administrator.UserApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.CatalogApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.StoreApiSteps;
import project.lighthouse.autotests.steps.api.departmentManager.InvoiceApiSteps;
import project.lighthouse.autotests.steps.departmentManager.WriteOffSteps;

import java.io.IOException;

public class WriteOffUserSteps {

    @Steps
    WriteOffSteps writeOffSteps;

    @Steps
    UserApiSteps userApiSteps;

    @Steps
    CatalogApiSteps catalogApiSteps;

    @Steps
    StoreApiSteps storeApiSteps;

    @Given("the user opens the write off create page")
    public void givenTheUserOpensTheWriteOffCreatePage() throws IOException, JSONException {
        beforeSteps();
        writeOffSteps.openPage();
    }

    public void beforeSteps() throws IOException, JSONException {
        userApiSteps.getUser(InvoiceApiSteps.DEFAULT_USER_NAME);
        catalogApiSteps.promoteDepartmentManager(storeApiSteps.createStoreThroughPost(), InvoiceApiSteps.DEFAULT_USER_NAME);
    }

    @When("the user inputs '$inputValue' in the '$elementName' field on the write off page")
    @Alias("the user inputs '$inputValue' in the write off '$elementName' field")
    public void whenTheUserInputsTextInTheFieldOnTheWriteOffPage(String inputValue, String elementName) {
        writeOffSteps.input(elementName, inputValue);
    }

    @When("the user inputs <value> in the write off <elementName>")
    public void whenTheUserInputsValueInTheWriteOffElementName(String value, String elementName) {
        writeOffSteps.input(elementName, value);
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

    @When("the user clicks finish edit link and ends the write off edition")
    public void writeOffStopEditlinkClick() {
        writeOffSteps.writeOffStopEditlinkClick();
    }

    @When("the user creates write off from write off list page")
    public void whenTheUserCreatesWriteOff() {
        writeOffSteps.createInvoiceLinkClick();
    }

    @Then("the user checks write off elements values $checkValuesTable")
    public void thenTheUserChecksTheElementValues(ExamplesTable checkValuesTable) {
        writeOffSteps.checkCardValue("", checkValuesTable);
    }

    @Then("the user checks the write off product with '$value' sku is present")
    public void thenTheUserChecksTheProductWithValueIsPresent(String value) {
        writeOffSteps.itemCheck(value);
    }

    @Deprecated
    @Then("the user checks the product with '$value' sku has '$elementName' element equal to '$expectedValue' on write off page")
    public void thenTheUserChecksTheProductWithValueHasElementEqualToExpectedValue(String value, String elementName, String expectedValue) {
        writeOffSteps.checkListItemHasExpectedValueByFindByLocator(value, elementName, expectedValue);
    }

    @Deprecated
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

    @Deprecated
    @When("the user clicks on '$parentElementName' element of write off product with '$invoiceSku' sku to edit")
    public void whenTheUserClicksOnElementOfInvoiceProductWithSkuToEdit(String parentElementName, String invoiceSku) {
        writeOffSteps.childrentItemClickByFindByLocator(parentElementName, invoiceSku);
    }

    @Given("the user opens write off list page")
    public void givenTheUserOpensAmountListPage() throws IOException, JSONException {
        beforeSteps();
        writeOffSteps.writeOffListPageOpen();
    }

    @Then("the user checks the write off with '$value' is present on write off list page")
    public void thenTheUserChecksTheProductWithValueHasElement(String value) {
        writeOffSteps.listItemCheck(value);
    }

    @Then("the user checks the write off with '$value' is not present on write off list page")
    public void thenTheUserChecksTheProductWithValueHasElements(String value) {
        writeOffSteps.itemCheckIsNotPresent(value);
    }

    @Then("the user checks the product with '$value' sku has '$name' element equal to '$expectedValue' on write off list page")
    public void thenTheUserChecksTheProductWithValueHasElement(String value, String elementName, String expectedValue) {
        writeOffSteps.checkListItemHasExpectedValueByFindByLocatorInList(value, elementName, expectedValue);
    }

    @When("the user clicks the local navigation writeOff search link")
    public void whenTheUserClicksTheLocalNavigationWriteOffSearchLink() {
        writeOffSteps.searchLinkClick();
    }

    @When("the user clicks the local navigation writeOff create link")
    public void whenTheUserClicksTheLocalNavigationWriteOffCreateLink() {
        writeOffSteps.createInvoiceLinkClick();
    }
}
