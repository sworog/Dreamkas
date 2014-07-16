package project.lighthouse.autotests.steps.catalog;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.catalog.CatalogPage;
import project.lighthouse.autotests.pages.catalog.modal.CreateGroupModalPage;
import project.lighthouse.autotests.pages.catalog.modal.DeleteGroupModalPage;
import project.lighthouse.autotests.pages.catalog.modal.EditGroupModalPage;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class CatalogSteps extends ScenarioSteps {

    CatalogPage catalogPage;
    CreateGroupModalPage createGroupModalPage;
    EditGroupModalPage editGroupModalPage;
    DeleteGroupModalPage deleteGroupModalPage;

    @Step
    public void openCatalogPage() {
        catalogPage.open();
    }

    @Step
    public void addGroupButtonClick() {
        catalogPage.addGroupButtonClick();
    }

    @Step
    public void assertCatalogTitle(String catalogTitle) {
        assertThat(catalogPage.getTitle(), is(catalogTitle));
    }

    @Step
    public void assertGroupTitle(String groupTitle) {
        assertThat(catalogPage.getGroupTitle(), is(groupTitle));
    }

    @Step
    public void groupCollectionContainsGroupWithName(String groupName) {
        //try catch to prevent exception if there is no groups
        catalogPage.getGroupObjectCollection().contains(groupName);
    }

    @Step
    public void groupCollectionNotContainGroupWithName(String groupName) {
        catalogPage.getGroupObjectCollection().notContains(groupName);
    }

    @Step
    public void groupWithNameClick(String groupName) {
        catalogPage.getGroupObjectCollection().clickByLocator(groupName);
    }

    @Step
    public void createGroupModalPageNameInput(String name) {
        createGroupModalPage.input("name", name);
    }

    @Step
    public void assertCreateGroupModalPageTitle(String title) {
        assertThat(createGroupModalPage.getTitleText(), is(title));
    }

    @Step
    public void createGroupModalPageConfirmOk() {
        createGroupModalPage.confirmationOkClick();
    }

    @Step
    public void createGroupModalPageConfirmCancel() {
        createGroupModalPage.confirmationCancelClick();
    }

    @Step
    public void editGroupModalPageNameInput(String name) {
        editGroupModalPage.input("name", name);
    }

    @Step
    public void assertEditGroupModalPageTitle(String title) {
        assertThat(editGroupModalPage.getTitleText(), is(title));
    }

    @Step
    public void editGroupModalPageConfirmOk() {
        editGroupModalPage.confirmationOkClick();
    }

    @Step
    public void editGroupModalPageConfirmCancel() {
        editGroupModalPage.confirmationCancelClick();
    }

    @Step
    public void editGroupModalPageDeleteGroupButtonClick() {
        editGroupModalPage.deleteButtonClick();
    }

    @Step
    public void assertDeleteGroupModalPageTitle(String title) {
        assertThat(deleteGroupModalPage.getTitleText(), is(title));
    }

    @Step
    public void deleteGroupModalPageConfirmOk() {
        deleteGroupModalPage.confirmationOkClick();
    }

    @Step
    public void deleteGroupModalPageConfirmCancel() {
        deleteGroupModalPage.confirmationCancelClick();
    }
}
