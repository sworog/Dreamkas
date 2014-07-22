package project.lighthouse.autotests.steps.catalog;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.TimeoutException;
import project.lighthouse.autotests.elements.bootstrap.SimplePreloader;
import project.lighthouse.autotests.elements.bootstrap.WaitForModalWindowClose;
import project.lighthouse.autotests.elements.preLoader.BodyPreLoader;
import project.lighthouse.autotests.helper.StringGenerator;
import project.lighthouse.autotests.objects.web.catalog.GroupObjectCollection;
import project.lighthouse.autotests.pages.catalog.CatalogPage;
import project.lighthouse.autotests.pages.catalog.GroupPage;
import project.lighthouse.autotests.pages.catalog.modal.CreateGroupModalPage;
import project.lighthouse.autotests.pages.catalog.modal.EditGroupModalPage;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class CatalogSteps extends ScenarioSteps {

    CatalogPage catalogPage;
    CreateGroupModalPage createGroupModalPage;
    EditGroupModalPage editGroupModalPage;
    GroupPage groupPage;

    private String storedName;

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
        assertThat(groupPage.getGroupTitle(), is(groupTitle));
    }

    @Step
    public void groupCollectionContainsGroupWithName(String groupName) {
        GroupObjectCollection orderObjectCollection = null;
        try {
            orderObjectCollection = catalogPage.getGroupObjectCollection();
        } catch (TimeoutException e) {
            catalogPage.containsText("У вас пока нет ни одной группы товаров.");
        } catch (StaleElementReferenceException e) {
            orderObjectCollection = catalogPage.getGroupObjectCollection();
        } finally {
            if (orderObjectCollection != null) {
                orderObjectCollection.contains(groupName);
            }
        }
    }

    @Step
    public void groupCollectionContainsGroupWithStoredName() {
        groupCollectionContainsGroupWithName(storedName);
    }

    @Step
    public void groupCollectionNotContainGroupWithName(String groupName) {
        GroupObjectCollection orderObjectCollection = null;
        try {
            orderObjectCollection = catalogPage.getGroupObjectCollection();
        } catch (TimeoutException e) {
            catalogPage.containsText("У вас пока нет ни одной группы товаров.");
        } catch (StaleElementReferenceException e) {
            orderObjectCollection = catalogPage.getGroupObjectCollection();
        } finally {
            if (orderObjectCollection != null) {
                orderObjectCollection.notContains(groupName);
            }
        }
    }

    @Step
    public void groupWithNameClick(String groupName) {
        catalogPage.getGroupObjectCollection().clickByLocator(groupName);
        new BodyPreLoader(getDriver()).await();
    }

    @Step
    public void createGroupModalPageNameInput(String name) {
        createGroupModalPage.input("name", name);
    }

    @Step
    public void createGroupModalPageNameInputGenerate(int count) {
        String generatedString = new StringGenerator(count).generateTestData();
        createGroupModalPage.input("name", generatedString);
        storedName = generatedString;
    }

    @Step
    public void editGroupModalPageNameInputGenerate(int count) {
        String generatedString = new StringGenerator(count).generateString("b");
        editGroupModalPage.input("name", generatedString);
        storedName = generatedString;
    }

    @Step
    public void assertCreateGroupModalPageTitle(String title) {
        assertThat(createGroupModalPage.getTitleText(), is(title));
    }

    @Step
    public void createGroupModalPageConfirmOk() {
        createGroupModalPage.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void createGroupModalPageCloseIconClick() {
        createGroupModalPage.closeIconClick();
        new WaitForModalWindowClose(getDriver()).await();
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
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void editGroupModalPageCloseIconClick() {
        editGroupModalPage.closeIconClick();
        new WaitForModalWindowClose(getDriver()).await();
    }

    @Step
    public void editGroupModalPageDeleteGroupButtonClick() {
        editGroupModalPage.deleteButtonClick();
    }

    @Step
    public void editGroupModalPageDeleteGroupConfirmButtonClick() {
        editGroupModalPage.deleteButtonConfirmClick();
        new SimplePreloader(getDriver()).await();
        new WaitForModalWindowClose(getDriver()).await();
        new BodyPreLoader(getDriver()).await();
    }

    @Step
    public void editGroupIconClick() {
        groupPage.editGroupIconClick();
    }

    @Step
    public void backArrowButtonClick() {
        groupPage.longArrowBackLinkClick();
    }

    @Step
    public void createGroupModalPageCheckFieldError(String errorMessage) {
        createGroupModalPage.getItems().get("name").getFieldErrorMessageChecker().assertFieldErrorMessage(errorMessage);
    }

    @Step
    public void editGroupModalPageCheckFieldError(String errorMessage) {
        editGroupModalPage.getItems().get("name").getFieldErrorMessageChecker().assertFieldErrorMessage(errorMessage);
    }
}
