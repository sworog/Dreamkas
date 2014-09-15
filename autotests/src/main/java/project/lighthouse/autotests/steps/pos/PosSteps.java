package project.lighthouse.autotests.steps.pos;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.json.JSONObject;
import org.openqa.selenium.By;
import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.TimeoutException;
import project.lighthouse.autotests.api.http.HttpExecutor;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.objects.web.posAutoComplete.PosAutoCompleteCollection;
import project.lighthouse.autotests.objects.web.receipt.ReceiptCollection;
import project.lighthouse.autotests.objects.web.receipt.ReceiptObject;
import project.lighthouse.autotests.pages.pos.PosLaunchPage;
import project.lighthouse.autotests.pages.pos.PosPage;
import project.lighthouse.autotests.pages.pos.ReceiptModalPage;
import project.lighthouse.autotests.pages.pos.ReceiptPositionEditModalWindow;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.containers.user.UserContainer;

import java.io.IOException;

import static org.hamcrest.Matchers.is;
import static org.hamcrest.Matchers.nullValue;
import static org.junit.Assert.assertThat;

public class PosSteps extends ScenarioSteps {

    PosLaunchPage posLaunchPage;
    PosPage posPage;
    ReceiptPositionEditModalWindow receiptPositionEditModalWindow;
    ReceiptModalPage receiptModalPage;

    @Step
    public void choosePosConfirmation() {
        posLaunchPage.addObjectButtonClick();
    }

    @Step
    public void navigateToPosPage(String storeName) {
        String storeId = Storage.getCustomVariableStorage().getStores().get(storeName).getId();
        String posUrl = String.format("%s/pos/store/%s", UrlHelper.getWebFrontUrl(), storeId);
        posLaunchPage.getDriver().navigate().to(posUrl);
    }

    public PosAutoCompleteCollection getPosAutoCompleteCollection() {
        PosAutoCompleteCollection abstractObjectCollection = null;
        try {
            abstractObjectCollection = posPage.getObjectCollection();
        } catch (StaleElementReferenceException e) {
            abstractObjectCollection = posPage.getObjectCollection();
        } catch (TimeoutException e) {
            posPage.containsText("Для поиска товара введите 3 или более символа.");
        }
        return abstractObjectCollection;
    }

    public ReceiptCollection getReceiptCollection() {
        ReceiptCollection receiptCollection = null;
        try {
            receiptCollection = posPage.getReceiptCollection();
        } catch (StaleElementReferenceException e) {
            receiptCollection = posPage.getReceiptCollection();
        } catch (TimeoutException e) {
            posPage.containsText("Для продажи добавьте в чек хотя бы один продукт.");
        }
        return receiptCollection;
    }

    @Step
    public void checkPostAutoCompleteCollectionContainsNoResults() {
        assertThat(getPosAutoCompleteCollection(), nullValue());
    }

    @Step
    public void checkReceiptCollectionContainsNoResults() {
        assertThat(getReceiptCollection(), nullValue());
    }

    @Step
    public void exactComparePosAutocompleteResultsCollectionWithExamplesTable(ExamplesTable examplesTable) {
        PosAutoCompleteCollection posAutoCompeteResults = getPosAutoCompleteCollection();
        if (posAutoCompeteResults != null) {
            posAutoCompeteResults.exactCompareExampleTable(examplesTable);
        }
    }

    @Step
    public void exactCompareReceiptCollectionWithExamplesTable(ExamplesTable examplesTable) {
        ReceiptCollection receiptCollection = getReceiptCollection();
        if (receiptCollection != null) {
            receiptCollection.exactCompareExampleTable(examplesTable);
        }
    }

    @Step
    public void receiptObjectClickByLocator(String name) {
        getReceiptCollection().clickByLocator(name);
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
        Integer receiptLastPinnedProductY = ((ReceiptObject) getReceiptCollection().get(19)).getElement().getLocation().getY();
        // assert
        assertThat(true, is(receiptLastPinnedProductY >= 802 && receiptLastPinnedProductY < totalPriceY));
    }

    @Step
    public void clickOnPlusButton() {
        receiptPositionEditModalWindow.clickOnPlusButton();
    }

    @Step
    public void clickOnMinusButton() {
        receiptPositionEditModalWindow.clickOnMinusButton();
    }

    @Step
    public void clearReceipt() {
        posPage.clearReceipt();
    }

    @Step
    public void confirmClearReceipt() {
        posPage.confirmClearReceipt();
    }

    @Step
    public void clickOnRegisterSaleButton() {
        posPage.clickOnRegisterSaleButton();
    }

    @Step
    public void clickOnContinueButton() {
        receiptModalPage.clickOnContinueButton();
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
}
