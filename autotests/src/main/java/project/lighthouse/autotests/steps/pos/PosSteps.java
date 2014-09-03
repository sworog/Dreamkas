package project.lighthouse.autotests.steps.pos;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.TimeoutException;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.objects.web.posAutoComplete.PosAutoCompleteCollection;
import project.lighthouse.autotests.objects.web.receipt.ReceiptCollection;
import project.lighthouse.autotests.pages.pos.PosLaunchPage;
import project.lighthouse.autotests.pages.pos.PosPage;
import project.lighthouse.autotests.storage.Storage;

import static org.hamcrest.Matchers.is;
import static org.hamcrest.Matchers.nullValue;
import static org.junit.Assert.assertThat;

public class PosSteps extends ScenarioSteps {

    PosLaunchPage posLaunchPage;
    PosPage posPage;

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

    public ReceiptCollection ReceiptCollection() {
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
    public void checkNoResults() {
        assertThat(getPosAutoCompleteCollection(), nullValue());
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
        ReceiptCollection receiptCollection = ReceiptCollection();
        if (receiptCollection != null) {
            receiptCollection.exactCompareExampleTable(examplesTable);
        }
    }

    @Step
    public void assertReceiptTotalSum(String totalSum) {
        assertThat(posPage.getReceiptTotalSum(), is(totalSum));
    }
}
