package project.lighthouse.autotests.jbehave.departmentManager.order;

import net.thucydides.core.annotations.Steps;
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

    @When("the user clicks on the order product by name '$name'")
    public void whenTheUserClicksOnTheOrderProductByName(String name) {
        orderSteps.orderProductCollectionObjectClickByLocator(name);
    }

    @When("the user clicks on last created order on the orders list page")
    public void whenTheUserClicksOnLastCreatedOrderOnTheOrdersListPage() {
        orderSteps.lastCreatedOrderCollectionObjectClick();
    }

    @When("the user clicks on deletion item icon to delete edited order product")
    public void whenTheUserClicksOnDeletionItemIconToDeleteEditedOrderProduct() {
        orderSteps.deletionIconClick();
    }
}
