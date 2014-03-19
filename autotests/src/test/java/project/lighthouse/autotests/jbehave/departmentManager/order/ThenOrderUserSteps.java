package project.lighthouse.autotests.jbehave.departmentManager.order;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.steps.departmentManager.order.OrderSteps;

public class ThenOrderUserSteps {

    @Steps
    OrderSteps orderSteps;

    @Then("the user checks the order products list contains entry $examplesTable")
    public void thenTheUserChecksTheOrderProductListContainsEntry(ExamplesTable examplesTable) {
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

    @Then("the user checks the filled autocomplete values in product addition form $examplesTable")
    public void thenTheUserChecksTheFilledAutoCompleteValues(ExamplesTable examplesTable) {
        orderSteps.checksValues(examplesTable);
    }

    @Then("the user checks the autocomplete values $examplesTable")
    public void thenTheUserChecksTheAdditionProductFormValues(ExamplesTable examplesTable) {
        orderSteps.additionFormCheckValues(examplesTable);
    }

    @Then("the user asserts the order field label with name '$elementName'")
    public void thenTheUserAssertsTheOrderFieldLabelWithName(String elementName) {
        orderSteps.assertFieldLabelTitle(elementName);
    }

    @Then("the user asserts the order field label with name '$elementName' of product addition form")
    public void thenTheUserAssertsTheOrderFieldLabelWithNameOfProductAdditionForm(String elementName) {
        orderSteps.assertAdditionProductFormLabelTitle(elementName);
    }

    @Then("the user checks the orders list contains required entry")
    public void thenTheUserChecksTheOrdersListContainsRequiredEntry() throws JSONException {
        orderSteps.assertOrderCollectionValues();
    }

    @Then("the user checks the orders list contains required entries")
    public void thenTheUserChecksTheOrdersListContainsEntry() throws JSONException {
        orderSteps.assertAnotherOrderCollectionValues();
    }
}
