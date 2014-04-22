package project.lighthouse.autotests.jbehave.departmentManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.steps.api.administrator.UserApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.CatalogApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.StoreApiSteps;
import project.lighthouse.autotests.steps.api.departmentManager.InvoiceApiSteps;
import project.lighthouse.autotests.steps.departmentManager.invoice.deprecated.InvoiceSteps;

import java.io.IOException;

public class InvoiceUserSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @Steps
    UserApiSteps userApiSteps;

    @Steps
    CatalogApiSteps catalogApiSteps;

    @Steps
    StoreApiSteps storeApiSteps;

    @Given("the user is on the invoice list page")
    public void givenTheUserIsOnTheInvoiceListPage() throws IOException, JSONException {
        beforeSteps();
        invoiceSteps.openInvoiceListPage();
    }

    @Given("the user is on the store '$storeName' invoice list page")
    public void givenTheUserIsOnTheStoreInvoiceListPage(String storeName) throws JSONException {
        Store store = StaticData.stores.get(storeName);
        invoiceSteps.openStoreInvoiceListPage(store);
    }

    @Given("before steps")
    public void beforeSteps() throws IOException, JSONException {
        userApiSteps.getUser(InvoiceApiSteps.DEFAULT_USER_NAME);
        catalogApiSteps.promoteDepartmentManager(storeApiSteps.createStoreThroughPost(), InvoiceApiSteps.DEFAULT_USER_NAME);
    }

    @When("the user inputs '$inputText' in the invoice '$elementName' field")
    public void whenTheUserInputsTextInTheInvoiceField(String elementName, String inputText) {
        invoiceSteps.input(elementName, inputText);
    }

    @When("the user inputs data to the invoice $examplesTable")
    public void whenTheUserInputsDataToTheInvoice(ExamplesTable examplesTable) {
        invoiceSteps.fieldInput(examplesTable);
    }

    @When("the user inputs '$value' in the invoice product '$elementName' field")
    @Alias("the user inputs <value> in the invoice product <elementName> field")
    public void whenTheUserInputsValueInTheInvoiceProductElementNameField(String value, String elementName) {
        whenTheUserInputsTextInTheInvoiceField(elementName, value);
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

    @Then("the user checks the invoice with '$skuValue' sku has '$name' equal to '$expectedValue'")
    public void whenTheUSerChecksTheInvoiceWithSkuHasNameValueEqualToExpectedValue(String skuValue, String name, String expectedValue) {
        invoiceSteps.checkInvoiceListItemWithSkuHasExpectedValue(skuValue, name, expectedValue);
    }

    @Then("the user checks the invoice with '$skuValue' sku is present")
    public void whenTheUserChecksTheInvoiceWithSkuIsPresent(String skuValue) {
        invoiceSteps.listItemCheck(skuValue);
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

    //Search objects
    @When("the user searches invoice by sku or supplier sku '$value'")
    public void whenTheUserSearchesInvoices(String value) {
        invoiceSteps.searchInput(value);
    }

    @When("the user clicks the invoice search button and starts the search")
    public void whenTheUserClicksTheInvoiceSearhButton() {
        invoiceSteps.searchButtonClick();
    }

    @When("the user clicks on item named '$itemName'")
    public void whenTheUserClicksOnItem(String itemName) {
        invoiceSteps.itemClick(itemName);
    }

    @Then("the user checks the form results text is '$text'")
    public void thenTheUserChecksTheFormREsultText(String text) {
        invoiceSteps.checkFormResultsText(text);
    }
}
