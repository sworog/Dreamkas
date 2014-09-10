package project.lighthouse.autotests.steps.deprecated.commercialManager;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.deprecated.commercialManager.store.StoreCardPage;
import project.lighthouse.autotests.pages.deprecated.commercialManager.store.StoreCreatePage;
import project.lighthouse.autotests.pages.deprecated.commercialManager.store.StoreListPage;

public class StoreSteps extends ScenarioSteps {

    StoreCreatePage storeCreatePage;
    StoreListPage storeListPage;
    StoreCardPage storeCardPage;

    @Step
    public void navigateToCreateStorePage() {
        storeCreatePage.open();
    }

    @Step
    public void clickCreateNewStoreButton() {
        storeListPage.createNewStoreButtonClick();
    }

    @Step
    public void navigateToStoresListPage() {
        storeListPage.open();
    }

    @Step
    public void clickCreateStoreSubmitButton() {
        storeCreatePage.createButtonClick();
    }

    @Step
    public void clickSaveStoreSubmitButton() {
        storeCreatePage.saveButtonClick();
    }

    @Step
    public void fillStoreFormData(ExamplesTable formData) {
        storeCreatePage.input(formData);
    }

    @Step
    public void clickOnStoreRowInList(String storeNumber) {
        storeListPage.findStoreRowInList(storeNumber).click();
    }

    @Step
    public void navigateToStorePage(String id) {
        storeCardPage.navigateToStoreCardPage(id);
    }

    @Step
    public void userClicksEditButtonOnStoreCardPage() {
        storeCardPage.editButton().click();
    }

    @Step
    public void checkPromotedStoreManager(String storeManager) {
        storeCardPage.checkPromotedStoreManager(storeManager);
    }

    @Step
    public void checkPromotedStoreManagerIsNotPresent(String storeManager) {
        storeCardPage.checkPromotedStoreManagerIsNotPresent(storeManager);
    }

    @Step
    public void unPromoteStoreManager(String storeManager) {
        storeCardPage.unPromoteStoreManager(storeManager);
    }

    @Step
    public void promoteStoreManager(String storeManager) {
        storeCardPage.promoteStoreManager(storeManager);
    }

    @Step
    public void promoteNotStoreManager(String notStoreManager) {
        storeCardPage.promoteNotStoreManager(notStoreManager);
    }

    @Step
    public void checkStoreNumber(String number) {
        storeCardPage.checkStoreCardHeader(number);
    }

    @Step
    public void checkPromotedDepartmentManager(String departmentManager) {
        storeCardPage.checkPromotedDepartmentManager(departmentManager);
    }

    @Step
    public void checkPromotedDepartmentManagerIsNotPresent(String departmentManager) {
        storeCardPage.checkPromotedDepartmentManagerIsNotPresent(departmentManager);
    }

    @Step
    public void unPromoteDepartmentManager(String departmentManager) {
        storeCardPage.unPromoteDepartmentManager(departmentManager);
    }

    @Step
    public void promoteDepartmentManager(String departmentManager) {
        storeCardPage.promoteDepartmentManager(departmentManager);
    }

    @Step
    public void promoteNotDepartmentManager(String notDepartmentManager) {
        storeCardPage.promoteNotDepartmentManager(notDepartmentManager);
    }
}
