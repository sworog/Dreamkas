package project.lighthouse.autotests.steps.pos;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.json.JSONObject;
import org.junit.Assert;
import org.openqa.selenium.By;
import project.lighthouse.autotests.api.http.HttpExecutor;
import project.lighthouse.autotests.collection.receipt.ReceiptCollection;
import project.lighthouse.autotests.collection.receipt.ReceiptObject;
import project.lighthouse.autotests.collection.refund.RefundProduct;
import project.lighthouse.autotests.common.item.interfaces.Collectable;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.pages.pos.*;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.containers.user.UserContainer;

import java.io.IOException;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class PosSteps extends ScenarioSteps {

    PosLaunchPage posLaunchPage;
    PosPage posPage;
    ReceiptPositionEditModalWindow receiptPositionEditModalWindow;
    ReceiptModalPage receiptModalPage;
    RefundModalWindowPage refundModalWindowPage;
    ReceiptElement receiptElement;

    @Step
    public void choosePosConfirmation() {
        posLaunchPage.addObjectButtonClick();
    }

    @Step
    public void navigateToPosPage(String storeName) {
        String storeId = Storage.getCustomVariableStorage().getStores().get(storeName).getId();
        String posUrl = String.format("%s/pos/stores/%s", UrlHelper.getWebFrontUrl(), storeId);
        posLaunchPage.getDriver().navigate().to(posUrl);
    }

    @Step
    public void navigateToPosSalesPage(String storeName) {
        String storeId = Storage.getCustomVariableStorage().getStores().get(storeName).getId();
        String posUrl = String.format("%s/pos/stores/%s/sales", UrlHelper.getWebFrontUrl(), storeId);
        posLaunchPage.getDriver().navigate().to(posUrl);
    }

    @Step
    public void checkPostAutoCompleteCollectionContainsNoResults() {
        posPage.shouldContainsText("Для поиска товара введите 3 или более символа.");
    }

    @Step
    public void checkReceiptCollectionContainsNoResults() {
        posPage.shouldContainsText("Для продажи добавьте в чек хотя бы один продукт.");
    }

    @Step
    public void exactComparePosAutocompleteResultsCollectionWithExamplesTable(ExamplesTable examplesTable) {
        posPage.exactCompareExampleTable(examplesTable);
    }

    @Step
    public void exactCompareReceiptCollectionWithExamplesTable(ExamplesTable examplesTable) {
        ((Collectable)posPage.getItems().get("receiptCollection")).exactCompareExampleTable(examplesTable);
    }

    @Step
    public void receiptObjectClickByLocator(String name) {
        ((Collectable)posPage.getItems().get("receiptCollection")).clickByLocator(name);
    }

    @Step
    public void assertReceiptTotalSum(String totalSum) {
        posPage.checkValue("totalPrice", totalSum);
    }

    @Step
    public void checkTheLastAddedProductIsPinned() {
        // shitty
        // get the total price text y location
        Integer totalPriceY = posPage.findVisibleElement(By.name("totalPrice")).getLocation().getY();
        // get the last added product y location in receipt
        Integer receiptLastPinnedProductY = ((ReceiptObject) ((ReceiptCollection)posPage.getItems().get("receiptCollection")).get(19)).getElement().getLocation().getY();
        // assert
        assertThat(true, is(receiptLastPinnedProductY >= 802 && receiptLastPinnedProductY < totalPriceY));
    }

    @Step
    public void clickOnPlusButton() {
        receiptPositionEditModalWindow.clickOnCommonItemWihName("plusButton");
    }

    @Step
    public void clickOnMinusButton() {
        receiptPositionEditModalWindow.clickOnCommonItemWihName("minusButton");
    }

    @Step
    public void clearReceipt() {
        posPage.clickOnCommonItemWihName("clearReceipt");
    }

    @Step
    public void confirmClearReceipt() {
        posPage.clickOnCommonItemWihName("confirmClearReceipt");
    }

    @Step
    public void clickOnRegisterSaleButton() {
        posPage.clickOnCommonItemWihName("registerSaleButton");
    }

    @Step
    public void clickOnContinueButton() {
        receiptModalPage.clickOnCommonItemWihName("continueButton");
    }

    @Step
    public void clickOnRefundContinueButton() {
        refundModalWindowPage.clickOnCommonItemWihName("continueButton");
    }

    @Step
    public void assertStoreProductInventory(String email, String storeName, String productName, String inventory) throws IOException, JSONException {
        UserContainer userContainer = Storage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email);
        String storeId = Storage.getCustomVariableStorage().getStores().get(storeName).getId();
        String productId = Storage.getCustomVariableStorage().getProducts().get(productName).getId();
        String url = String.format("%s/api/1/stores/%s/products/%s", UrlHelper.getApiUrl(), storeId, productId);
        String response = HttpExecutor.getHttpRequestable(userContainer.getEmail(), userContainer.getPassword()).executeGetRequest(url);
        JSONObject jsonObject = new JSONObject(response);
        assertThat(jsonObject.getString("inventory"), is(inventory));
    }

    @Step
    public void assertSuccessTitle(String value) {
        String expected = String.format("Выдайте сдачу\n%s", value);
        receiptModalPage.checkValue("заголовок успешной продажи", expected);
    }

    @Step
    public void assertRefundSuccessTitle(String value) {
        String expected = String.format("Выдайте деньги\n%s", value);
        refundModalWindowPage.checkValue("заголовок успешного возврата", expected);
    }

    @Step
    public void assertRefundSuccessBankTitle(String value) {
        String expected = String.format("Сделайте возврат на банковскую карту\n%s", value);
        refundModalWindowPage.checkValue("заголовок успешного возврата", expected);
    }

    @Step
    public void openPosLaunchPage() {
        posLaunchPage.open();
    }

    @Step
    public void assertCashRegistrySideMenuLinkIsActive() {
        if (!posPage.getCommonItemAttributeValue("posSideMenuLink", "class").contains("active")) {
            Assert.fail("Ссылка 'Касса' не активна в боковом меню навигации кассы");
        }
    }

    @Step
    public void clickOnSaleHistorySideMenuLink() {
        posPage.clickOnCommonItemWihName("saleHistorySideMenuLink");
    }

    @Step
    public void clickOnChangeStoreSideMenuLink() {
        posPage.clickOnCommonItemWihName("changeStoreSideMenuLink");
    }

    @Step
    public void clickOnSideMenuInteractionButton() {
        posPage.clickOnCommonItemWihName("sideBar");
    }

    @Step
    public void setRefundProductQuantityByName(String name, String quantity) {
        ((RefundProduct) ((Collectable)refundModalWindowPage.getItems().get("defaultCollection")).getAbstractObjectByLocator(name)).setQuantity(quantity);
    }

    @Step
    public void assertRefundPorductQuantity(String name, String quantity) {
        String actualQuantity =
                ((RefundProduct) ((Collectable)refundModalWindowPage.getItems().get("defaultCollection")).getAbstractObjectByLocator(name)).getQuantity();
        assertThat(actualQuantity, is(quantity));
    }

    @Step
    public void clickOnRefundProductPlusButtonByName(String name) {
        ((RefundProduct) ((Collectable)refundModalWindowPage.getItems().get("defaultCollection")).getAbstractObjectByLocator(name)).clickOnPlusButton();
    }

    @Step
    public void clickOnRefundProductMinusButtonByName(String name) {
        ((RefundProduct) ((Collectable)refundModalWindowPage.getItems().get("defaultCollection")).getAbstractObjectByLocator(name)).clickOnMinusButton();
    }

    @Step
    public void clickOnRefundButton() {
        posPage.clickOnCommonItemWihName("refundButton");
    }
}
