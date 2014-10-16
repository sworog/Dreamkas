package ru.dreamkas.steps.store;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import ru.dreamkas.elements.bootstrap.SimplePreloader;
import ru.dreamkas.helper.StringGenerator;
import ru.dreamkas.pages.store.StoreListPage;
import ru.dreamkas.pages.store.modal.StoreCreateModalWindow;
import ru.dreamkas.pages.store.modal.StoreEditModalWindow;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class StoreSteps extends ScenarioSteps {

    StoreListPage storeListPage;
    StoreCreateModalWindow storeCreateModalWindow;
    StoreEditModalWindow storeEditModalWindow;

    private String generatedString;

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
        storeCreateModalWindow.input(examplesTable);
    }

    @Step
    public void storeEditModalWindowInputValues(ExamplesTable examplesTable) {
        storeEditModalWindow.input(examplesTable);
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
        storeListPage.compareWithExampleTable(examplesTable);
    }

    @Step
    public void storeObjectCollectionDoNotContainStoreWithName(String name) {
        storeListPage.collectionNotContainObjectWithLocator(name);
    }

    @Step
    public void storeObjectCollectionContainStoreWithName(String name) {
        storeListPage.collectionContainObjectWithLocator(name);
    }

    @Step
    public void storeObjectClickByName(String name) {
        storeListPage.clickOnCollectionObjectByLocator(name);
    }

    @Step
    public void assertStoreCreateModalWindowTitle(String title) {
        assertThat(storeCreateModalWindow.getTitle(), is(title));
    }

    @Step
    public void assertStoreEditModalWindowTitle(String title) {
        assertThat(storeEditModalWindow.getTitle(), is(title));
    }

    @Step
    public void assertStoresListPageTitle(String title) {
        assertThat(storeListPage.getTitle(), is(title));
    }

    @Step
    public void assertStoreCreateModalWindowItemErrorMessage(String elementName, String message) {
        storeCreateModalWindow.checkItemErrorMessage(elementName, message);
    }

    @Step
    public void assertStoreEditModalWindowItemErrorMessage(String elementName, String message) {
        storeEditModalWindow.checkItemErrorMessage(elementName, message);
    }

    @Step
    public void storeCreateModalWindowGenerateString(String elementName, int count) {
        String generatedString = new StringGenerator(count).generateString("a");
        storeCreateModalWindow.input(elementName, generatedString);
        this.generatedString = generatedString;
    }

    @Step
    public void storeEditModalWindowGenerateString(String elementName, int count) {
        String generatedString = new StringGenerator(count).generateString("b");
        storeEditModalWindow.input(elementName, generatedString);
        this.generatedString = generatedString;
    }

    @Step
    public void storeObjectCollectionContainStoreWithStoredName() {
        storeObjectCollectionContainStoreWithName(generatedString);
    }
}
