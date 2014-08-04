package project.lighthouse.autotests.steps.store;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.TimeoutException;
import project.lighthouse.autotests.elements.bootstrap.SimplePreloader;
import project.lighthouse.autotests.objects.web.store.StoreObjectCollection;
import project.lighthouse.autotests.pages.store.StoreListPage;
import project.lighthouse.autotests.pages.store.modal.StoreCreateModalWindow;
import project.lighthouse.autotests.pages.store.modal.StoreEditModalWindow;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class StoreSteps extends ScenarioSteps {

    StoreListPage storeListPage;
    StoreCreateModalWindow storeCreateModalWindow;
    StoreEditModalWindow storeEditModalWindow;

    @Step
    public void storeListPageOpen() {
        storeListPage.open();
    }

    @Step
    public void addStoreButtonClick() {
        storeListPage.addObjectButtonClick();
    }

    @Step
    public void storeCreateModalWindowInputValues(ExamplesTable examplesTable) {
        storeCreateModalWindow.fieldInput(examplesTable);
    }

    @Step
    public void storeEditModalWindowInputValues(ExamplesTable examplesTable) {
        storeEditModalWindow.fieldInput(examplesTable);
    }

    @Step
    public void storeCreateModalWindowConfirmButtonClick() {
        storeCreateModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void storeCreateModalWindowCloseIconClick() {
        storeCreateModalWindow.closeIconClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void storeEditModalWindowCloseIconClick() {
        storeEditModalWindow.closeIconClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void storeEditModalWindowConfirmButtonClick() {
        storeEditModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void storeObjectCollectionCompareWithExampleTable(ExamplesTable examplesTable) {
        StoreObjectCollection storeObjectCollection = null;
        try {
            storeObjectCollection = storeListPage.getStoreObjectCollection();
        } catch (TimeoutException e) {
            storeListPage.containsText("У вас ещё нет ни одного магазина ");
        } catch (StaleElementReferenceException e) {
            storeObjectCollection = storeListPage.getStoreObjectCollection();
        } finally {
            if (storeObjectCollection != null) {
                storeObjectCollection.compareWithExampleTable(examplesTable);
            }
        }
    }

    @Step
    public void storeObjectCollectionDoNotContainStoreWithName(String name) {
        StoreObjectCollection storeObjectCollection = null;
        try {
            storeObjectCollection = storeListPage.getStoreObjectCollection();
        } catch (TimeoutException e) {
            storeListPage.containsText("У вас ещё нет ни одного магазина ");
        } catch (StaleElementReferenceException e) {
            storeObjectCollection = storeListPage.getStoreObjectCollection();
        } finally {
            if (storeObjectCollection != null) {
                storeObjectCollection.notContains(name);
            }
        }
    }

    @Step
    public void storeObjectClickByName(String name) {
        storeListPage.getStoreObjectCollection().clickByLocator(name);
    }

    @Step
    public void assertStoreCreateModalWindowTitle(String title) {
        assertThat(storeCreateModalWindow.getTitleText(), is(title));
    }

    @Step
    public void assertStoreEditModalWindowTitle(String title) {
        assertThat(storeEditModalWindow.getTitleText(), is(title));
    }

    @Step
    public void assertStoresListPageTitle(String title) {
        assertThat(storeListPage.getTitle(), is(title));
    }
}
