package project.lighthouse.autotests.steps.commercialManager;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.objects.Store;
import project.lighthouse.autotests.pages.commercialManager.store.StoreCardPage;
import project.lighthouse.autotests.pages.commercialManager.store.StoreCreatePage;
import project.lighthouse.autotests.pages.commercialManager.store.StoreListPage;

import java.util.Map;

public class StoreSteps extends ScenarioSteps {

    StoreCreatePage storeCreatePage;
    StoreListPage storeListPage;
    StoreCardPage storeCardPage;

    public StoreSteps(Pages pages) {
        super(pages);
    }

    @Step
    public void navigateToCreateStorePage() {
        storeCreatePage.open();
    }

    @Step
    public void clickCreateNewStoreButton() {
        storeListPage.createNewStoreButton().click();
    }

    @Step
    public void navigateToStoresListPage() {
        storeListPage.open();
    }

    @Step
    public void clickCreateStoreSubmitButton() {
        storeCreatePage.submitButton().click();
    }

    @Step
    public void fillStoreFormData(ExamplesTable formData) {
        storeCreatePage.inputTable(formData);
    }

    @Step
    public void checkStoreDataInList(ExamplesTable storeData) {
        for (Map<String,String> column : storeData.getRows()) {
            storeListPage.findModelFieldContaining(Store.NAME, column.get("elementName"), column.get("value"));
        }
    }

    @Step
    public void clickOnStoreRowInList(String storeNumber) {
        storeListPage.findStoreRowInList(storeNumber).click();
    }

    @Step
    public void checkStoreCardData(ExamplesTable storeData) {
        for (Map<String, String> row : storeData.getRows()) {
            String columnName = row.get("elementName");
            String columnValue = row.get("value");
            if (columnName.equals("number")) {
                storeCardPage.checkStoreCardHeader(columnValue);
            }
            storeCardPage.checkStoreCardValue(columnName, columnValue);
        }

    }
}
