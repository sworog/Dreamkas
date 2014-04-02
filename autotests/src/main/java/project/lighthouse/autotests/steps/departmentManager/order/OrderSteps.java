package project.lighthouse.autotests.steps.departmentManager.order;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.junit.Assert;
import org.openqa.selenium.TimeoutException;
import project.lighthouse.autotests.elements.Buttons.LinkFacade;
import project.lighthouse.autotests.elements.preLoader.PreLoader;
import project.lighthouse.autotests.helper.exampleTable.order.OrderExampleTableUpdater;
import project.lighthouse.autotests.objects.web.order.order.OrderObjectCollection;
import project.lighthouse.autotests.objects.web.order.orderProduct.OrderProductObject;
import project.lighthouse.autotests.pages.departmentManager.order.OrderPage;
import project.lighthouse.autotests.pages.departmentManager.order.OrdersListPage;
import project.lighthouse.autotests.storage.Storage;

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
    public void input(String elementName, String value) {
        orderPage.input(elementName, value);
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
    public void orderProductCollectionObjectQuantityType(String locator, String value) {
        OrderProductObject orderProductObject =
                (OrderProductObject) orderPage.getOrderProductObjectCollection().getAbstractObjectByLocator(locator);
        orderProductObject.quantityType(value);
    }

    @Step
    public void lastCreatedOrderProductCollectionObjectQuantityType(String value) throws JSONException {
        orderProductCollectionObjectQuantityType(
                Storage.getOrderVariableStorage().getProduct().getName(),
                value);
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
        orderPage.getSaveButton().click();
        new PreLoader(getDriver()).await();
    }

    @Step
    public void saveButtonShouldBeNotVisible() {
        orderPage.getSaveButton().shouldBeNotVisible();
    }

    @Step
    public void saveButtonShouldBeVisible() {
        orderPage.getSaveButton().shouldBeVisible();
    }

    @Step
    public void orderAcceptButtonShouldBeVisible() {
        orderPage.getOrderAcceptButton().shouldBeVisible();
    }

    @Step
    public void orderAcceptButtonShouldBeNotVisible() {
        orderPage.getOrderAcceptButton().shouldBeNotVisible();
    }

    @Step
    public void cancelLinkClick() {
        orderPage.getCancelLink().click();
    }

    @Step
    public void cancelLinkShouldBeNotVisible() {
        orderPage.getCancelLink().shouldBeNotVisible();
    }

    @Step
    public void cancelLinkShouldBeVisible() {
        orderPage.getCancelLink().shouldBeVisible();
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
    public void assertExactOrderCollectionValues(ExamplesTable examplesTable) throws JSONException {
        ExamplesTable updatedExamplesTable = new OrderExampleTableUpdater(examplesTable).updateValues();
        ordersListPage.getOrderObjectCollection().exactCompareExampleTable(updatedExamplesTable);
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
        openOrderWithNameObjectClick(
                Storage.getOrderVariableStorage().getNumber());
    }

    @Step
    public void openOrderWithNameObjectClick(String locator) {
        ordersListPage.getOrderObjectCollection().clickByLocator(locator);
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

    @Step
    public void assertOrderNumberHeader() {
        String expectedNumber = String.format("Заказ поставщику № %s", Storage.getOrderVariableStorage().getNumber());
        assertThat(
                orderPage.getOrderNumberHeaderText(),
                equalTo(expectedNumber));
    }

    @Step
    public void assertSaveControlsText(String expectedText) {
        assertThat(
                orderPage.getSaveControlsText(),
                equalTo(expectedText)
        );
    }

    @Step
    public void assertDownloadFileLinkIsClickable() {
        LinkFacade linkFacade = orderPage.getDownloadFileLink();
        orderPage.getWaiter().elementToBeClickable(linkFacade.getFindBy());
    }

    @Step
    public void assertDownloadFileLinkIsNotVisible() {
        try {
            orderPage.getDownloadFileLink().click();
        } catch (TimeoutException ignored) {
        }
    }
}
