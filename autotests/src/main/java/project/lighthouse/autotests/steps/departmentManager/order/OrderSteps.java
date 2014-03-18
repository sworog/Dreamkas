package project.lighthouse.autotests.steps.departmentManager.order;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.junit.Assert;
import project.lighthouse.autotests.objects.web.order.orderProduct.OrderProductObject;
import project.lighthouse.autotests.pages.departmentManager.order.OrderPage;
import project.lighthouse.autotests.pages.departmentManager.order.OrdersListPage;

import static org.hamcrest.Matchers.equalTo;
import static org.junit.Assert.assertThat;

public class OrderSteps extends ScenarioSteps {

    OrderPage orderPage;
    OrdersListPage ordersListPage;

    @Step
    public void openOrderCreatePage() {
        orderPage.open();
    }

    @Step
    public void input(ExamplesTable examplesTable) {
        orderPage.fieldInput(examplesTable);
    }

    @Step
    public void additionFormInput(ExamplesTable examplesTable) {
        orderPage.getProductAdditionForm().fieldInput(examplesTable);
    }

    @Step
    public void additionFormInput(String elementName, String value) {
        orderPage.getProductAdditionForm().input(elementName, value);
    }

    @Step
    public void productCollectionExactCompare(ExamplesTable examplesTable) {
        orderPage.getOrderProductObjectCollection().exactCompareExampleTable(examplesTable);
    }

    @Step
    public void assertTotalSum(String totalSumValue) {
        assertThat(
                orderPage.getOrderTotalSumText(),
                equalTo(totalSumValue)
        );
    }

    @Step
    public void saveButtonClick() {
        orderPage.saveButtonClick();
    }

    @Step
    public void checksValues(ExamplesTable examplesTable) {
        orderPage.getProductAdditionForm().checkCardValue(examplesTable);
    }

    @Step
    public void additionFormCheckValues(ExamplesTable examplesTable) {
        orderPage.getProductAdditionForm().checkValues(examplesTable);
    }

    @Step
    public void assertFieldLabelTitle(String elementName) {
        orderPage.checkFieldLabel(elementName);
    }

    @Step
    public void assertAdditionProductFormLabelTitle(String elementName) {
        orderPage.getProductAdditionForm().checkFieldLabel(elementName);
    }

    @Step
    public void addProductToOrderButtonClick() {
        orderPage.getProductAdditionForm().addButtonClick();
    }

    @Step
    public void openOrdersListPage() {
        ordersListPage.open();
    }

    @Step
    public void assertOrderProductObjectQuantity(String locator, String expectedQuantity) {
        OrderProductObject orderProductObject =
                (OrderProductObject) orderPage.getOrderProductObjectCollection().getAbstractObjectByLocator(locator);
        Assert.assertThat(orderProductObject.getQuantity(), equalTo(expectedQuantity));
    }
}
