package project.lighthouse.autotests.steps.departmentManager.order;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.junit.Assert;
import org.openqa.selenium.TimeoutException;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.helper.exampleTable.order.OrderExampleTableUpdater;
import project.lighthouse.autotests.objects.web.order.order.OrderObjectCollection;
import project.lighthouse.autotests.objects.web.order.orderProduct.OrderProductObject;
import project.lighthouse.autotests.pages.departmentManager.order.OrderPage;
import project.lighthouse.autotests.pages.departmentManager.order.OrdersListPage;
import project.lighthouse.autotests.storage.Storage;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

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
    public void checkOrderPageValues(ExamplesTable examplesTable) {
        orderPage.checkCardValue(examplesTable);
    }

    @Step
    public void additionFormInput(ExamplesTable examplesTable) {
        orderPage.getProductAdditionForm().fieldInput(examplesTable);
    }

    @Step
    public void editionFormInput(ExamplesTable examplesTable) {
        orderPage.getProductEditionForm().fieldInput(examplesTable);
    }

    @Step
    public void additionFormInput(String elementName, String value) {
        orderPage.getProductAdditionForm().input(elementName, value);
    }

    @Step
    public void editionFormInput(String elementName, String value) {
        orderPage.getProductEditionForm().input(elementName, value);
    }

    @Step
    public void productCollectionExactCompare(ExamplesTable examplesTable) throws JSONException {
        ExamplesTable updatedExampleTable = new OrderExampleTableUpdater(examplesTable).updateValues();
        orderPage.getOrderProductObjectCollection().exactCompareExampleTable(updatedExampleTable);
    }

    @Step
    public void orderProductCollectionObjectClickByLocator(String locator) {
        orderPage.getOrderProductObjectCollection().clickByLocator(locator);
    }

    @Step
    public void lastCreatedOrderProductCollectionObjectClickByLocator() throws JSONException {
        orderProductCollectionObjectClickByLocator(
                Storage.getOrderVariableStorage().getProduct().getName());
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
    public void cancelLinkClick() {
        orderPage.cancelLinkClick();
    }

    @Step
    public void deleteButtonClickAndConfirmTheDeletion() {
        orderPage.deleteButtonClick();
        orderPage.getWaiter().getAlert().accept();
    }

    @Step
    public void deleteButtonClickAndDismissTheDeletion() {
        orderPage.deleteButtonClick();
        orderPage.getWaiter().getAlert().dismiss();
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
    public void editOrderProductButtonClick() {
        orderPage.getProductEditionForm().editButtonClick();
    }

    @Step
    public void cancelOrderProductButtonClick() {
        orderPage.getProductEditionForm().cancelLinkClick();
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

    @Step
    public void assertOrderProductObjectQuantity(String expectedQuantity) throws JSONException {
        assertOrderProductObjectQuantity(
                Storage.getOrderVariableStorage().getProduct().getName(),
                expectedQuantity);
    }

    @Step
    public void assertExactOrderCollectionValues(ExamplesTable examplesTable) {
        ordersListPage.getOrderObjectCollection().exactCompareExampleTable(examplesTable);
    }

    @Step
    public void assertOrderCollectionValues(ExamplesTable examplesTable) throws JSONException {
        ExamplesTable updatedExampleTable = new OrderExampleTableUpdater(examplesTable).updateValues();
        ordersListPage.getOrderObjectCollection().compareWithExampleTable(updatedExampleTable);
    }

    @Step
    public void assertOrderCollectionDoNotContainOrderWithNumber(String locator) {
        OrderObjectCollection orderObjectCollection = null;
        try {
            orderObjectCollection = ordersListPage.getOrderObjectCollection();
        } catch (TimeoutException e) {
            ordersListPage.containsText("Нет невыполненных заказов");
        } finally {
            if (orderObjectCollection != null) {
                orderObjectCollection.notContains(locator);
            }
        }
    }

    @Step
    public void assertOrderCollectionDoNotContainLastCreatedOrder() {
        assertOrderCollectionDoNotContainOrderWithNumber(Storage.getOrderVariableStorage().getNumber());
    }

    @Step
    public void lastCreatedOrderCollectionObjectClick() {
        ordersListPage.getOrderObjectCollection().clickByLocator(
                Storage.getOrderVariableStorage().getNumber());
    }

    @Step
    public void assertOrderCollectionValues() throws JSONException {
        List<Map<String, String>> mapList = new ArrayList<Map<String, String>>() {
            {
                add(new HashMap<String, String>() {
                    {
                        put("number", "10001");
                        put("supplier", Storage.getOrderVariableStorage().getSupplier().getName());
                        put("date", new DateTimeHelper(0).convertDateByPattern("dd.MM.yyyy"));
                    }
                });
            }
        };
        assertExactOrderCollectionValues(
                new ExamplesTable("").withRows(mapList));
    }

    @Step
    public void assertAnotherOrderCollectionValues() throws JSONException {
        List<Map<String, String>> mapList = new ArrayList<Map<String, String>>() {
            {
                add(new HashMap<String, String>() {
                    {
                        put("number", "10002");
                        put("supplier", "supplier-s30u64s1");
                        put("date", new DateTimeHelper(0).convertDateByPattern("dd.MM.yyyy"));
                    }
                });
                add(new HashMap<String, String>() {
                    {
                        put("number", "10001");
                        put("supplier", Storage.getOrderVariableStorage().getSupplier().getName());
                        put("date", new DateTimeHelper(0).convertDateByPattern("dd.MM.yyyy"));
                    }
                });
            }
        };
        assertExactOrderCollectionValues(
                new ExamplesTable("").withRows(mapList));
    }

    @Step
    public void assertOrderProductCollectionDoNotContainsProduct(String locator) {
        try {
            orderPage.getOrderProductObjectCollection().notContains(locator);
        } catch (TimeoutException ignored) {
        }
    }

    @Step
    public void assertOrderProductCollectionDoNotContainsProduct() throws JSONException {
        try {
            assertOrderProductCollectionDoNotContainsProduct(
                    Storage.getOrderVariableStorage().getProduct().getName());
        } catch (TimeoutException ignored) {
        }
    }

    @Step
    public void deletionIconClick() {
        orderPage.getProductEditionForm().deletionIconClick();
    }
}
