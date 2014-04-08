package project.lighthouse.autotests.jbehave.departmentManager.order;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.steps.departmentManager.order.OrderFileSteps;
import project.lighthouse.autotests.steps.departmentManager.order.OrderSteps;

public class ThenOrderUserSteps {

    @Steps
    OrderSteps orderSteps;

    @Steps
    OrderFileSteps orderFileSteps;

    @Then("the user checks the order products list contains entry $examplesTable")
    public void thenTheUserChecksTheOrderProductListContainsEntry(ExamplesTable examplesTable) throws JSONException {
        orderSteps.productCollectionExactCompare(examplesTable);
    }

    @Then("the user checks the order product found by name '$locator' has quantity equals to expectedValue")
    public void thenTheUserChecksTheOrderProductFoundByNameHasQuantityEqualsTo(String locator, String expectedValue) {
        orderSteps.assertOrderProductObjectQuantity(locator, expectedValue);
    }

    @Then("the user checks the order product in last created order has quantity equals to expectedValue")
    public void thenTheUserChecksTheOrderProductHasQuantityEqualsTo(String expectedValue) throws JSONException {
        orderSteps.assertOrderProductObjectQuantity(expectedValue);
    }

    @Then("the user checks the order total sum is '$expectedTotalSum'")
    public void thenTheUserChecksTheOrderTotalSum(String expectedTotalSum) {
        orderSteps.assertTotalSum(expectedTotalSum);
    }

    @Then("the user checks the filled order page values $examplesTable")
    public void thenTheUserChecksTheFilledOrderPageValues(ExamplesTable examplesTable) {
        orderSteps.checkOrderPageValues(examplesTable);
    }

    @Then("the user asserts the order field label with name '$elementName'")
    public void thenTheUserAssertsTheOrderFieldLabelWithName(String elementName) {
        orderSteps.assertFieldLabelTitle(elementName);
    }

    @Then("the user checks the orders list contains exact entries $examplesTable")
    public void thenTheUserChecksTheOrdersListContainsExactEntries(ExamplesTable examplesTable) throws JSONException {
        orderSteps.assertExactOrderCollectionValues(examplesTable);
    }

    @Then("the user checks the orders list contains entry $examplesTable")
    public void thenTheUserChecksTheOrderListContainsEntry(ExamplesTable examplesTable) throws JSONException {
        orderSteps.assertOrderCollectionValues(examplesTable);
    }

    @Then("the user checks the orders list do not contain last created order")
    public void thenTheUserChecksTheOrdersListDoNotContainLastCreatedOrder() {
        orderSteps.assertOrderCollectionDoNotContainLastCreatedOrder();
    }

    @Then("the user checks the last created order products list dont contains product")
    public void thenTheUserChecksTheLastCreatedOrderProductListDonContainsProduct() throws JSONException {
        orderSteps.assertOrderProductCollectionDoNotContainsProduct();
    }

    @Then("the user checks the order products list do not contain product with name '$name'")
    public void thenTheUserChecksTheOrderProductListDoNotContainProductWithName(String name) {
        orderSteps.assertOrderProductCollectionDoNotContainsProduct(name);
    }

    @Then("the user checks the order number is expected")
    public void thenTheUserChecksTheOrderNumberIsExpected() {
        orderSteps.assertOrderNumberHeader();
    }

    @Then("the user checks the save controls text is '$value'")
    public void thenTheUserChecksTheSaveControlsText(String value) {
        orderSteps.assertSaveControlsText(value);
    }

    @Then("the user checks the download file link is clickable")
    public void thenTheUserChecksTheDownloadFileLinkIsClickable() {
        orderSteps.assertDownloadFileLinkIsClickable();
    }

    @Then("the user checks the download file link is not visible")
    public void thenTheUserChecksTheDownloadFileLinkIsNotVisible() {
        orderSteps.assertDownloadFileLinkIsNotVisible();
    }

    @Then("the user checks the downloaded file contains required data by user with name '$userName'")
    public void thenTheUserChecksTheDownloadedFileContainsRequiredData(String userName) throws Exception {
        orderFileSteps.assertOrderDownloadedFileData(userName, "lighthouse");
    }

    @Then("the user checks the order accept button should be visible")
    public void thenTheUserChecksTheOrderAcceptButtonShouldBeVisible() {
        orderSteps.orderAcceptButtonShouldBeVisible();
    }

    @Then("the user checks the order accept button should be not visible")
    public void thenTheUserChecksTheOrderAcceptButtonShouldBeNotVisible() {
        orderSteps.orderAcceptButtonShouldBeNotVisible();
    }

    @Then("the user checks the save order button should be not visible")
    public void thenTheUserChecksTheOrderSaveButtonShouldBeNotVisible() {
        orderSteps.saveButtonShouldBeNotVisible();
    }

    @Then("the user checks the save order button should be visible")
    public void thenTheUserChecksTheOrderSaveButtonShouldBeVisible() {
        orderSteps.saveButtonShouldBeVisible();
    }

    @Then("the user checks the cancel link button should be not visible")
    public void thenTheUserChecksTheCancelLinkShouldBeNotVisible() {
        orderSteps.cancelLinkShouldBeNotVisible();
    }

    @Then("the user checks the cancel link button should be visible")
    public void thenTheUserChecksTheCancelLinkShouldBeVisible() {
        orderSteps.cancelLinkShouldBeVisible();
    }

    @Then("the user waits for the order product edition preloader finish")
    public void thenTheUserWaitsForTheOrderProductEditionPreloaderFinish() {
        orderSteps.waitForPreLoader();
    }
}
