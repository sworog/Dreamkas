package project.lighthouse.autotests.steps.product;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.TimeoutException;
import project.lighthouse.autotests.elements.bootstrap.SimplePreloader;
import project.lighthouse.autotests.helper.StringGenerator;
import project.lighthouse.autotests.objects.web.product.ProductCollection;
import project.lighthouse.autotests.pages.catalog.group.GroupPage;
import project.lighthouse.autotests.pages.catalog.group.modal.CreateNewProductModalWindow;
import project.lighthouse.autotests.pages.catalog.group.modal.EditProductModalWindow;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;
import static org.junit.Assert.fail;

public class ProductSteps extends ScenarioSteps {

    GroupPage groupPage;
    CreateNewProductModalWindow createNewProductModalWindow;
    EditProductModalWindow editProductModalWindow;

    private ExamplesTable examplesTable;
    private String name;

    private static final String NO_PRODUCTS_MESSAGE = "В этой группе пока нет ни одного товара.";

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
    public void editNewProductModalWindowInput(ExamplesTable examplesTable) {
        editProductModalWindow.inputTable(examplesTable);
        this.examplesTable = examplesTable;
    }

    @Step
    public void createNewProductModalWindowConfirmOkClick() {
        createNewProductModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void editProductModalWindowConfirmOkClick() {
        editProductModalWindow.confirmationOkClick();
        new SimplePreloader(getDriver()).await();
    }

    @Step
    public void assertCreateNewProductModalWindowMarkUpValue(String value) {
        assertThat(createNewProductModalWindow.getMarkUpValueWebElement().getText(), is(value));
    }

    @Step
    public void assertEditProductModalWindowMarkUpValue(String value) {
        assertThat(editProductModalWindow.getMarkUpValueWebElement().getText(), is(value));
    }

    @Step
    public void assertCreateNewProductModalWindowMarkUpIsNotVisible() {
        if (!createNewProductModalWindow.invisibilityOfElementLocated(createNewProductModalWindow.getMarkUpValueWebElement())) {
            fail("The markUp value is visible in create new product modal window");
        }
    }

    @Step
    public void assertEditProductModalWindowMarkUpIsNotVisible() {
        if (!editProductModalWindow.invisibilityOfElementLocated(editProductModalWindow.getMarkUpValueWebElement())) {
            fail("The markUp value is visible in create new product modal window");
        }
    }

    @Step
    public void productCollectionCompareWithExampleTable(ExamplesTable examplesTable) {
        ProductCollection productCollection = null;
        try {
            productCollection = groupPage.getProductCollection();
        } catch (TimeoutException e) {
            groupPage.containsText(NO_PRODUCTS_MESSAGE);
        } catch (StaleElementReferenceException e) {
            productCollection = groupPage.getProductCollection();
        } finally {
            if (productCollection != null) {
                productCollection.compareWithExampleTable(examplesTable);
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
    public void editProductModalWindowCloseIconClick() {
        editProductModalWindow.closeIconClick();
    }

    @Step
    public void productCollectionNotContainProductWithName(String name) {
        ProductCollection productCollection = null;
        try {
            productCollection = groupPage.getProductCollection();
        } catch (TimeoutException e) {
            groupPage.containsText(NO_PRODUCTS_MESSAGE);
        } catch (StaleElementReferenceException e) {
            productCollection = groupPage.getProductCollection();
        } finally {
            if (productCollection != null) {
                productCollection.notContains(name);
            }
        }
    }

    @Step
    public void productCollectionContainProductWithName(String name) {
        ProductCollection productCollection = null;
        try {
            productCollection = groupPage.getProductCollection();
        } catch (TimeoutException e) {
            groupPage.containsText(NO_PRODUCTS_MESSAGE);
        } catch (StaleElementReferenceException e) {
            productCollection = groupPage.getProductCollection();
        } finally {
            if (productCollection != null) {
                productCollection.contains(name);
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

    @Step
    public void assertCreateNewProductModalWindowFieldErrorMessage(String elementName, String errorMessage) {
        createNewProductModalWindow.getItems().get(elementName).getFieldErrorMessageChecker().assertFieldErrorMessage(errorMessage);
    }

    @Step
    public void assertEditProductModalWindowFieldErrorMessage(String elementName, String errorMessage) {
        editProductModalWindow.getItems().get(elementName).getFieldErrorMessageChecker().assertFieldErrorMessage(errorMessage);
    }

    @Step
    public void createNewProductModalWindowFieldGenerateText(String elementName, int number) {
        String generatedString = new StringGenerator(number).generateTestData();
        createNewProductModalWindow.input(elementName, generatedString);
        this.name = generatedString;
    }

    @Step
    public void productCollectionContainProductWithStoredName() {
        productCollectionContainProductWithName(name);
    }

    @Step
    public void editProductModalWindowFieldGenerateText(String elementName, int number) {
        String generatedString = new StringGenerator(number).generateString("b");
        editProductModalWindow.input(elementName, generatedString);
    }

    @Step
    public void createNewProductModalWindowInput(String elementName, String value) {
        createNewProductModalWindow.input(elementName, value);
    }
}
