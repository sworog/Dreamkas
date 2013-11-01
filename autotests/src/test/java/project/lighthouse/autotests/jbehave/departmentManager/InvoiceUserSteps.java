package project.lighthouse.autotests.jbehave.departmentManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.*;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.helper.ExampleTableConverter;
import project.lighthouse.autotests.pages.departmentManager.invoice.InvoiceApi;
import project.lighthouse.autotests.steps.administrator.UserSteps;
import project.lighthouse.autotests.steps.commercialManager.CatalogSteps;
import project.lighthouse.autotests.steps.commercialManager.StoreSteps;
import project.lighthouse.autotests.steps.departmentManager.InvoiceSteps;

import java.io.IOException;

public class InvoiceUserSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @Steps
    StoreSteps storeSteps;

    @Steps
    CatalogSteps catalogSteps;

    @Steps
    UserSteps userSteps;

    private ExamplesTable examplesTable;

    @Given("there is the invoice '$invoiceSku' with product '$productName' name, '$productSku' sku, '$productBarCode' barcode, '$productUnits' units")
    public void givenThereIsInvoiceWithProduct(String invoiceSku, String productName, String productSku, String productBarCode, String productUnits) throws JSONException, IOException {
        invoiceSteps.createInvoiceThroughPostWithData(invoiceSku, productName, productSku, productBarCode, productUnits);
    }

    @Given("there is the invoice with '$sku' sku")
    public void givenThereIsTheInvoiceWithSku(String sku) throws JSONException, IOException {
        invoiceSteps.createInvoiceThroughPost(sku);
    }

    @Given("there is the invoice with sku '$sku' in the store with number '$number' ruled by department manager with name '$userName'")
    public void givenThereIsTheInvoiceInTheStore(String sku, String number, String userName) throws IOException, JSONException {
        invoiceSteps.createInvoiceThroughPost(sku, number, userName);
    }

    @Given("there is the invoice in the store with number '$number' ruled by department manager with name '$userName' with values $exampleTable")
    public void givenThereIsTheInvoiceInTheStoreWithValues(String number, String userName, ExamplesTable examplesTable) throws IOException, JSONException {
        invoiceSteps.createInvoiceThroughPost(number, userName, examplesTable);
        this.examplesTable = examplesTable;
    }

    @Given("the user is on the invoice create page")
    public void givenTheUserIsOnTheInvoiceCreatePage() throws IOException, JSONException {
        beforeSteps();
        invoiceSteps.openInvoiceCreatePage();
    }

    @Given("the user is on the invoice list page")
    public void givenTheUserIsOnTheInvoiceListPage() throws IOException, JSONException {
        beforeSteps();
        invoiceSteps.openInvoiceListPage();
    }

    @Given("before steps")
    public void beforeSteps() throws IOException, JSONException {
        userSteps.getUser(InvoiceApi.DEFAULT_USER_NAME);
        catalogSteps.promoteDepartmentManager(storeSteps.createStore(), InvoiceApi.DEFAULT_USER_NAME);
    }

    @Given("the user navigates to the invoice page with name '$invoiceName'")
    public void givenTheUserNavigatesToTheInvoicePage(String invoiceName) throws JSONException {
        invoiceSteps.navigateToTheInvoicePage(invoiceName);
    }

    @Given("the user adds the product to the invoice with name '$invoiceName' with sku '$productSku', quantity '$quantity', price '$price' in the store ruled by '$userName'")
    public void addProductToInvoice(String invoiceName, String productSku, String quantity, String price, String userName) throws IOException, JSONException {
        invoiceSteps.addProductToInvoice(invoiceName, productSku, quantity, price, userName);
    }

    @When("the user inputs '$inputText' in the invoice '$elementName' field")
    public void whenTheUserInputsTextInTheInvoiceField(String elementName, String inputText) {
        invoiceSteps.input(elementName, inputText);
    }

    @When("the user clicks the invoice create button")
    @Pending
    public void whenTheUserClicksTheInvoiceCreateButton() {
        //Pending
    }

    @When("the user clicks the create button on the invoice list page")
    public void whenTheUserClicksTheCreateButtonOnTheInvoiceListPage() {
        invoiceSteps.invoiceListItemCreate();
    }

    @When("the user clicks edit button and starts invoice edition")
    public void whenTheUserClicksTheEditButtonOnProductCardViewPage() {
        invoiceSteps.editButtonClick();
    }

    @When("the user open the invoice card with '$skuValue' sku")
    public void whenTheUserOpenTheProductCard(String skuValue) {
        invoiceSteps.listItemClick(skuValue);
    }

    @When("the user generates charData with '$charNumber' number in the '$elementName' invoice field")
    public void whenTheUserGeneratesCharData(String elementName, int charNumber) {
        invoiceSteps.generateTestCharData(elementName, charNumber);
    }

    @When("the user navigates to invoice product addition")
    public void whenTheUserNavigatesToInvoiceProductAddition() {
        invoiceSteps.goToTheaAdditionOfProductsLinkClick();
    }

    @When("the user inputs '$value' in the invoice product '$elementName' field")
    @Alias("the user inputs <value> in the invoice product <elementName> field")
    public void whenTheUserInputsValueInTheInvoiceProductElementNameField(String value, String elementName) {
        whenTheUserInputsTextInTheInvoiceField(elementName, value);
    }

    @When("the user clicks the add more product button")
    public void whenTheUserClicksTheAddMoreProductButton() {
        invoiceSteps.addOneMoreProductLinkClick();
    }

    @When("the user clicks on '$elementName' element to edit it")
    public void whenTheUserClicksOnElementtoEditIt(String elementName) {
        invoiceSteps.elementClick(elementName);
    }

    @When("the user clicks OK and accepts changes")
    public void whenTheUSerClicksOkAndAcceptsChanges() throws InterruptedException {
        invoiceSteps.acceptChangesButtonClick();
    }

    @When("the user clicks Cancel and discard changes")
    public void whenTheUserClicksCancelAndDiscardTheChanges() {
        invoiceSteps.discardChangesButtonClick();
    }

    @When("the user clicks OK and accepts deletion")
    public void whenTheUSerClicksOkAndAcceptsDeletion() throws InterruptedException {
        invoiceSteps.acceptDeleteButtonClick();
    }

    @When("the user clicks Cancel and discard deletion")
    public void whenTheUserClicksCancelAndDiscardTheDeletion() {
        invoiceSteps.discardDeleteButtonClick();
    }

    @When("the user clicks finish edit button and ends the invoice edition")
    public void whenTheUserClicksFinishEDitButtonAndEndsEdition() {
        invoiceSteps.invoiceStopEditButtonClick();
    }

    @When("the user clicks finish edit link and ends the invoice edition")
    public void whenTheUserClicksFinishEditLinkAndEndsEdition() {
        invoiceSteps.invoiceStopEditlinkClick();
    }

    @When("the user edits '$elementName' element with new value '$newValue' and verify the '$checkType' changes")
    public void whenTheUserEditElementWithNewValueAndVerify(String elementName, String newValue, String checkType) throws InterruptedException {
        String newElementName = "inline " + elementName;
        whenTheUserClicksOnElementtoEditIt(elementName);
        whenTheUserInputsTextInTheInvoiceField(newElementName, newValue);
        whenTheUSerClicksOkAndAcceptsChanges();
        thenTheUserChecksTheElementValue(checkType, elementName, newValue);
    }

    @When("the user clicks on '$elementClassName' element of invoice product with '$elementName' sku")
    public void whenTheUserClicksOnElementOfInvoiceProductWithSku(String elementClassName, String elementName) {
        invoiceSteps.childrenElementClick(elementName, elementClassName);
    }

    @When("the user clicks on '$parentElementName' element of invoice product with '$invoiceSku' sku to edit")
    public void whenTheUserClicksOnElementOfInvoiceProductWithSkuToEdit(String parentElementName, String invoiceSku) {
        invoiceSteps.childrentItemClickByFindByLocator(parentElementName, invoiceSku);
    }


    @When("the user clicks the add invoice product button and adds the invoice product")
    public void whenTheUserClicksTheAddInvoiceProductButtonAndAddsTheInvoiceProduct() {
        invoiceSteps.addNewInvoiceProductButtonClick();
    }

    @When("the user deletes the invoice product with '$elementName' sku")
    public void whenTheUserDeletesTheInvoiceProducWithSku(String elementName) {
        invoiceSteps.childrenItemNavigateAndClickByFindByLocator(elementName);
    }

    @When("the user try to delete the invoice product with '$elementName' sku")
    public void whenTheUserTryToDeleteTheInvoiceProductWithSku(String elementName) {
        invoiceSteps.tryTochildrenItemNavigateAndClickByFindByLocator(elementName);
    }

    @Then("the user checks the invoice with '$skuValue' sku has '$name' equal to '$expectedValue'")
    public void whenTheUSerChecksTheInvoiceWithSkuHasNameValueEqualToExpectedValue(String skuValue, String name, String expectedValue) {
        invoiceSteps.checkInvoiceListItemWithSkuHasExpectedValue(skuValue, name, expectedValue);
    }

    @Then("the user checks the invoice with '$skuValue' sku is present")
    public void whenTheUserChecksTheInvoiceWithSkuIsPresent(String skuValue) {
        invoiceSteps.listItemCheck(skuValue);
    }

    @Then("the user checks the invoice with '$skuValue' sku is not present")
    public void whenTheUserChecksTheInvoiceWithSkuIsNotPresent(String skuValue) {
        invoiceSteps.checkItemIsNotPresent(skuValue);
    }

    @Then("the user checks the invoice '$elementName' value is '$expectedValue'")
    public void thenTheUserChecksValue(String elementName, String expectedValue) {
        invoiceSteps.checkCardValue(elementName, expectedValue);
    }

    @Then("the user checks invoice '$checkType' elements values $checkValuesTable")
    public void thenTheUserChecksTheElementValues(String checkType, ExamplesTable checkValuesTable) {
        invoiceSteps.checkCardValue(checkType, checkValuesTable);
    }

    @Then("the user checks invoice '$checkType' element '$elementName' equal to '$expectedValue'")
    public void thenTheUserChecksTheElementValue(String checkType, String elementName, String expectedValue) {
        invoiceSteps.checkCardValue(checkType, elementName, expectedValue);
    }

    @Then("the user checks invoice elements values $checkValuesTable")
    public void thenTheUserChecksTheElementValues(ExamplesTable checkValuesTable) {
        invoiceSteps.checkCardValue("", checkValuesTable);
    }

    @Then("the user checks '$elementName' invoice field contains only '$fieldLength' symbols")
    public void thenTheUserChecksNameFieldContainsOnlyExactSymbols(String elementName, int fieldLength) {
        invoiceSteps.checkFieldLength(elementName, fieldLength);
    }

    @Then("the user checks the '$elementName' is prefilled and equals NowDate")
    public void thenTheUserChecksTheDateIsPrefilledAndEquals(String elementName) {
        invoiceSteps.checkTheDateisNowDate(elementName);
    }

    @Then("the user checks the invoice product with '$value' sku is present")
    public void whenTheUserChecksTheInvoiceProductWithSKuIsPresent(String value) {
        invoiceSteps.invoiceProductListItemCheck(value);
    }

    @Then("the user checks the product with '$value' sku has values $checkValuesTable")
    public void thenTheUserChecksTheProductWithSkuHasValues(String value, ExamplesTable checkValuesTable) {
        invoiceSteps.checkListItemWithSkuHasExpectedValue(value, checkValuesTable);
    }

    @Then("the user checks the invoice product with '$elementName' sku is not present")
    public void thenTheUserChecksTheInvoiceProductWithSkuIsNotPresent(String elementName) {
        invoiceSteps.checkItemIsNotPresent(elementName);
    }

    //Search objects
    @When("the user searches invoice by sku or supplier sku '$value'")
    public void whenTheUserSearchesInvoices(String value) {
        invoiceSteps.searchInput(value);
    }

    @When("the user clicks the invoice search buttton and starts the search")
    public void whenTheUserClicksTheInvoiceSearhButton() {
        invoiceSteps.searchButtonClick();
    }

    @When("the user clicks the local navigation invoice search link")
    public void whenTheUserClicksTheLocalNavigationInvoiceSearchLink() {
        invoiceSteps.searchLinkClick();
    }

    @Then("the user checks the invoice with sku '$sku' in search results")
    public void thenTheUserChecksTheInvoiceInSearchResults(String sku) {
        invoiceSteps.checkHasInvoice(sku);
    }

    @Then("the user checks the invoice search result list contains entry with stored values")
    public void thenTheUserChecksTheInvoiceSearchResult() {
        invoiceSteps.invoiceCompareWithExampleTable(ExampleTableConverter.convert(examplesTable));
    }

    @Then("the user checks the form results text is '$text'")
    public void thenTheUserChecksTheFormREsultText(String text) {
        invoiceSteps.checkFormResultsText(text);
    }

    @When("the user clicks on the search result invoice with sku '$sku'")
    public void whenTheUserClickOnTheSearchResultInvoice(String sku) {
        invoiceSteps.searchResultClick(sku);
    }

    @Then("the user checks the highlighted text is '$expectedHighlightedText'")
    public void thenTheUserChecksTheHighLightedText(String expectedHighlightedText) {
        invoiceSteps.checkHighlightsText(expectedHighlightedText);
    }
}
