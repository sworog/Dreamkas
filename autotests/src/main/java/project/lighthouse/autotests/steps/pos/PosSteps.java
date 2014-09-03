package project.lighthouse.autotests.steps.pos;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.TimeoutException;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.objects.web.posAutoComplete.PosAutoCompleteCollection;
import project.lighthouse.autotests.pages.pos.PosLaunchPage;
import project.lighthouse.autotests.pages.pos.PosPage;
import project.lighthouse.autotests.storage.Storage;

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

    @Step
    public void checkNoResults() {
        assertThat(getPosAutoCompleteCollection(), nullValue());
    }

    @Step
    public void compareWithExamplesTable(ExamplesTable examplesTable) {
        PosAutoCompleteCollection posAutoCompeteResults = getPosAutoCompleteCollection();
        if (posAutoCompeteResults != null) {
            getPosAutoCompleteCollection().compareWithExampleTable(examplesTable);
        }
    }

    @Step
    public void exactCompareWithExamplesTable(ExamplesTable examplesTable) {
        PosAutoCompleteCollection posAutoCompeteResults = getPosAutoCompleteCollection();
        if (posAutoCompeteResults != null) {
            getPosAutoCompleteCollection().exactCompareExampleTable(examplesTable);
        }
    }
}
