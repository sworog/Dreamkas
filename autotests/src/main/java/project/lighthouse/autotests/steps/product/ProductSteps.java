package project.lighthouse.autotests.steps.product;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.TimeoutException;
import project.lighthouse.autotests.elements.bootstrap.SimplePreloader;
import project.lighthouse.autotests.objects.web.product.ProductCollection;
import project.lighthouse.autotests.pages.catalog.group.GroupPage;
import project.lighthouse.autotests.pages.catalog.group.modal.CreateNewProductModalWindow;
import project.lighthouse.autotests.pages.catalog.group.modal.EditProductModalWindow;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class ProductSteps extends ScenarioSteps {

    GroupPage groupPage;
    CreateNewProductModalWindow createNewProductModalWindow;
    EditProductModalWindow editProductModalWindow;

    private ExamplesTable examplesTable;

    @Step
    public void createNewProductButtonClick() {
        groupPage.createNewProductButtonClick();
    }

    @Step
    public void createNewProductModalWindowInput(ExamplesTable examplesTable) {
        createNewProductModalWindow.inputTable(examplesTable);
        this.examplesTable = examplesTable;
    }

    @Step
    public void createNewProductModalWindowConfirmOkClick() {
        createNewProductModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void productCollectionExactCompareWith(ExamplesTable examplesTable) {
        ProductCollection productCollection = null;
        try {
            productCollection = groupPage.getProductCollection();
        } catch (TimeoutException e) {
            groupPage.containsText("У вас пока продуктов.");
        } catch (StaleElementReferenceException e) {
            productCollection = groupPage.getProductCollection();
        } finally {
            if (productCollection != null) {
                productCollection.exactCompareExampleTable(examplesTable);
            }
        }
    }

    @Step
    public void productCollectionProductWithNameClick(String name) {
        groupPage.getProductCollection().clickByLocator(name);
    }

    @Step
    public void editProductModalWindowCheckStoredValues() {
        editProductModalWindow.checkValues(examplesTable);
    }

    @Step
    public void createNewProductModalWindowCloseIconClick() {
        createNewProductModalWindow.closeIconClick();
    }

    @Step
    public void productCollectionNotContainProductWithName(String name) {
        ProductCollection productCollection = null;
        try {
            productCollection = groupPage.getProductCollection();
        } catch (TimeoutException e) {
            groupPage.containsText("У вас пока продуктов.");
        } catch (StaleElementReferenceException e) {
            productCollection = groupPage.getProductCollection();
        } finally {
            if (productCollection != null) {
                productCollection.notContains(name);
            }
        }
    }

    @Step
    public void deleteButtonClick() {
        editProductModalWindow.deleteButtonClick();
    }

    @Step
    public void confirmDeleteButtonClick() {
        editProductModalWindow.confirmDeleteButtonClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void assertCreateNewProductModalWindowTitle(String title) {
        assertThat(createNewProductModalWindow.getTitleText(), is(title));
    }

    @Step
    public void assertEditProductModalWindowTitle(String title) {
        assertThat(editProductModalWindow.getTitleText(), is(title));
    }
}
