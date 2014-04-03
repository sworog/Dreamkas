package project.lighthouse.autotests.jbehave.departmentManager.order;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.steps.departmentManager.order.OrderSteps;

public class WhenOrderUserSteps {

    @Steps
    OrderSteps orderSteps;

    @When("the user inputs values on order page $examplesTable")
    public void whenTheUserInputsValuesOnOrderPage(ExamplesTable examplesTable) {
        orderSteps.input(examplesTable);
    }

    @When("the user inputs value in element '$elementName' on order page")
    public void whenTheUserInputsValueInElementOnOrderPage(String value, String elementName) {
        orderSteps.input(elementName, value);
    }

    @When("the user inputs values in addition new product form on the order page $examplesTable")
    public void whenTheUserInputsValuesInAdditionNewProductFormOnTheOrderPage(ExamplesTable examplesTable) {
        orderSteps.additionFormInput(examplesTable);
    }

    @When("the user inputs values in edition new product form on the order page $examplesTable")
    public void whenTheUserInputsValuesInEditionNewProductFormOnTheOrderPage(ExamplesTable examplesTable) {
        orderSteps.editionFormInput(examplesTable);
    }

    @When("the user inputs value in elementName '$elementName' in addition new product form on the order page")
    public void whenTheUserInputsValuesInAdditionNewProductFormOnTheOrderPage(String value, String elementName) {
        orderSteps.additionFormInput(elementName, value);
    }

    @When("the user inputs value in elementName '$elementName' in edition new product form on the order page")
    public void whenTheUserInputsValuesInEditionNewProductFormOnTheOrderPage(String value, String elementName) {
        orderSteps.editionFormInput(elementName, value);
    }

    @When("the user clicks the save order button")
    public void whenTheUserClicksTheCreateOrderButton() {
        orderSteps.saveButtonClick();
    }

    @When("the user clicks the order delete button and confirms the deletion")
    public void whenTheUserClicksTheOrderDeleteButtonAndConfirmsTheDeletion() {
        orderSteps.deleteButtonClickAndConfirmTheDeletion();
    }

    @When("the user clicks the order delete button and dismisses the deletion")
    public void whenTheUserClicksTheOrderDeleteButtonAndDismissesTheDeletion() {
        orderSteps.deleteButtonClickAndDismissTheDeletion();
    }

    @When("the user clicks the cancel link button")
    public void whenTheUserClicksTheCancelLinkrButton() {
        orderSteps.cancelLinkClick();
    }

    @When("the user clicks the add order product button")
    public void whenTheUserClicksTheAddOrderProductButton() {
        orderSteps.addProductToOrderButtonClick();
    }

    @When("the user clicks the edit order product button")
    public void whenTheUserClicksTheEditOrderProductButton() {
        orderSteps.editOrderProductButtonClick();
    }

    @When("the user clicks the cancel order product button")
    public void whenTheUserClicksTheCancelOrderProductButton() {
        orderSteps.cancelOrderProductButtonClick();
    }

    @When("the user clicks on order product in last created order")
    public void whenTheUserClicksOnOrderProductInLastCreatedOrder() throws JSONException {
        orderSteps.lastCreatedOrderProductCollectionObjectClickByLocator();
    }

    @When("the user inputs quantity '$value' on the order product in last created order")
    @Alias("the user inputs quantity value on the order product in last created order")
    public void whenTheUserInputsQuantityValueOnTheOrderProductInLastCreatedOrder(String value) throws JSONException {
        orderSteps.lastCreatedOrderProductCollectionObjectQuantityType(value);
    }

    @When("the user inputs quantity '$value' on the order product with name '$name'")
    public void whenTheUserInputsQuantityValueOnTheOrderProductInLastCreatedOrder(String value, String name) throws JSONException {
        orderSteps.orderProductCollectionObjectQuantityType(name, value);
    }

    @When("the user clicks on delete icon and deletes order product with name '$name'")
    public void whenTheUserClicksOnDeleteIconAndDeletesOrderProductWithName(String name) {
        orderSteps.orderProductCollectionObjectDeleteIconClick(name);
    }

    @When("the user clicks on delete icon and deletes last created order product")
    public void whenTheUserClicksOnDeleteIconAndDeletesLastCreatedOrderProduct() throws JSONException {
        orderSteps.lastCreatedOrderProductCollectionObjectDeleteIconClick();
    }

    @When("the user clicks on the order product by name '$name'")
    public void whenTheUserClicksOnTheOrderProductByName(String name) {
        orderSteps.orderProductCollectionObjectClickByLocator(name);
    }

    @When("the user clicks on last created order on the orders list page")
    public void whenTheUserClicksOnLastCreatedOrderOnTheOrdersListPage() {
        orderSteps.lastCreatedOrderCollectionObjectClick();
    }

    @When("the user clicks on the order with number '$number' on the orders list page")
    public void whenTheUserClicksOnTheOrderWithNumberOnOrdersListPage(String number) {
        orderSteps.openOrderWithNameObjectClick(number);
    }

    @When("the user clicks on deletion item icon to delete edited order product")
    public void whenTheUserClicksOnDeletionItemIconToDeleteEditedOrderProduct() {
        orderSteps.deletionIconClick();
    }
}
