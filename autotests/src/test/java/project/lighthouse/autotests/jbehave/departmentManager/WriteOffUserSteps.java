package project.lighthouse.autotests.jbehave.departmentManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.pages.departmentManager.invoice.InvoiceApi;
import project.lighthouse.autotests.steps.administrator.UserSteps;
import project.lighthouse.autotests.steps.commercialManager.CatalogSteps;
import project.lighthouse.autotests.steps.commercialManager.StoreSteps;
import project.lighthouse.autotests.steps.departmentManager.WriteOffSteps;

import java.io.IOException;

public class WriteOffUserSteps {

    @Steps
    WriteOffSteps writeOffSteps;

    @Steps
    StoreSteps storeSteps;

    @Steps
    CatalogSteps catalogSteps;

    @Steps
    UserSteps userSteps;

    @Given("the user opens the write off create page")
    public void givenTheUserOpensTheWriteOffCreatePage() throws IOException, JSONException {
        beforeSteps();
        writeOffSteps.openPage();
    }

    public void beforeSteps() throws IOException, JSONException {
        userSteps.getUser(InvoiceApi.DEFAULT_USER_NAME);
        catalogSteps.promoteDepartmentManager(storeSteps.createStore(), InvoiceApi.DEFAULT_USER_NAME);
    }

    @Given("there is the write off with number '$writeOffNumber'")
    public void givenThereIsTheWriteOffWithNumber(String writeOffNumber) throws IOException, JSONException {
        writeOffSteps.createWriteOffThroughPost(writeOffNumber);
    }

    @Given("there is the write off with number '$writeOffNumber' in the store with number '$storeNumber' ruled by user with name '$userName'")
    @Alias("there is the write off with sku '$writeOffNumber' in the store with number '$storeNumber' ruled by user with name '$userName'")
    public void givenThereIsTheWriteOffWithNumberInTheStoreRuledByUser(String writeOffNumber, String storeNumber, String userName) throws IOException, JSONException {
        writeOffSteps.createWriteOffThroughPost(writeOffNumber, storeNumber, userName);
    }

    @Given("there is the write off with '$writeOffNumber' number with product '$productSku' with quantity '$quantity', price '$price' and cause '$cause'")
    public void givenThereIsTheWriteOffWithProduct(String writeOffNumber, String productSku, String quantity, String price, String cause)
            throws IOException, JSONException {
        writeOffSteps.createWriteOffThroughPost(writeOffNumber, productSku, productSku, productSku, "kg", "15", quantity, price, cause);
    }

    @Given("the user adds the product to the write off with number '$writeOffNumber' with sku '$productSku', quantity '$quantity', price '$price, cause '$cause' in the store ruled by '$userName'")
    public void addProductToWriteOff(String writeOffNumber, String productSku, String quantity, String price, String cause, String userName) throws IOException, JSONException {
        writeOffSteps.addProductToWriteOff(writeOffNumber, productSku, quantity, price, cause, userName);
    }

    @Given("the user navigates to new write off with '$writeOffNumber' number with product '$productSku' with quantity '$quantity', price '$price' and cause '$cause'")
    public void givenThereIsTheWriteOffWithProductWithNavigation(String writeOffNumber, String productSku, String productUnits, String purchasePrice, String quantity, String price, String cause)
            throws IOException, JSONException {
        writeOffSteps.createWriteOffAndNavigateToIt(writeOffNumber, productSku, productSku, productSku, productUnits, purchasePrice, quantity, price, cause);
    }

    @Given("navigate to new write off with '$writeOffNumber' number")
    public void givenThereIsTheWriteOffWithProductWithNavigation(String writeOffNumber) throws IOException, JSONException {
        writeOffSteps.createWriteOffAndNavigateToIt(writeOffNumber);
    }

    @Given("the user navigates to the write off with number '$writeOffNumber'")
    public void givenNavigateToTheWriteOffWithNumber(String writeNumber) throws JSONException {
        writeOffSteps.navigatoToWriteOffPage(writeNumber);
    }

    @Given("there is the writeOff in the store with number '$number' ruled by department manager with name '$userName' with values $exampleTable")
    public void givenThereIsTheWriteOffInTheStoreWithValues(String number, String userName, ExamplesTable examplesTable) throws IOException, JSONException {
        writeOffSteps.createWriteOffThroughPost(number, userName, examplesTable);
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
        writeOffSteps.writeOffItemListCreate();
    }

    @When("the user goes to the write off list page by clicking the link")
    public void goToTheWriteOffListPage() {
        writeOffSteps.goToTheWriteOffListPage();
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
}
